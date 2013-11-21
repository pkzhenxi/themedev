<?php

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFix700Test extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();

	}

	/**
	 * WS-796 - Authorize.net & Axia Amex payments not being accepted
	 * @group taxout
	 */
	public function testWS796()
	{

		$checkoutForm = new CheckoutForm();
		$checkoutForm->shippingFirstName = "Kris";
		$checkoutForm->shippingLastName = "White";
		$checkoutForm->shippingAddress1 = "1409 Mullins Dr.";
		$checkoutForm->shippingCity = "Plano";
		$checkoutForm->shippingState = "TX";
		$checkoutForm->shippingPostal = "75025";
		$checkoutForm->shippingCountry = "US";
		$checkoutForm->acceptTerms=1;
		$checkoutForm->shippingProvider="1";
		$checkoutForm->shippingPriority="1";
		$checkoutForm->paymentProvider="61";



		$checkoutForm->scenario = "formSubmitGuest";

		$objCart = Yii::app()->shoppingcart;
		$objCart->addProduct('7up');

		//Pass an amex number as a visa
		$checkoutForm->cardType="VISA";
		$checkoutForm->cardNumber = "373285160709701";

	    $checkoutForm->validate();
		$retVal = $checkoutForm->getErrors();
		$this->assertArrayHasKey('cardNumber',$retVal);
		$this->assertContains("Invalid Card Number or Type mismatch",$retVal['cardNumber']);

		//Pass it as an Amex
		$checkoutForm->cardType="AMERICAN_EXPRESS";
		$checkoutForm->validate();
		$retVal = $checkoutForm->getErrors();
		$this->assertArrayNotHasKey('cardNumber',$retVal);

	}

	/**
	 * WS-791 - Error in payment form removes payment info fields from checkout page
	 * @group taxout
	 */
	public function testWS791()
	{
		//we just need to ensure our function that returns the shipping priority
		//is escaping reserved characters correctly

		$string = "We're only available during available office hours.";
		Yii::app()->session['ship.priorityRadio.cache'] = array('Store Pickup'=>$string);

		$controller = new BaseCheckoutForm();
		$retVal = $controller->getSavedPrioritiesRadio();

		$this->assertContains("We\'re",$retVal);

	}
}


