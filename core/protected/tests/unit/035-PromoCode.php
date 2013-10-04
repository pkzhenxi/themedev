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

class PromoCodeTest extends PHPUnit_Framework_TestCase
{


	public function testGets()
	{

		$objPromoCode = PromoCode::LoadByCode('everything');

		$this->assertEquals('everything',$objPromoCode->Code);
		$this->assertEquals('category:Beverages',$objPromoCode->LsCodeArray[0]);
		$this->assertTrue($objPromoCode->Enabled);
		$this->assertFalse($objPromoCode->Except);
		$this->assertTrue($objPromoCode->HasRemaining);
		$this->assertTrue($objPromoCode->Started);
		$this->assertTrue($objPromoCode->Active);
		$this->assertFalse($objPromoCode->Expired);
		$this->assertEquals(15,$objPromoCode->Threshold);



		$objPromoCode = PromoCode::LoadByCode('expiredtest');
		$this->assertEquals('expiredtest',$objPromoCode->Code);
		$this->assertArrayNotHasKey(0,$objPromoCode->LsCodeArray);
		$this->assertFalse($objPromoCode->Active);
		$this->assertTrue($objPromoCode->Enabled);
		$this->assertFalse($objPromoCode->Except);
		$this->assertTrue($objPromoCode->HasRemaining);
		$this->assertTrue($objPromoCode->Started);
		$this->assertTrue($objPromoCode->Expired);
		$this->assertEquals(0,$objPromoCode->Threshold);


	}


	public function testValidatecode()
	{

		$form = new PromoCode();

		$form->code = "expiredtest";
		$form->validatePromocode('code',null);

		$errors = $form->getErrors();

		$this->assertEquals("Promo Code has expired or has been used up.",$errors['code'][0]);

		$form->clearErrors();

		$form->code = "notyet";
		$form->validatePromocode('code',null);

		$errors = $form->getErrors();
		$this->assertEquals("Promo Code is not active yet.",$errors['code'][0]);



	}


	public function testProductAffected()
	{
		//Add a clothing item to our cart
		$objCart = Cart::LoadByIdStr('WO-30001');
		$objItem = Product::LoadByCode('ZEKE-22');
		$objItem2 = Product::LoadByCode('DOGBONE123');
		if (count($objCart->cartItems)==2)
		{
			$objCart->AddProduct($objItem,1);
			$objCart->AddProduct($objItem2,2);
		}
		$objCart->UpdateCart();



		//Test some codes

		$objPromoCode = PromoCode::LoadByCode('fifty'); //Beverages only
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[0]); //7Up
		$this->assertTrue($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[1]); //Powerbar Pure & Simple Bar Cranberry/Oatmeal
		$this->assertFalse($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[2]); //Astro Devil Tee
		$this->assertFalse($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[3]); //Dog Bone
		$this->assertFalse($retVal);


		$objPromoCode = PromoCode::LoadByCode('everything'); //Beverages and clothing
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[0]); //7Up
		$this->assertTrue($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[1]); //Powerbar Pure & Simple Bar Cranberry/Oatmeal
		$this->assertFalse($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[2]); //Astro Devil Tee
		$this->assertTrue($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[3]); //Dog Bone
		$this->assertFalse($retVal);

		$objPromoCode = PromoCode::LoadByCode('threedollars'); //no restrictions
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[0]); //7Up
		$this->assertTrue($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[1]); //Powerbar Pure & Simple Bar Cranberry/Oatmeal
		$this->assertTrue($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[2]); //Astro Devil Tee
		$this->assertTrue($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[3]); //Dog Bone
		$this->assertTrue($retVal);

		$objPromoCode = PromoCode::LoadByCode('notbeverages'); //everything but beverages
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[0]); //7Up
		$this->assertFalse($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[1]); //Powerbar Pure & Simple Bar Cranberry/Oatmeal
		$this->assertTrue($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[2]); //Astro Devil Tee
		$this->assertTrue($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[3]); //Dog Bone
		$this->assertTrue($retVal);

		$objPromoCode = PromoCode::LoadByCode('house'); //house brand family only
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[0]); //7Up
		$this->assertFalse($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[1]); //Powerbar Pure & Simple Bar Cranberry/Oatmeal
		$this->assertFalse($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[2]); //Astro Devil Tee
		$this->assertFalse($retVal);
		$retVal = $objPromoCode->IsProductAffected($objCart->cartItems[3]); //Dog Bone
		$this->assertTrue($retVal);


	}

	public function testShipping()
	{

		$objCode = PromoCode::model()->findByPk(1);
		$this->assertFalse($objCode->Shipping);

		$objCode = PromoCode::LoadByShipping('freeshipping');
		$this->assertTrue($objCode->Shipping);

		PromoCode::DisableShippingPromoCodes();
		$objCode = PromoCode::LoadByShipping('freeshipping');
		$this->assertFalse($objCode->Enabled);

		PromoCode::EnableShippingPromoCodes();
		$objCode = PromoCode::LoadByShipping('freeshipping');
		$this->assertTrue($objCode->Enabled);
	}


	public function testLoadScenarios()
	{

		$objCode = PromoCode::LoadByCode('everything');
		$this->assertInstanceOf('PromoCode',$objCode);

		$objCode = PromoCode::LoadByShipping('freeshipping');
		$this->assertInstanceOf('PromoCode',$objCode);


	}


	public function testCheckoutApply()
	{

		$o = PromoCode::LoadByCode('house');
		$o->amount=10;
		$o->lscodes=null;
		$o->threshold=null;
		$o->valid_from=null;
		$o->valid_until=null;
		$o->qty_remaining=null;
		$o->save();

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

		$objPromo = new PromoCode();
		$objPromo->setScenario('checkout');

		//Blank entry field
		$objPromo->code = "";
		$retVal = $objPromo->validate();
		$this->assertFalse($retVal);
		$retVal = $form->error($objPromo,'code');
		$this->assertContains('Code cannot be blank',$retVal);

		$objPromo->code = "house";
		$retVal = $objPromo->validate();
		$this->assertTrue($retVal);



	}



}