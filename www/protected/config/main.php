<?php

Yii::setPathOfAlias('modules', 'protected/modules/');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Web Application',
    'preload' => array('log'),
    'sourceLanguage' => 'ru',
    'language' => 'ru',
    'defaultController' => 'site',
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'theme' => 'bootstrap',
    'modules' => array(
        'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii',
            ),
            'class' => 'system.gii.GiiModule',
            'password' => 'qq',
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'dbauth' => array(),
    ),
    'components' => array(
        'bootstrap' => array(
            'class' => 'bootstrap.components.Bootstrap',
        ),
        'cache' => array(
            'class' => 'system.caching.CFileCache',
            'cachePath' => 'protected/cache',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                //"gii"=>'/gii',    
                'logout' => 'site/logout',
                'login' => 'site/login',
                '<controller:(post|favorite|follower)>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:(post|favorite|follower)>/<action:\w+>' => '<controller>/<action>',
                '<id:[a-zA-Z0-9]+>/<action:\w+>' => 'user/<action>',
                '<id:[a-zA-Z0-9]+>' => 'user/view',
            ),
        ),
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=test.ik',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'tablePrefix' => '',
            'enableParamLogging' => true,
            'schemaCachingDuration' => 3600,
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'session' => array(
            'class' => 'system.web.CDbHttpSession',
            'connectionID' => 'db',
            'autoCreateSessionTable' => 'true',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters' => array('127.0.0.1'),
                ),
            ),
        ),
        'favorites' => array(
            'class'=>'application.components.Favorites',
        ),
        'follows' => array(
            'class'=>'application.components.Follows',
        ),
        'users' => array(
            'class'=>'application.components.Users',
        ),
    ),
    'params' => array(
        'adminEmail' => 'webmaster@example.com',
    ),
);