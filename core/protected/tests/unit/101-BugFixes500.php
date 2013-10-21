<?php

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFix500Test extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();
	}

	/*
	 * WS-548 - Product restrictions for shipping do not work
	 * @group taxout
	 */

	public function testWS548()
	{

		_dbx("update xlsws_product_related set autoadd=0 where product_id=18");
		define('ALL_PRODUCTS', 0);
		define('AT_LEAST_ONE_PRODUCT',2);

		// reset db just in case
		_dbx("DELETE FROM `xlsws_promo_code` WHERE `module`='flatrate';
			  DELETE FROM `xlsws_promo_code` WHERE `module`='tieredshipping';
			 ");

		//Lets enable and define some restrictions for a few shipping methods
		$modules = array(0=>array(0=>'tieredshipping',1=>'shipping:,keyword:yikes',2=>ALL_PRODUCTS),
			1=>array(0=>'flatrate',1=>'shipping:,category:Clothing',2=>AT_LEAST_ONE_PRODUCT)
		);

		foreach ($modules as $id => $value){
			$objPromoCode = new PromoCode();
			$objPromoCode->enabled = 1;
			$objPromoCode->exception = $value[2];
			$objPromoCode->code = $value[0].":";
			$objPromoCode->type = $objPromoCode->amount = 0;
			$objPromoCode->module = $value[0];
			$objPromoCode->lscodes = $value[1];
			$objPromoCode->save();
			unset($objPromoCode);
		}

		//Create a cart and add a product that should not trigger
		//our restricted methods

		$objCart = Yii::app()->shoppingcart;
		$objCart->addProduct(11);  //grilled cheese

		$cartController = new CartController('cart');

		$form = $cartController->beginWidget('CActiveForm', array(
			'id'=>'checkout',
			'enableClientValidation'=>false,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		));

		$retVal = ob_get_contents();
		$this->assertContains('<form id="checkout" action="index.php" method="post">',$retVal);

		ob_clean();
		ob_start();

		$this->assertEquals(1,count($objCart->cartItems));

		// Simulate user input shipping address
		$_POST['CheckoutForm']['shippingFirstName'] = 'Hilda';
		$_POST['CheckoutForm']['shippingLastName'] = 'Crocket';
		$_POST['CheckoutForm']['shippingAddress1'] = '1234 Fake Street';
		$_POST['CheckoutForm']['shippingCity'] = 'Miami';
		$_POST['CheckoutForm']['shippingCountry'] = 224; //usa
		$_POST['CheckoutForm']['shippingState'] = 20;  //florida
		$_POST['CheckoutForm']['shippingPostal'] = '33162';
		$_POST['CheckoutForm']['billingSameAsShipping'] = 1;

		// check cart controller for JSON reply
		$cartController->actionAjaxCalculateShipping();
		$retVal = ob_get_contents();
		ob_end_clean();

		//neither of these should trigger
		$this->assertNotContains('Tier Based',$retVal);
		$this->assertNotContains('Flat rate',$retVal);

		//fail attempt to trigger tiered shipping
		$objCart->addProduct(18); //bar of gold, with keyword 'yikes'
		$this->assertEquals(2,count($objCart->cartItems));

		print_r($objCart->cartItems);
		ob_clean();
		ob_start();
		$cartController->actionAjaxCalculateShipping();
		$retVal = ob_get_contents();
		ob_end_clean();

		$this->assertNotContains('Tier Based',$retVal); //because not all products meet requirement
		$this->assertNotContains('Flat rate',$retVal);  //no clothing products

		$objCart->addProduct(108);
		$this->assertEquals(3,count($objCart->cartItems));

		print_r($objCart->cartItems);
		ob_clean();
		ob_start();
		$cartController->actionAjaxCalculateShipping();
		$retVal = ob_get_contents();
		ob_end_clean();

		$this->assertNotContains('Tier Based',$retVal); //because not all products meet requirement
		$this->assertContains('Flat rate',$retVal);  //one clothing product


		//re-attempt to trigger tiered shipping
		$objCart->clearCart();
		$objCart->addProduct(18); //bar of gold, with keyword 'yikes'
		$this->assertEquals(1,count($objCart->cartItems));

		print_r($objCart->cartItems);
		ob_clean();
		ob_start();
		$cartController->actionAjaxCalculateShipping();
		$retVal = ob_get_contents();
		ob_end_clean();

		$this->assertContains('Tier Based',$retVal); //should work
		$this->assertNotContains('Flat rate',$retVal); //no clothing products


		//reset db
		_dbx("DELETE FROM `xlsws_promo_code` WHERE `module`='flatrate';
			  DELETE FROM `xlsws_promo_code` WHERE `module`='tieredshipping';
			 update xlsws_product_related set autoadd=1 where product_id=18;
			 ");

	}


	/**
	 * WS-555 - Shipping will not calculate as expected if selecting a billing address from the address book that is different from the shipping address
	 * @group taxout
	 */

	public function testWS555()
	{

		Yii::app()->user->logout();

		//lets login as a customer
		$objCustomer = Customer::model()->findByPk(3);
		$strPassword = _xls_decrypt($objCustomer->password);

		//perform login procedure
		$identity=new UserIdentity($objCustomer->email,$strPassword);
		$identity->authenticate();
		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
		if($identity->errorCode==UserIdentity::ERROR_NONE) {

			// 30 days
			Yii::app()->user->login($identity,3600*24*30);

			//Assign customer to cart
			Yii::app()->shoppingcart->assignCustomer(1);

			//Since we have successfully logged in, see if we have a cart in progress
			Yii::app()->shoppingcart->loginMerge();
		}

		//Create a cart and add a product
		$objCart = Yii::app()->shoppingcart;
		$objCart->addProduct(79);

		$cartController = new CartController('cart');

		$form = $cartController->beginWidget('CActiveForm', array(
			'id'=>'checkout',
			'enableClientValidation'=>false,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		));

		$retVal = ob_get_contents();

		$this->assertContains('<form id="checkout" action="index.php" method="post">',$retVal);

		ob_clean();
		ob_start();

		// Simulate user input shipping address
		$_POST['CheckoutForm']['shippingFirstName'] = 'Hilda';
		$_POST['CheckoutForm']['shippingLastName'] = 'Crocket';
		$_POST['CheckoutForm']['shippingAddress1'] = '1234 Fake Street';
		$_POST['CheckoutForm']['shippingCity'] = 'Vancouver';
		$_POST['CheckoutForm']['shippingCountry'] = 39;
		$_POST['CheckoutForm']['shippingState'] = 137;
		$_POST['CheckoutForm']['shippingPostal'] = 'V5T 3E2';

		// Simulate user choosing a listed address
		$_POST['CheckoutForm']['intBillingAddress'] = 5;
		$_POST['CheckoutForm']['billingSameAsShipping'] = 0;

		// Form chooses United States as default despite being hidden
		$_POST['CheckoutForm']['billingCountry'] = 224;

		// check cart controller for JSON reply
		$cartController->actionAjaxCalculateShipping();
		$retVal = ob_get_contents();
		ob_end_clean();

		$this->assertContains('"result":"success","provider":',$retVal);

	}


	/**
	 * WS-562 Promo Codes with a $ threshold can sometimes be applied below that threshold
	 * @group taxout
	 */

	public function testWS562()
	{

		//Delete the promo code in case we have it set up in prior tests
		_dbx("delete from xlsws_promo_code where code='twentypercent'");

		$objPromo = new PromoCode();
		$objPromo->enabled=1;
		$objPromo->exception=0;
		$objPromo->code='twentypercent';
		$objPromo->type = PromoCode::Percent;
		$objPromo->amount = 20;
		$objPromo->save();

		$PromoId = $objPromo->id;

		//Various promo code scenarios
		$objCart = Yii::app()->shoppingcart;
		$objProduct = Product::LoadByCode('Cupcake-T');
		$objCart->addProduct($objProduct);

		$model = new CheckoutForm();
		$model->promoCode = 'twentypercent';
		$_POST['CheckoutForm'] = $model;

		$cartController = new CartController('cart');
		ob_clean();
		ob_start();
		$cartController->actionApplyPromocode();
		$retVal = ob_get_contents();
		ob_end_clean();
		$this->assertContains('Promo Code applied at 20%',$retVal);
		$this->assertContains('$18.36',$retVal);

		$objCart->clearCart(); //Clear cart

		//Add two items to the cart
		$objCart->addProduct($objProduct,2);
		$objPromo->threshold = 30;
		$objPromo->save();
		ob_clean();
		ob_start();
		$cartController->actionApplyPromocode();
		$retVal = ob_get_contents();
		ob_end_clean();
		$this->assertContains('Promo Code applied at 20%',$retVal);
		$this->assertContains('$36.72',$retVal);

		//We need the row id here
		foreach($objCart->cartItems as $item)
			$id = $item->id; //we just have one
		//Now simulate updating the qty to 1
		$_POST['CartItem_qty_'.$id]=1;
		$_SERVER['HTTP_X_REQUESTED_WITH']='XMLHttpRequest'; //Fake an ajax request
		ob_clean();
		ob_start();
		$cartController->actionUpdateCart();
		$retVal = ob_get_contents();
		ob_end_clean();
		foreach(Yii::app()->user->getFlashes() as $key => $message) {
			echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
			$this->assertContains('twentypercent',$message);
			$this->assertContains('no longer applies to your cart and has been removed',$message);
		}


		//Now let's do the same thing for a logged in user
		$objCart->clearCart(); //Clear cart

	}




}


