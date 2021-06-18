<?php

namespace Simple\Frame;
class Create
{
  /**
   * 功能：创建模板文件
   * @param $project
   */
  public static function createTemplate($project)
  {
    $project = ucfirst($project);
    //创建目录
    self::createDir('../app/' . $project . '/Model');
    self::createDir('../app/' . $project . '/Controller');
    self::createDir('../app/' . $project . '/View');
    //创建文件
    self::createFile('../app/' . $project . '/Model/IndexModel.php', $project, 'Model');
    self::createFile('../app/' . $project . '/Controller/IndexController.php', $project, 'Controller');
  }

  /**
   * 功能：创建模板目录
   * @param $project
   */
  public static function createDir($project)
  {
    header("Content-type:text/html;charset=utf-8");
    //检查app目录是否存在
    if (!is_dir($project)) {
      mkdir($project, 0777, true);
    }
  }

  /**
   * 功能：创建模板文件
   * @param $file
   * @param $model
   * @param $suffix
   */
  public static function createFile($file, $model, $suffix)
  {
    //判断文件是否存在
    if (!file_exists($file)) {
      $one = "\r\n";
      $two = "\r\n\r\n";
      $content = '';
      if ($suffix == 'Model') {
        $content = '<?php' . $two .
          'namespace App\\' . $model . '\Model;' . $two .
          'use Simple\DB;' . $two .
          'class IndexModel extends DB' . $one .
          '{' . $one .
          "}" . $one;
      } elseif ($suffix == 'Controller') {
        $content = '<?php' . $two .
          'namespace App\\' . $model . '\Controller;' . $two .
          'use Simple\Controller;' . $two .
          'class IndexController extends Controller' . $one .
          '{' . $one .
          '    public function index()' . $one .
          '    {' . $one .
          '        $this->display();' . $one .
          '    }' . $one .
          "}" . $one;
      }
      file_put_contents($file, $content);
    }
  }
}
