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

		$CheckoutForm = new CheckoutForm();
		$CheckoutForm->promoCode = 'twentypercent';
		$_POST['CheckoutForm'] = $CheckoutForm;

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


