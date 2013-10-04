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

class ModulesTest extends PHPUnit_Framework_TestCase
{

	public function testListModules()
	{

		$objProduct = Product::LoadByCode('7up');


		$objEvent = new CEventProduct('LegacysoapController','onSaveProduct',$objProduct);
		_xls_raise_events('CEventProduct',$objEvent);

	}


	public function testModuleAmazonOrders()
	{
		$module="wsamazon";
		Yii::import('ext.'.$module.".".$module);

		$component = new $module;
		$component->attachEventHandler('OnActionListOrders',array($component,'OnActionListOrders'));

		$objEvent = new CEventTaskQueue('test');
		$component->init();
		$component->OnActionListOrders($objEvent);


	}

//	public function testModuleAmazonDetails()
//	{
//		$module="wsamazon";
//		Yii::import('ext.'.$module.".".$module);
//
//		$component = new $module;
//		$component->attachEventHandler('onActionListOrderDetails',array($component,'onActionListOrderDetails'));
//
//		$objEvent = new CEventTaskQueue('test');
//		$objEvent->data_id = '107-7697406-4349830';
//		$component->init();
//		$component->onActionListOrderDetails($objEvent);
//
//
//	}


	public function testModules()
	{

		$objModule = Modules::LoadByName('paypal');

		$arrValues = $objModule->GetConfigValues();
		$this->assertEquals('PayPal',$arrValues['label']);

		$retValue = $objModule->markup;
		$this->assertEquals(0,$retValue);

		$retValue = $objModule->payment_method;
		$this->assertEquals('Credit Card',$retValue);

		$retValue = $objModule->product;
		$this->assertEquals('SHIPPING',$retValue);


		$arrValues['ls_payment_method']='Web Credit Card';
		$objModule->SaveConfigValues($arrValues);
		$arrValues = $objModule->GetConfigValues();
		$this->assertEquals('Web Credit Card',$arrValues['ls_payment_method']);




	}


	public function testCEvent()
	{

		$arrModules =  Modules::model()->findAllByAttributes(array('category'=>'CEventCustomer'),array('order'=>'name')); //Get active and inactive
		foreach ($arrModules as $module)
		{
			Yii::import('application.extensions.'.$module->module.'.'.$module->module);
			$objC = Yii::app()->getComponent($module->module);
			//print_r($objC);die("end");
			//error_log($module->module);
			//error_log(Yii::app()->getComponent($module->module)->name);
		}




	}


}