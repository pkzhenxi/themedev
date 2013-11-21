<?php


//ob_start("logit");
// change the following paths if necessary
$WEBROOT = dirname($filename).'/../../..';
$yiit= $WEBROOT.'/core/framework/yiit.php';
$config= $WEBROOT.'/core/protected/config/test.php';

$_SERVER['SCRIPT_FILENAME'] = $WEBROOT.'/index.php';
$_SERVER['SCRIPT_NAME'] = "/".basename($_SERVER['SCRIPT_FILENAME']);
$_SESSION['DUMMY']="nothing"; //needed to force creation of $_SESSION which is used in some tests

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

$_SERVER['HTTP_USER_AGENT'] = "Mozilla/5.0 (Windows NT 5.1; rv:15.0) Gecko/20100101 Firefox/15.0";
$_SERVER['REQUEST_URI'] = "index.php";

Yii::createWebApplication($config);
Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-cities");
