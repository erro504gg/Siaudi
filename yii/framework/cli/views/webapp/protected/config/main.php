<?php

require_once 'Zend/Loader/Autoloader.php';
spl_autoload_unregister(array('YiiBase', 'autoload'));
spl_autoload_register(array('Zend_Loader_Autoloader', 'autoload'));
spl_autoload_register(array('YiiBase', 'autoload'));

//date_default_timezone_set('America/Sao_Paulo');
//"localhost", 11211
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
//print(dirname(__FILE__) . DIRECTORY_SEPARATOR );exit;
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'extensionPath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '../common/extensions',
    'name' => 'TÍTULO DO SISTEMA',
    'sourceLanguage' => 'pt_br',
    'language' => 'pt',
    'timeZone' => 'America/Sao_Paulo',
    'charset' => 'ISO-8859-1',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.models.form.*',
        'application.models.table.*',
        'application.models.view.*',
        'application.models.webservice.*',
        'application.components.*',
        'application.common.util.*',
        'application.modules.auditTrail.models.AuditTrail',
        'application.common.extensions.giix-components.*', // giix components
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array(
                'ext.giix-core', // giix generators
            ),
        ),
        'auditTrail' => array(
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            'class' => 'WebUser',
            'loginUrl' => array('site/logout'),
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'showScriptName' => false,
            'urlFormat' => 'path',
            'caseSensitive' => 'false',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'pgsql:host=10.1.0.200;port=5432;dbname=bd_siscorp',
            'emulatePrepare' => true,
            'username' => 'postgres',
            'password' => 'postgres',
            'charset' => 'latin1',
            'tablePrefix' => 'tb_',
            'enableProfiling' => YII_DEBUG,
            'enableParamLogging' => YII_DEBUG,
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                //uncomment the following to show log messages on web pages
//                array(
//                    'class' => 'CWebLogRoute',
//                    'levels' => 'trace',
//                    'categories' => 'system.db.*',
//                ),
                array(
                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                ),
            ),
        ),
        'widgetFactory' => array(
            'widgets' => array(
                'CDetailView' => array(
                    'nullDisplay' => ' ',
                ),
                'CJuiDatePicker' => array(
                    'language' => 'pt-BR',
                    'htmlOptions' => array('size' => 10),
                    'options' => array(
                        'showButtonPanel' => true,
                        'changeYear' => true,
                        'dateFormat' => 'dd/mm/yy',
                        'changeMonth' => true,
                        'changeYear' => true,
                        'buttonImage' => '/tiss_conab/images/calendar.gif',
                        'buttonImageOnly' => true,
                        'showOn' => 'button',
                    ),
                ),
                'CJuiAutoComplete' => array(
                    'options' => array(
                        'showAnim' => 'fold',
                        'minLength' => '2',
                    ),
                    'htmlOptions' => array(
                        'class' => 'autocomplete',
                        'size' => '40'
                    ),
                ),
            ),
        ),
        'format' => array(
            'class' => 'application.components.MyFormatter',
            'timeFormat' => 'H:i:s',
            'numberFormat' => array(
                'decimals' => '2',
                'decimalSeparator' => ',',
                'thousandSeparator' => '.',
            ),
            'booleanFormat' => array(Yii::t('app', 'Não'), Yii::t('app', 'Sim')),
        ),
        'clientScript' => array(
            'class' => 'ext.NLSClientScript',
        //'excludePattern' => '/\.tpl/i', //js regexp, files with matching paths won't be filtered is set to other than 'null'
        ),
//        'i18n' => array(
//            'class' => 'ext.DateTimeI18NBehavior',
//        ),
//        'LoggableBehavior' => array(
//            'class' => 'application.modules.auditTrail.behaviors.LoggableBehavior',
//        ),
        /* 'cache' => array(
          'class' => 'CMemCache',
          'servers' => array(
          array(
          'host' => 'localhost',
          'port' => 11211,
          ),
          ),
          ), */
        'request' => array(
            'class' => 'application.components.HttpRequest',
            'enableCsrfValidation' => false,
            'enableCookieValidation' => true,
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'email.sistema@conab.gov.br',
        'id_aplicacao' => '',
        'tema' => '', //blueTheme, brownTheme, greenTheme
        'schema' => '',
    ),
);
