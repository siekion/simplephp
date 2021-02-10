/**
 * 功能：使用ajax时，返回http开头的链接
 * @returns {string}
 */
function u(url) {
    return window.location.protocol + '//' + window.location.host + '/' + url;
}

/**
 * JS跳转
 * @param url
 */
function jump(url) {
    window.location.replace(window.location.protocol + '//' + window.location.host + '/' + url);
}

/**
 * 功能：将获取到的json数组反序列化为json对象。
 * @param data
 */
function json(data) {
    return jQuery.parseJSON(data);
}

/**
 * 判断值是否为空
 * @param v
 * @returns {boolean}
 */
function is_empty(v) {
    switch (typeof v) {
        case 'undefined':
            return true;
        case 'string':
            if (v.replace(/(^[ \t\n\r]*)|([ \t\n\r]*$)/g, '').length == 0) return true;
            break;
        case 'boolean':
            if (!v) return true;
            break;
        case 'number':
            if (0 === v || isNaN(v)) return true;
            break;
        case 'object':
            if (null === v || v.length === 0) return true;
            for (var i in v) {
                return false;
            }
            return true;
    }
    return false;
}