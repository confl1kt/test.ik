<?php

setlocale(LC_ALL, 'ru_RU');
error_reporting(E_ALL);
ini_set('display_errors', 'on');

ini_set('session.gc_maxlifetime', 24 * 3600);
ini_set('session.cookie_lifetime', 24 * 3600);

ini_set('session.save_path', realpath('protected/sessions/') );


// change the following paths if necessary
$yii='framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

//======= dynamic configuration ================================================
yii::setPathOfAlias('application', realpath('protected'));
yii::import('application.components.*');

$config = ConfigInFolders::merge(
        array('application.components','application.models','application.modules'), include $config);
//==============================================================================

$app = Yii::createWebApplication($config);

$install = new AutoInstall;
$install->Install();
yii::import('application.models.*');

$app->run();
