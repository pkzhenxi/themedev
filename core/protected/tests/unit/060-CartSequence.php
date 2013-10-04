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

class CartSequence60 extends PHPUnit_Framework_TestCase
{


	/**
	 * @group taxout
	 */
	public function testApplyPromocode() {

		Yii::app()->user->logout();




		//Change price on item
		$objProduct = Product::LoadByCode('7Up');
		$objProduct->sell = 1.69;
		$objProduct->sell_web = 1.69;
		$objProduct->save();


		//Create a cart
		$objCart = Yii::app()->shoppingcart;
		$objCart->addProduct(88);

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

		$objPromo = new PromoCode();
		$objPromo->setScenario('checkout');

		//Blank entry field
		$objPromo->code = "";
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('Code cannot be blank',$retVal);

		//A promo code that doesn't exist
		$objPromo->code = "thisisbad";
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('Promo Code is invalid',$retVal);


		//A disabled promo code

		$obj = PromoCode::LoadByCode('threedollars');
		$obj->enabled=0;
		$obj->save();


		$objPromo->code = "threedollars";
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('Promo Code is invalid',$retVal);




		//Lets test a keyword
		_dbx("delete from xlsws_product_tags where product_id=88");
		$obj = PromoCode::LoadByCode('threedollars');
		$obj->lscodes='keyword:art';
		$obj->enabled=1;
		$obj->save();

		$objPromo->code = "threedollars";
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('We are sorry, but one or more of the items in your cart cannot be used with',$retVal);
		$objPromo->clearErrors();

		//Now add tag
		$ot = Tags::model()->findByAttributes(array('tag'=>'art'));
		$oTagAssc = new ProductTags();
		$oTagAssc->product_id = $objProduct->id;
		$oTagAssc->tag_id = $ot->id;
		$oTagAssc->save();
		$objPromo->code = "threedollars";
		$retVal = $objPromo->validate();
		$this->assertTrue($retVal);

		$obj = PromoCode::LoadByCode('threedollars'); //and put back the test the way we found it
		$obj->lscodes=null;
		$obj->enabled=0;
		$obj->save();


		//Let's clear our cart and add a non beverage item
		$objCart->clearCart();
		$retVal=$objCart->addProduct(29);
		$this->assertGreaterThan(0,$retVal);

		$objPromoCode = PromoCode::LoadByCode('fifty');
		$objPromoCode->threshold = 100;
		$objPromoCode->save();
		$objPromoCode->refresh();

		//test threshold, our cart is low
		$objPromo->code = "fifty";
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('Promo Code only valid when your purchases total at least $100.00',$retVal);

		//Put threshold back
		$objPromoCode->threshold = 0;
		$objPromoCode->save();
		$objPromoCode->refresh();

		//test non applicable code
		$objPromo->code = "fifty";
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('We are sorry, but one or more of the items in your cart cannot be used with Promo Code.',$retVal);



		//WHILE WE'RE HERE -- let's test our free shipping code
		//Set a criteria with our cart
		$objPromoCode2 = PromoCode::LoadByShipping('freeshipping');
		$objPromoCode2->lscodes = 'shipping:,category:Beverages';
		$objPromoCode2->save();
		$objPromoCode2->refresh();

		//test non applicable code
		$objPromo->code = $objPromoCode2->code;
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('Free Shipping only valid when your purchases total at least $15.00',$retVal);

		//Add enough to beat threshold
		$retVal=$objCart->addProduct(29,3);

		//test non applicable code
		$objPromo->code = $objPromoCode2->code;
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('We are sorry, but one or more of the items in your cart cannot be used with Free Shipping.',$retVal);

		//Put a qualifying item in the cart
		$retVal=$objCart->addProduct(88);
		$this->assertGreaterThan(0,$retVal);

		//Free Shipping currently set to All Items, so this should fail
		$objPromo->code = $objPromoCode2->code;
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('We are sorry, but one or more of the items in your cart cannot be used with Free Shipping',$retVal);

		//Clear cart and just do beverages, which should then pass
		$objCart->clearCart();
		$retVal=$objCart->addProduct(88,31);
		$retVal = $objPromo->validate();
		$this->assertTrue($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertEquals('',$retVal);


		//Now back to our other code
		$objPromo->code = "fifty";

		$retVal = $objPromo->validate();
		$this->assertTrue($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertEquals('',$retVal);


		//Check our cart controller for JSON reply
		$_POST['CheckoutForm']['promoCode'] = $objPromo->code;
		$cartController->actionApplyPromocode();

		$retVal = ob_get_contents();
		ob_clean();

		$this->assertContains('"action":"success","errormsg":"Promo Code applied at 50%.",',$retVal);
		$this->assertContains('<strike>$1.69',$retVal);


	}


	public function testTag()
	{
		$productTags = ProductTags::model()->findAllByAttributes(array('product_id'=>88));
		foreach($productTags as $tag)
			$this->assertNotEmpty($tag->tag->tag);

	}

	/**
	 * @group taxout
	 */
	public function testPriceRestore()
	{

		//Change price on item
		$objProduct = Product::LoadByCode('7Up');
		$objProduct->sell = 1.69;
		$objProduct->sell_web = 1.69;
		$objProduct->save();

		//Lets create another cart in progress
		$objCartx = new Cart;
		$objCartx->customer_id=1;
		$objCartx->cart_type=CartType::cart;
		$objCartx->save();
		$objCartx->AddProduct(Product::LoadByCode('NonPowerBar'));
		$objCartx->AddProduct(Product::LoadByCode('NonPowerBar'));
		$objCartx->AddProduct(Product::LoadByCode('SPTURKEY'));
		$objCartx->AddProduct(Product::LoadByCode('7Up'));
		$objCartx->save();


		//Now let's use a customer record and log in as them
		$objCustomer = Customer::model()->findByPk(1);
		$strPassword = _xls_decrypt($objCustomer->password);


		//Perform login procedure
		$identity=new UserIdentity($objCustomer->email,$strPassword);
		$identity->authenticate();
		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
		if($identity->errorCode==UserIdentity::ERROR_NONE)
		{
			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			$duration=3600*24*30; // 30 days
			Yii::app()->user->login($identity,$duration);

			//Assign customer to cart
			Yii::app()->shoppingcart->assignCustomer(1);

			//Since we have successfully logged in, see if we have a cart in progress
			Yii::app()->shoppingcart->loginMerge();

			//Verify prices haven't changed
			//Yii::app()->shoppingcart->verifyPrices();
		}

		//Load a cart which should load our previous cart in progress automatically
		$objCart = Yii::app()->shoppingcart;
		echo "cart object is id ".$objCart->id."\n";
		//$this->assertEquals(13,$objCart->id);
		$this->assertEquals(1,$objCart->customer_id);
		Yii::app()->user->logout();
		Yii::app()->user->clearStates();

		//Change price on item
		$objProduct->refresh();
		$objProduct->sell = 0.99;
		$objProduct->sell_web = 0.99;
		$objProduct->save();
		$objProduct->refresh();

		//Log in again
		$identity->authenticate();
		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
		if($identity->errorCode==UserIdentity::ERROR_NONE)
		{
			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			$duration=3600*24*30; // 30 days
			Yii::app()->user->login($identity,$duration);
			echo "loginmerge";

			$objCart = Yii::app()->shoppingcart;
			echo "cart object is id ".$objCart->id."\n";

			$objCart->assignCustomer(1);
			//Since we have successfully logged in, see if we have a cart in progress
			Yii::app()->shoppingcart->loginMerge();
			echo "verf";
			//Verify prices haven't changed
			Yii::app()->shoppingcart->verifyPrices();
		} else echo "had an error logging in";

		$arrFlashes = Yii::app()->user->getFlashes();
		print_r($arrFlashes);
		$this->assertEquals("Your prior cart has been restored.",$arrFlashes['success']);
		$this->assertEquals("The item 7Up Soda 12 ounce can in your cart has decreased in price to $0.99.<br>",$arrFlashes['info']);


		Yii::app()->user->logout();
		Yii::app()->user->clearStates();

	}


	/**
	 * @group taxout
	 */
	public function testZipValidation() {

		Yii::app()->user->logout();


		//Create a cart
		$objCart = Yii::app()->shoppingcart;
		$objCart->addProduct(88);

		$model = new CheckoutForm();

		$model->shippingAddress1  = "1409 Mullins Dr";
		$model->shippingState = 56;
		$model->shippingCountry = 224;


		$model->setScenario('formSubmitGuest');

		$model->validate();
		$arrErrors = $model->getErrors();
		$this->assertEquals('Zip/Postal cannot be blank.',$arrErrors['shippingPostal'][0]);

		//Now change to an invalid postal code
		$model->shippingPostal = "n0tv4lid";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();

		$this->assertTrue(isset($arrErrors['shippingPostal']));
		$this->assertEquals('Zip/Postal format is incorrect for this country.',$arrErrors['shippingPostal'][0]);

		//Now change to an valid postal code
		$model->shippingPostal = "78759";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();
		$this->assertTrue(!isset($arrErrors['shippingPostal']));

		//Now change to an valid postal code
		$model->shippingPostal = "78759-1234";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();
		$this->assertTrue(!isset($arrErrors['shippingPostal']));


		//Test Canada
		$model->shippingState = 146;
		$model->shippingCountry = 39;
		//Now change to an invalid postal code
		$model->shippingPostal = "12345";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();

		$this->assertTrue(isset($arrErrors['shippingPostal']));
		$this->assertEquals('Zip/Postal format is incorrect for this country.',$arrErrors['shippingPostal'][0]);


		//Now change to an valid postal code
		$model->shippingPostal = "H2V 1Z8";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();
		$this->assertTrue(!isset($arrErrors['shippingPostal']));



	}


	/**
	 * @group taxout
	 */
	public function testZipValidationBilling() {

		Yii::app()->user->logout();


		//Create a cart
		$objCart = Yii::app()->shoppingcart;
		$objCart->addProduct(88);

		$model = new CheckoutForm();

		$model->billingAddress1  = "1409 Mullins Dr";
		$model->billingState = 56;
		$model->billingCountry = 224;


		$model->setScenario('formSubmitGuest');

		$model->validate();
		$arrErrors = $model->getErrors();
		$this->assertEquals('Zip/Postal cannot be blank.',$arrErrors['billingPostal'][0]);

		//Now change to an invalid postal code
		$model->billingPostal = "n0tv4lid";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();

		$this->assertTrue(isset($arrErrors['billingPostal']));
		$this->assertEquals('Zip/Postal format is incorrect for this country.',$arrErrors['billingPostal'][0]);

		//Now change to an valid postal code
		$model->billingPostal = "78759";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();
		$this->assertTrue(!isset($arrErrors['billingPostal']));

		//Now change to an valid postal code
		$model->billingPostal = "78759-1234";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();
		$this->assertTrue(!isset($arrErrors['billingPostal']));


		//Test Canada
		$model->billingState = 146;
		$model->billingCountry = 39;
		//Now change to an invalid postal code
		$model->billingPostal = "12345";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();

		$this->assertTrue(isset($arrErrors['billingPostal']));
		$this->assertEquals('Zip/Postal format is incorrect for this country.',$arrErrors['billingPostal'][0]);


		//Now change to an valid postal code
		$model->billingPostal = "H2V 1Z8";
		$model->clearErrors();
		$model->validate();
		$arrErrors = $model->getErrors();

		$this->assertTrue(!isset($arrErrors['billingPostal']));



	}

}








