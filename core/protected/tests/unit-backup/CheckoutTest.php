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

class CheckoutTest extends PHPUnit_Framework_TestCase
{

	public function testPaymentDate()
	{
		$retVal['order_id'] = "WO-25231";
		$retVal['amount'] = "40.24";
		$retVal['data'] = "0TX037394N9456736";
		$retVal['payment_date'] = "14:56:35 Nov 28, 2012 PST";



		$objCart = Cart::LoadByIdStr($retVal['order_id']);
		if ($objCart instanceof Cart )
		{
			$objPayment = CartPayment::model()->findByPk($objCart->payment_id);
			$objPayment->payment_amount = isset($retVal['amount']) ? $retVal['amount'] : 0;
			$objPayment->payment_data = $retVal['data'];
			$objPayment->datetime_posted = isset($retVal['payment_date']) ? date("Y-m-d H:i:s",strtotime($retVal['payment_date'])) : new CDbExpression('NOW()');
			$objPayment->save();
		}
		$this->assertEquals('2012-11-28 17:56:35',$objPayment->datetime_posted);

	}


	public function testShippingModules() {



		$controller = new CartController("default");

		$controller->actionGetShippingModules();


	}


	public function testCountryList() {

		$obj = new CheckoutForm;

		$returnVal = $obj->getCountries();

		$this->assertArrayHasKey(224,$returnVal);
		//print_r($returnVal);




	}


	public function test_AjaxStates() {


		$url = "http://www.copper.site/cart/getdestinationstates";
		$post = "";
		$expected = "";
		//set the url, number of POST vars, POST data
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,           true );
		curl_setopt($ch, CURLOPT_POSTFIELDS,    $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/html; charset=utf-8', 'Content-Length: '.strlen($post) ));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		echo "respnse os ".$response;
		$this->assertEquals($expected,$response);




	}


	public function test_form() {

		$tx = new CartController;

		$form = $tx->beginWidget('CActiveForm', array(
			'id'=>'checkout',
			'enableClientValidation'=>false,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		));

		echo $form->getStates();


		// initialize a controller (which defaults to null in tests)
		$c = new CController('phpunit');
		$c->setAction(new CInlineAction($c, 'urltest'));
		Yii::app()->setController($c);


	}

}

