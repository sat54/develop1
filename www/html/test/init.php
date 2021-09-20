<?php
/**
 * APP_ENV
 *
 **/
 if ($_SERVER['APP_ENV'] == 'production') {
    define('APP_ENV', 'production');
} else if ($_SERVER['APP_ENV'] == 'development') {
    define('APP_ENV', 'development');
} else if ($_SERVER['APP_ENV'] == 'local') {
    define('APP_ENV', 'local');
} else {
    echo "APP_ENV not configured.";
    exit;
}

if (! defined('__DIR__')) {
    define('__DIR__', dirname(__FILE__));
}
define('APP_ROOT', __DIR__);
define('WWW_ROOT', realpath(dirname(__FILE__) . '/public/'));
define('UPLOAD_PATH', realpath(dirname(__FILE__) . '/files/'));
if (isset($_SERVER['HTTP_HOST'])) {
    define('URI', (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"]);
}
ini_set('error_log', APP_ROOT.'/tmp/log/php_error.log');
if (APP_ENV == 'local') {
    ini_set('display_errors', 1);
}

/**
 * Exception, Error
 *
 */
set_error_handler('error_handler');
function error_handler($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_exception_handler('exception_handler');
function exception_handler($e) {
    if (ini_get('display_errors')) {
        echo '<pre>' . $e . '</pre>';
    } else {
        error_log($e);
        include WWW_ROOT.'/error.php';
        exit;
    }
}

/**
 * LoadFile
 *
 */
require_once APP_ROOT . '/vendor/autoload.php';
require_once APP_ROOT . '/app/functions/common.php';

/**
 * Application Configuration
 *
 */
 
//class loader
spl_autoload_register(function($name){
    $name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
    include __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';
});

//database
if (APP_ENV == 'production') {
    ORM::configure('mysql:host=localhost;dbname=db;charset=utf8mb4');
    ORM::configure('username', 'production');
    ORM::configure('password', 'password');
} elseif (APP_ENV == 'development') {
    ORM::configure('mysql:host=localhost;dbname=db;charset=utf8mb4');
    ORM::configure('username', 'development');
    ORM::configure('password', 'password');
} else {
    ORM::configure('mysql:host=db;dbname=mysql;charset=utf8mb4');
    ORM::configure('username', 'root');
    ORM::configure('password', 'root');
}
ORM::configure('driver_options', [
    PDO::ATTR_EMULATE_PREPARES         => false,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    PDO::ATTR_DEFAULT_FETCH_MODE       => PDO::FETCH_OBJ
]);


//configs
$configs = require_once APP_ROOT . '/config/config.php';

//session
session_name('user');
session_start();
