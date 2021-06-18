<?php

namespace Simple\Core;

use PDO;
use PDOException;
use Simple\Frame\Error;

class Model
{
  private $dbms;   //数据库类型
  private $host;   //主机地址
  private $port;     //端口号
  private $user;     //用户名
  private $pass;     //密码
  private $dbname; //数据库名
  private $charset;//字符集
  private $dsn;      //数据源名称
  private $pdo;    //用于存放PDO的一个对象
  private static $instance;    // 静态私有属性用于保存单例对象
  protected $field = '*'; //字段
  protected $where = ''; //where条件
  protected $limit = ''; //limit
  protected $group = ''; //分组
  protected $order = ''; //排序
  protected $sql; //最后一次执行的SQL语句
  public $result; //最后一次执行的结果
  public $dataArray = array(); //要操作的数据


  /**
   * 功能：实例化model
   * model constructor.
   * @param $config
   */
  private function __construct($config)
  {
    $this->dbms = isset($config['dbms']) ? $config['dbms'] : 'myqsl';
    $this->host = isset($config['host']) ? $config['host'] : 'localhost';
    $this->port = isset($config['port']) ? $config['port'] : '3306';
    $this->user = isset($config['user']) ? $config['user'] : 'root';
    $this->pass = isset($config['pass']) ? $config['pass'] : '';
    $this->dbname = isset($config['dbname']) ? $config['dbname'] : '';
    $this->charset = isset($config['charset']) ? $config['charset'] : 'utf8';
    try {
      if ($this->dbms == 'mysql') {
        $this->dsn = "$this->dbms:host=$this->host;port=$this->port;dbname=$this->dbname;charset=$this->charset";
      } elseif ($this->dbms = 'sqlsrv') {
        $this->dsn = "$this->dbms:server=$this->host;port=$this->port;database=$this->dbname;charset=$this->charset";
      } else {
        return 'Simple框架不支持' . $this->dbms . '数据库!';
      }
      $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->pdo;
    } catch (PDOException $e) {
      $this->pdo_error($e);
    }
    return false;
  }

  /**
   * 功能：获取单例对象
   * @param $config
   * @return model
   */
  public static function getInstance($config)
  {
    $cName = get_called_class();
    if (!self::$instance instanceof self) {
      self::$instance = new $cName($config);
    }
    return self::$instance;
  }


  /**
   * 功能：输出错误信息
   * @param $e
   * @param string $sql
   * @return bool
   */
  private function pdo_error($e, $sql = '')
  {
    Error::try($e, $sql);
    return false;
  }

  public function distinct($field)
  {
    if (empty($field)) {
      return $this;
    }
    $this->field = 'distinct ' . $field;
    return $this;
  }

  /**
   * 功能：执行SQL语句
   * @param $sql
   * @return false|int
   */
  public function execSQl($sql, $resultKey = '')
  {
    if (env('SIMPLE_DEBUG')) {
      sqlLog($sql);
    }
    $this->sql = $sql;
    try {
      $result = $this->pdo->exec($sql);
    } catch (PDOException $e) {
      $this->pdo_error($e, $sql);
    }
    $this->field = '*';
    $this->where = '';
    $this->group = '';
    $this->order = '';
    if ($resultKey) {
      $this->result[$resultKey] = $result;
    } else {
      $this->result = $result;
    }
    return $result;
  }

  /**
   * 功能：查询所有数据
   * @return array
   */
  public function select($tabName, $resultKey = '')
  {
    $where = empty($this->where) ? '' : 'WHERE ' . $this->where;
    $limit = empty($this->limit) ? '' : 'LIMIT ' . $this->limit;
    $order = empty($this->order) ? '' : 'ORDER BY ' . $this->order;
    $sql = 'select ' . $this->field . ' from ' . $tabName . ' ' . $where . ' ' . $this->group . ' ' . $order . ' ' . $limit;
    if (env('SIMPLE_DEBUG')) {
      sqlLog($sql);
    }
    $this->sql = $sql;
    try {
      $stmt = $this->pdo->query($sql);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt->closeCursor();
    } catch (PDOException $e) {
      $this->pdo_error($e, $sql);
    }
    if ($resultKey) {
      $this->result[$resultKey] = $result;
    } else {
      $this->result = $result;
    }
    $this->field = '*';
    $this->where = '';
    $this->group = '';
    $this->order = '';
    return $result;
  }

