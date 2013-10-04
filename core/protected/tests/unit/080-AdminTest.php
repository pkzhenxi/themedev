<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * User: kris
	 * Date: 2012-08-09
	 * Time: 10:34 AM
	 * To change this template use File | Settings | File Templates.
	 */

	require_once "../bootstrap.php";
	require_once "PHPUnit/Autoload.php";

class AdminTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
		// Import controller
		Yii::import('application.modules.admin.*');
		Yii::import('application.modules.admin.components.*');
		Yii::import('application.modules.admin.controllers.*');
		Yii::import('application.modules.admin.views.*');
		Yii::import('application.modules.admin.views.default.*');

		$controller = new AdminModule("admin",Yii::app()->getModule("admin"));
		$controller->init();
	}


//	public function testChangeConf() {
//
//		$controller = new AdminController("default");
//
//		$retValue = $controller->actionExportconfig();
//		$this->assertTrue($retValue);
//
//
//	}




	public function testAdminConfiguration()
	{

		$controller = new DefaultController("default");
		Yii::app()->controller = $controller;

		$this->assertTrue($controller!=null);
		$this->assertInstanceOf('DefaultController', $controller);


		// Start catching output
		//ob_start();
		//ob_start();

		// Run action
		//$controller->actionSidebar();

		// Check generated XML
		//$this->assertEquals("Store",$controller->menuItems[0]['label']);
		//$this->assertEquals("nav-header",$controller->menuItems[0]['linkOptions']['class']);



	}

//	public function testAdminEdit()
//	{
//
//		$controller = new AdminBaseController("adminbase");
//		Yii::app()->controller = $controller;
//
//		$this->assertTrue($controller!=null);
//		$this->assertInstanceOf('AdminBaseController', $controller);
//
//
//		// Start catching output
//		//ob_start();
//		//ob_start();
//
//		// Run action
//		$controller->init();
//
//		// Check generated XML
//		$this->assertEquals('$this->menuItems not defined',$controller->menuItems[0]['label']);
//
//
//		//ob_clean();
//
//		$_GET['id']=2;
//		$_POST['Configuration'] =array(
//			0=>array('key_value'=>'LightSpeed Web Store yeah!'),
//			1=>array('key_value'=>'866-862-1801 x2'),
//			2=>array('key_value'=>'kris@xsilva.com'),
//			3=>array('key_value'=>'Amazing products available to order online!'),
//			4=>array('key_value'=>'123 Main St.'),
//			5=>array('key_value'=>'Anytown, USA 12345'),
//			6=>array('key_value'=>'MON / FRI: 9AM-9PM')
//
//		);
//
//
//		$retVal = $controller->actionEdit();
//		//$this->expectOutputString('<form id="checkout" action="index.php" method="post">');
//
//	}


}