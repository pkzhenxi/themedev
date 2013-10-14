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

class CartSequence61 extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{

		$obj = PromoCode::LoadByShipping('freeshipping');
		$obj->lscodes = 'shipping:,class:Beverages';
		$obj->save();

	}



	/**
	 * @group taxin
	 */
	public function testPricesTaxIn()
	{

		$objProduct = Product::LoadByCode("5Alive");
		$objProduct->sell=5;
		$objProduct->sell_web=4.50;
		$objProduct->sell_tax_inclusive=5.41;
		$objProduct->sell_web_tax_inclusive=4.87;
		$objProduct->save();

		echo "db sell: ".$objProduct->sell."\n";
		echo "db sell_web: ".$objProduct->sell_web."\n";
		echo "db sell_tax_inclusive: ".$objProduct->sell_tax_inclusive."\n";
		echo "db sell_web_tax_inclusive: ".$objProduct->sell_web_tax_inclusive."\n";


		$objCart = Yii::app()->shoppingcart;
		$objCart->addProduct($objProduct);
		//Because our cart is default and we're tax in, the store defaults to our tax code and should show tax inclusive

		//We are in a tax-in situation Should return 4.50
		$retVal = $objProduct->Price;
		echo "Price: ".$retVal."\n";
		$this->assertEquals('$4.87',$retVal);

		//We are in a tax-in situation. Should return 4.50
		$retVal = $objProduct->PriceValue;
		echo "PriceValue: ".$retVal."\n";
		$this->assertEquals('4.87',$retVal);

		//We are in a tax-in situation,. Should return 5.00
		$retVal = $objProduct->SlashedPrice;
		echo "SlashedPrice: ".$retVal."\n";
		$this->assertEquals('$5.41',$retVal);

		$retVal = $objProduct->SlashedPriceValue;
		echo "SlashedPrice: ".$retVal."\n";
		$this->assertEquals('5.41',$retVal);


		Yii::app()->shoppingcart->assignCustomer(2);
		//Now our cart is attached to a customer that is outside our tax area. Prices should now show without tax


		//We are in a tax-in situation, but outside the tax area. Should return 4.50
		$retVal = $objProduct->Price;
		echo "Price: ".$retVal."\n";
		$this->assertEquals('$4.50',$retVal);

		//We are in a tax-in situation, but outside the tax area. Should return 4.50
		$retVal = $objProduct->PriceValue;
		echo "PriceValue: ".$retVal."\n";
		$this->assertEquals('4.50',$retVal);

		//We are in a tax-in situation, but outside the tax area. Should return 5.00
		$retVal = $objProduct->SlashedPrice;
		echo "SlashedPrice: ".$retVal."\n";
		$this->assertEquals('$5.00',$retVal);

		$retVal = $objProduct->SlashedPriceValue;
		echo "SlashedPrice: ".$retVal."\n";
		$this->assertEquals('5.00',$retVal);



	}


	/**
	 * @group taxin
	 */
	public function testPromoTaxIn()
	{

		Yii::app()->user->logout();

		//Change price on item
		$objProduct = Product::LoadByCode('7Up');
		$objProduct->sell = 1.69;
		$objProduct->sell_web = 1.69;
		$objProduct->sell_tax_inclusive = 14.30;
		$objProduct->sell_web_tax_inclusive = 14.30;
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
		$this->assertContains('We are sorry, but one or more of the items in your cart cannot be used with',$retVal);



		//WHILE WE'RE HERE -- let's test our free shipping code
		//Set a criteria with our cart
		$objPromoCode2 = PromoCode::LoadByShipping('freeshipping');
		$objPromoCode2->lscodes = 'shipping:,category:Beverages';
		$objPromoCode2->save();
		$objPromoCode2->refresh();

		//test non applicable code
		$objPromo->code = "a";
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('Free Shipping only valid when your purchases total at least $15.00',$retVal);

		//Add enough to beat threshold
		$retVal=$objCart->addProduct(29,3);

		//test non applicable code
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('We are sorry, but one or more of the items in your cart cannot be used with',$retVal);

		//Put a qualifying item in the cart
		$retVal=$objCart->addProduct(88);
		$this->assertGreaterThan(0,$retVal);

		//Free Shipping currently set to All Items, so this should fail
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('We are sorry, but one or more of the items in your cart cannot be used with',$retVal);

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
		$this->assertContains('<strike>$14.30',$retVal);



	}





	/**
	 * @group taxin
	 */
	public function testPriceRestoreTaxIn()
	{

		//Change price on item
		$objProduct = Product::LoadByCode('7Up');
		$objProduct->sell= 2.00;
		$objProduct->sell_web = 2.00;
		$objProduct->sell_tax_inclusive = 14.30;
		$objProduct->sell_web_tax_inclusive = 14.30;
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
		$objProduct->sell = 1.99;
		$objProduct->sell_web = 0.99;
		$objProduct->sell_tax_inclusive = 2.14;
		$objProduct->sell_web_tax_inclusive = 1.24;
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









}