  /**
   * 功能：查询一条数据
   * @return mixed
   */
  public function find($tabName, $resultKey = '')
  {
    $where = empty($this->where) ? '' : 'WHERE ' . $this->where;
    $limit = empty($this->limit) ? '' : 'LIMIT ' . $this->limit;
    $order = empty($this->order) ? '' : 'ORDER BY ' . $this->order;
    $sql = 'select ' . $this->field . ' from ' . $tabName . ' ' . $where . ' ' . $this->group . ' ' . $order . ' ' . $limit;
    if (env('SIMPLE_DEBUG')) {
      sqlLog($sql);
    }
    $this->sql = $sql;
    try {
      $stmt = $this->pdo->query($sql);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt->closeCursor();
    } catch (PDOException $e) {
      $this->pdo_error($e, $sql);
    }
    $this->field = '*';
    $this->where = '';
    $this->group = '';
    $this->order = '';
    if ($resultKey) {
      $this->result[$resultKey] = $result;
    } else {
      $this->result = $result;
    }
    return $result;
  }

  /**
   * 功能：添加数据
   * @param array $arr
   * @return bool
   */
  public function add($tabName, $arr = array(), $resultKey = '')
  {
    if (empty($arr) and empty($this->dataArray)) {
      $this->result = false;
      return false;
    } else {
      if (!empty($arr)) {
        $this->dataArray = $arr;
      }
      $sqlFieldStr = '';
      $sqlParamStr = '';
      $i = 0;
      foreach ($this->dataArray as $k => $v) {
        $sqlFieldStr .= ',' . $k;
        $sqlParamStr .= "'" . $v . "',";
        $i++;
      }
      $sqlFieldStr = substr($sqlFieldStr, 1);
      $sqlParamStr = substr($sqlParamStr, 0, -1);
      $sql = 'insert into ' . $tabName . '(' . $sqlFieldStr . ')' . ' values(' . $sqlParamStr . ')';
      if (env('SIMPLE_DEBUG')) {
        sqlLog($sql);
      }
      $this->sql = $sql;
      try {
        $result = $this->pdo->exec($sql);
      } catch (PDOException $e) {
        $this->pdo_error($e, $sql);
      }
      $this->field = '*';
      $this->where = '';
      $this->group = '';
      $this->order = '';
      if ($resultKey) {
        $this->result[$resultKey] = $result;
      } else {
        $this->result = $result;
      }
      return $result;
    }
  }

  /**
   * 功能：删除数据
   * @return bool
   */
  public function delete($tabName, $resultKey = '')
  {
    $where = empty($this->where) ? '' : 'WHERE ' . $this->where;
    $sql = 'delete from ' . $tabName . ' ' . $where;
    if (env('SIMPLE_DEBUG')) {
      sqlLog($sql);
    }
    $this->sql = $sql;
    try {
      $result = $this->pdo->exec($sql);
    } catch (PDOException $e) {
      $this->pdo_error($e, $sql);
    }
    $this->field = '*';
    $this->where = '';
    $this->group = '';
    $this->order = '';
    if ($resultKey) {
      $this->result[$resultKey] = $result;
    } else {
      $this->result = $result;
    }
    return $result;
  }


  /**
   * 功能：修改数据
   * @param array $arr
   * @return bool
   */
  public function update($tabName, $arr = array(), $resultKey = '')
  {
    if (empty($arr) and empty($this->dataArray)) {
      $this->result = false;
      return false;
    } else {
      if (!empty($arr)) {
        $this->dataArray = $arr;
      }
      $i = 0;
      $setStr = '';
      foreach ($this->dataArray as $k => $v) {
        $setStr .= ',' . $k . '="' . $v . '"';
        $i++;
      }
      $setStr = substr($setStr, 1);
      $where = empty($this->where) ? '' : 'WHERE ' . $this->where;
      $sql = 'update ' . $tabName . ' set ' . $setStr . ' ' . $where;
      if (env('SIMPLE_DEBUG')) {
        sqlLog($sql);
      }
      $this->sql = $sql;
      try {
        $result = $this->pdo->exec($sql);
      } catch (PDOException $e) {
        $this->pdo_error($e, $sql);
      }
      $this->field = '*';
      $this->where = '';
      $this->group = '';
      $this->order = '';
      if ($resultKey) {
        $this->result[$resultKey] = $result;
      } else {
        $this->result = $result;
      }
      return $result;
    }
  }

  /**
   * 功能：sql预处理
   * @param $sql
   * @param $arr
   */
  public function prepare($sql, $arr)
  {
    if (env('SIMPLE_DEBUG')) {
      sqlLog($sql);
    }
    $this->sql = $sql;
    try {
      $stmt = $this->pdo->prepare($sql);
      $result = $stmt->execute($arr);
    } catch (PDOException $e) {
      $this->pdo_error($e, $sql);
    }
  }

