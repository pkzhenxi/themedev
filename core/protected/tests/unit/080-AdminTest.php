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



	/**
	 * WS-863 - Disable Web Store functionality not supported on Cloud
	 *
	 * includes
	 * WS-894 - Disable custom.css editing for bronze release
	 * WS-901 - Remove Theme Gallery
	 * WS-866 - Disable manual theme uploading
	 * WS-865 - Disable SRO lookup
	 * WS-864 - Disable Advanced Payments
	 */

	public function testForCloudAccount()
	{
		$reset = false;

		if (!(_xls_get_conf('LIGHTSPEED_CLOUD')>0))
		{
			$reset = true;
			_xls_set_conf('LIGHTSPEED_CLOUD',date('dis'));   // 6 digits
		}

		Configuration::exportConfig();

		$controller = new ThemeController('theme');
		Yii::app()->controller = $controller;

		$this->assertTrue($controller!=null);
		$this->assertInstanceOf('ThemeController', $controller);

		$controller->currentTheme = isset(Yii::app()->theme) ? Yii::app()->theme->name : '';

		$value = new CInlineAction($controller,'index');

		$controller->setAction($value);
		$controller->beforeAction('index');

//		print_r($controller->menuItems);

		$this->assertFalse($controller->menuItems[2]['visible']);  //custom.com
		$this->assertFalse($controller->menuItems[3]['visible']);  //View Theme Gallery
		$this->assertFalse($controller->menuItems[4]['visible']);  //Upload Theme .Zip

		unset($controller);
		unset($value);

		$controller = new DefaultController('default');
		Yii::app()->controller = $controller;

		$this->assertTrue($controller!=null);
		$this->assertInstanceOf('DefaultController', $controller);

		$value = new CInlineAction($controller,'index');
		$controller->setAction($value);

		$controller->beforeAction('index');

//		print_r($controller->menuItems);

		$this->assertFalse($controller->menuItems[12]['visible']);  //SROs

		$tmp = Configuration::model()->findByAttributes(array('key_name'=>'TAX_INCLUSIVE_PRICING'));
		$this->assertNotEquals(15,$tmp->configuration_type_id); //will not be visible

		unset($controller);
		unset($value);

		$controller = new PaymentsController('payments');
		Yii::app()->controller = $controller;

		$this->assertTrue($controller!=null);
		$this->assertInstanceOf('PaymentsController', $controller);

		$value = new CInlineAction($controller,'index');
		$controller->setAction($value);

		$controller->beforeAction('index');

//		print_r($controller->menuItems);

		$i=0;
		while ($controller->menuItems[$i]['label']!='Advanced Integration Modules') $i++;
		$this->assertFalse($controller->menuItems[$i]['visible']);  //Advanced Integration Modules label
		$this->assertLessThan(13,count($controller->menuItems));   //if ALL advanced modules are disabled and all except 3 simple are visible

		unset($controller);
		unset($value);


		//undo db change
		if ($reset)
		{
			_xls_set_conf('LIGHTSPEED_CLOUD','0');
			Configuration::exportConfig();
		}


	}

}