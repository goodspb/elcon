<?php
use Common\Exception\NotFoundException;
use Common\Log;
use Common\Lang;

/**
 * return language translate
 * @param $key
 * @return mixed
 */
function lang($key)
{
    return Lang::get($key);
}

/**
 * Show execution trace for debugging
 * @param bool $exit Set to true to exit after show trace.
 * @param bool $print Set to true to print trace
 *
 * @return string
 */
function showTrace($exit = true, $print = true)
{
    $e = new Exception;
    if ($print) {
        echo '<pre>', $e->getTraceAsString(), '</pre>';
    }
    if ($exit) {
        exit;
    }
    return $e->getTraceAsString();
}

/**
 * Safely get child value from an array or an object
 *
 * Usage:
 * Assume you want to get value from a multidimensional array like: $array = ['l1' => ['l2' => 'value']],
 * then you can try following:
 * $l1 = fnGet($array, 'l1'); // returns ['l2' => 'value']
 * $l2 = fnGet($array, 'l1/l2'); // returns 'value'
 * $undefined = fnGet($array, 'l3'); // returns null
 *
 * You can specify default value for undefined keys, and the key separator:
 * $l2 = fnGet($array, 'l1.l2', null, '.'); // returns 'value'
 * $undefined = fnGet($array, 'l3', 'default value'); // returns 'default value'
 *
 * @param array|object $array Subject array or object
 * @param $key
 * @param mixed $default Default value if key not found in subject
 * @param string $separator Key level separator, default '/'
 *
 * @return mixed
 */
function fnGet(&$array, $key, $default = null, $separator = '/')
{
    if (false === $subKeyPos = strpos($key, $separator)) {
        if (is_object($array)) {
            return property_exists($array, $key) ? $array->$key : $default;
        }
        return isset($array[$key]) ? $array[$key] : $default;
    } else {
        $firstKey = substr($key, 0, $subKeyPos);
        if (is_object($array)) {
            $tmp = property_exists($array, $firstKey) ? $array->$firstKey : null;
        } else {
            $tmp = isset($array[$firstKey]) ? $array[$firstKey] : null;
        }
        return fnGet($tmp, substr($key, $subKeyPos + 1), $default, $separator);
    }
}

/**
 * Translate
 *
 * @param string $name
 * @param array|null $params
 *
 * @return string
 */
function __($name = null, array $params = null)
{
    if (!$params) {
        return $name;
    }
    $replace = array();
    foreach ($params as $k => $v) {
        $replace[':' . $k] = $v;
    }
    $result = strtr($name, $replace);
    return $result;
}

/**
 * 判断是否SSL协议
 * @return boolean
 */
function is_ssl()
{
    if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
        return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
        return true;
    }
    return false;
}

function url($path, $queries = array(), $secure = null)
{
    if (substr($path, 0, 2) == '//' || ($prefix = substr($path, 0, 7)) == 'http://' || $prefix == 'https:/') {
        return $path;
    }
    $secure === null and $secure = is_ssl();
    $protocol = $secure ? 'https://' : 'http://';
    $host = fnGet($_SERVER, 'HTTP_HOST');
    $base = $_SERVER['SCRIPT_NAME'];
    $base = trim(dirname($base), '/');
    $base and $base .= '/';
    $url = $protocol . $host . '/' . $base;
    $url .= $path;
    if ($queries && is_array($queries)) {
        $queries = http_build_query($queries);
    }
    $queries && is_string($queries) and $url .= '?' . str_replace('?', '', $queries);
    return $url;
}

function send_http_status($code)
{
    $statuses = [
        // INFORMATIONAL CODES
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // SUCCESS CODES
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        226 => 'IM Used',
        // REDIRECTION CODES
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy', // Deprecated
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        // CLIENT ERROR
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',
        // SERVER ERROR
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];
    if (isset($statuses[$code])) {
        header('HTTP/1.1 ' . $code . ' ' . $statuses[$code]);
        header('Status:' . $code . ' ' . $statuses[$code]);
    }
}

function exceptionHandler(Exception $exception)
{
    profilerStop();
    Log::exception($exception);
    if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
        send_http_status(404);
        header('content-type: application/json');
        echo json_encode([
            'error_code' => 404,
            'error_msg' => '404 Not Found',
        ]);
        return;
    }

    $debug = \Common\Config::get('app.debug');
    if (!$debug) {
        send_http_status(500);
        header('content-type: application/json');
        echo json_encode([
            'error_code' => 500,
            'error_msg' => 'Server down, please check log!',
        ]);
    } else {
        echo <<<EOF
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Exception Debug</title>
            </head>
            <body>
                <h2>{$exception->getMessage()}</h2>
EOF;
        $traces = $exception->getTraceAsString();
        $traces = explode("\n",$traces);
        echo '<table>';
        foreach ($traces as $trace) {
            echo '<tr style="height: 25px;"><td>'.$trace."</td></tr>";
        }
        echo "</table></body></html>";
    }
}

function errorHandler($errNo, $errStr, $errFile, $errLine)
{
    throw new ErrorException($errNo . ' - ' . $errStr, $errNo, 1, $errFile, $errLine);
}

function profilerStart()
{
    if (isset($_SERVER['ENABLE_PROFILER']) && function_exists('xhprof_enable')) {
        xhprof_enable(0, array(
            'ignored_functions' => array(
                'call_user_func',
                'call_user_func_array',
            ),
        ));
    }
}

function profilerStop()
{
    if (isset($_SERVER['ENABLE_PROFILER']) && function_exists('xhprof_enable')) {
        static $profiler;
        static $profilerDir;
        $profilerDir || is_dir($profilerDir = storagePath('profiler')) or mkdir($profilerDir, 0777, true);
        $pathInfo = strtr($_SERVER['REQUEST_URI'], array('/' => '|'));
        $microTime = explode(' ', microtime());
        $reportFile = $microTime[1] . '-' . substr($microTime[0], 2) . '-' . $_SERVER['REQUEST_METHOD'] . $pathInfo;
        $profiler or $profiler = new XHProfRuns_Default($profilerDir);
        $profiler->save_run(xhprof_disable(), 'service', $reportFile);
    }
}

function storagePath($path = null)
{
    $dir = ROOT_PATH . '/storage' . ($path ? '/' . $path : '');
    is_dir($dir) OR mkdir($dir, 0777, true);
    return $dir;
}