  /**
   * 功能：返回受上一个SQL语句影响的行数
   * @return int
   */
  public function rowCount()
  {
    try {
      $stmt = $this->pdo->query($this->sql);
      $count = $stmt->rowCount();
    } catch (PDOException $e) {
      $this->pdo_error($e, $this->sql);
    }
    return $count;
  }

  /**
   * 功能：返回结果集中的列数
   * @return int
   */
  public function columnCount()
  {
    try {
      $stmt = $this->pdo->query($this->sql);
      $count = $stmt->columnCount();
    } catch (PDOException $e) {
      $this->pdo_error($e, $this->sql);
    }
    return $count;
  }

  /**
   * 功能：获取下一行并作为一个对象返回
   * @return mixed
   */
  public function fetchObject()
  {
    try {
      $stmt = $this->pdo->query($this->sql);
      //$stmt->fetch(PDO::FETCH_BOJ);
      $object = $stmt->fetchObject();
    } catch (PDOException $e) {
      $this->pdo_error($e, $this->sql);
    }
    return $object;
  }

  /**
   * 判断用户输入的字段
   * @param $field
   * @return $this
   */
  public function filed($field)
  {
    if (empty($field)) {
      return $this;
    }
    $this->field = $field;
    return $this;
  }

  /**
   * 判断用户的查询条件
   * @param $where
   * @return $this
   */
  public function where($data, $value = '', $operator = '')
  {
    $where = '';
    if (is_array($data)) {
      foreach ($data as $k => $v) {
        $where .= $k . '="' . $v . '" ' . 'and ';
      }
      $where = substr($where, 0, -4);
    } else if ($value or strlen($value) > 0) {
      $where .= $data . '="' . $value . '" ' . $operator . ' ';
    } elseif (empty($data)) {
      return $this;
    } elseif (empty($value)) {
      $where .= $data;
    }
    $this->where .= $where;
    return $this;
  }

  /**
   * order条件
   * @param string $order 要输入的order条件
   * @return object 返回自己，保证连贯操作
   */
  public function order($order)
  {
    if (empty($order)) {
      return $this;
    }
    $this->order = $order;
    return $this;
  }

  public function group($group)
  {
    if (empty($group)) {
      return $this;
    }
    $this->group = 'group by ' . $group . ' ';
    return $this;
  }

  /**
   * limit条件
   * @param string $limit 要输入的limit条件
   * @return object 返回自己，保证连贯操作
   */
  public function limit($limit)
  {
    if (empty($limit)) {
      return $this;
    }
    $this->limit = $limit;
    return $this;
  }


  /**
   * [__clone 私有化克隆方法，保护单例模式]
   */
  private function __clone()
  {
  }


  /**
   * [__set 为一个不可访问的属性赋值的时候自动触发]
   * @param [string] $name  [属性名]
   * @param [mixed] $value [属性值]
   */
  public function __set($name, $value)
  {
    $allow_set = array('host', 'port', 'user', 'pass', 'dbname', 'charset');
    if (in_array($name, $allow_set)) {
      $this->$name = $value;
    }
  }


  /**
   * [__get *获得一个不可访问的属性的值的时候自动触发]
   * @param  [string] $name [属性名]
   * @return [string] $name的value [该属性名的值]
   */
  public function __get($name)
  {
    $allow_get = array('host', 'port', 'user', 'pass', 'dbname', 'charset');
    if (in_array($name, $allow_get)) {
      return $this->$name;
    }
  }

  /**
   * [__call 访问一个不可访问的对象方法的时候触发]
   * @param  [string] $name     [属性名]
   * @param  [array] $argument [参数列表]
   */
  public function __call($name, $argument)
  {
    echo "对不起,您访问的" . $name . "()方法不存在!<br />";
  }

  /**
   * [__callstatic 访问一个不可访问的类方法(静态方法)的时候触发]
   * @param  [string] $name     [属性名]
   * @param  [array] $argument [参数列表]
   */
  public static function __callStatic($name, $argument)
  {
    echo "对不起,您访问的" . $name . "()静态方法不存在!<br />";
  }

  /**
   * 释放资源
   */
  public function __destruct()
  {
    $this->where = '';
    $this->limit = '';
    $this->order = '';
  }

  /**
   * 返回最后执行的sql语句
   * @return mixed
   */
  public function sql()
  {
    return $this->sql;
  }
}
