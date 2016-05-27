<?php
error_reporting(E_ALL);

use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;

try {

    defined('RUNTIME', 'dev');

    define('APP_PATH', realpath('..') . '/');

    /*读配置*/
    $config = new ConfigIni(APP_PATH . 'app/config/config.ini');

    /*如果配置环境有配置，且启用，替换*/
    if(is_readable(APP_PATH . 'app/config/config.ini.dev')){
        $overrides = new ConfigIni(APP_PATH . 'app/config/config.ini.dev');
        $config->merge($overrides);
    }

    /*加载配置loader文件*/
    require APP_PATH . 'app/config/loader.php';

    /*加载配置服务services文件*/
    require APP_PATH . 'app/config/services.php';

    // Handle the request
    $application = new Application($di);

    echo $application->handle()->getContent();

} catch (Exception $e) {
    $log = array(
        'file' => $e -> getFile(),
        'line' => $e -> getLine(),
        'code' => $e -> getCode(),
        'msg' => $e -> getMessage(),
        'trace' => $e -> getTraceAsString(),
    );

    $date = date('YmdHis');
    $logger = new \Phalcon\Logger\Adapter\File(ROOT_PATH."/app/cache/logs/{$e->getCode()}_{$date}.log");
    $logger -> error(json_encode($log));
}
