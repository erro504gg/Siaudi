<?php

// include do Zend Framework
$zend = dirname(__FILE__) . "/protected/common/";
set_include_path(get_include_path() . PATH_SEPARATOR . $zend);

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../yii/framework/yii.php';

$config = dirname(__FILE__) . '/protected/config/main.php';
if (($_SERVER['SERVER_NAME'] == 'sisdep.conab.gov.br')) {
    define('YII_DEBUG', false);
    define('YII_TRACE_LEVEL', 1);
} else {
    define('YII_DEBUG', true);
    define('YII_TRACE_LEVEL', 3);
}

require_once(dirname(__FILE__).'/protected/messages/MensagemSistema.php');
require_once($yii);
Yii::createWebApplication($config)->run();
