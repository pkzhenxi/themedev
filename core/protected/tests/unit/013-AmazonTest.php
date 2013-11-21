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

class AmazonTest extends PHPUnit_Framework_TestCase
{
	public $controller;
	public $set=0;


	public function setUp()
	{

		Yii::import('ext.wsamazon.*');

		_xls_set_conf('LANG_MENU',1); //turn on multilang so bullets parse correctly

		$module = "wsamazon";
		$this->controller = new $module;
		if ($this->set==0)
			$this->controller->init(); //Run init on module first
		$this->set++;

	}

	//Assumes customer information has been created in prior Unit Tests, these should be run in sequence so session tracks cart in progress
	public function testAmazon()
	{

		$objProduct = Product::model()->findByPk(88);

		$retVal =  $objProduct->category->integration->amazon->item_type_keyword;
		echo $objProduct->category->integration->amazon->product_type;
		echo $objProduct->category->integration->amazon->extra;
		$this->assertContains('FoodAndBeverages',$objProduct->category->integration->amazon->product_type);
		$this->assertEquals('Beverages',$objProduct->category->integration->amazon->extra);

	}



	public function testAmazonXML()
	{
		$objProduct = Product::model()->findByPk(88);


		$retVal = $this->controller->getUploadProductFeed($objProduct);

		$this->assertContains('<Value>078000012149</Value>',$retVal);
		$this->assertContains('<Title><![CDATA[7Up Soda 12 ounce can]]></Title>',$retVal);

		//echo $retVal;
	}


	public function testAmazonPhoto()
	{
		$objProduct = Product::model()->findByPk(88);

		$feed = $this->controller->getUploadPhotoFeed($objProduct);
		echo $feed;
		$feed = str_replace($_SERVER['testini']['SERVER_NAME'],"kris1.4004.lightspeedwebstore.com",$feed);

		$this->assertContains("7up-soda-12-ounce-can.png</ImageLocation>",$feed);

	}

	public function testAmazonBullets()
	{

		$objProduct = Product::model()->findByPk(88);

		$feed = $this->controller->getBulletPoints($objProduct);

		$this->assertContains("<BulletPoint><![CDATA[Comes in a green can]]></BulletPoint>",$feed);



	}

	//These tests are disabled here because in order to properly test, we have to have a real pending order on Amazon
//	public function testOrders()
//	{
//
//		$module = "wsamazon";
//
//		$component = new $module;
//		$component->init(); //Run init on module first
//		$actionName = "OnActionListOrders";
//
//		$objEvent = new CEventTaskQueue(get_class($this));
//		$retVal = $component->$actionName($objEvent);
//
//
//		$amzOrder = '107-7697406-4349830,2013-04-11';
//
//		$actionName = "onActionListOrderDetails";
//
//		$objEvent = new CEventTaskQueue(get_class($this),$amzOrder);
//		$retVal = $component->$actionName($objEvent);
//
//	}
//
//	public function testCart()
//	{
//
//		$amzOrder = '107-7697406-4349830';
//		$obj = Cart::LoadByIdStr($amzOrder);
//		if (!$obj)
//			$obj = new Cart(array('id_str'=>$amzOrder));
//
//		$obj->origin = "amazon";
//
//
//
//		echo $obj->id_str;
//		echo $obj->datetime_cre;
//
//	}

}


