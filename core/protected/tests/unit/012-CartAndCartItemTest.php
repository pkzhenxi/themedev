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

class CartAndCartItemTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{

		Controller::initParams();
	}

	//Assumes customer information has been created in prior Unit Tests, these should be run in sequence so session tracks cart in progress
	public function testResetTestingEnvironment()
	{
		_dbx('SET FOREIGN_KEY_CHECKS=0;
			TRUNCATE TABLE `xlsws_cart_messages`;
			TRUNCATE TABLE `xlsws_cart_item`;
			TRUNCATE TABLE `xlsws_cart`;
			TRUNCATE TABLE `xlsws_cart_shipping`;
			TRUNCATE TABLE `xlsws_cart_payment`;
			TRUNCATE TABLE `xlsws_email_queue`;
			SET FOREIGN_KEY_CHECKS=1;
		');

		_dbx("update xlsws_configuration set key_value='30000' where key_name='NEXT_ORDER_ID'");

		$objProduct = Product::model()->findByPk(11);
		$objProduct->inventory_reserved=$objProduct->CalculateReservedInventory();
		$objProduct->inventory_avail=$objProduct->Inventory;
		$objProduct->save();

		$objProduct = Product::model()->findByPk(88);
		$objProduct->inventory_reserved=$objProduct->CalculateReservedInventory();
		$objProduct->inventory_avail=$objProduct->Inventory;
		$objProduct->save();

	}


	/** Raw test of initialize new object */
	public function testInitializeCart() {

		$objCart = Cart::InitializeCart();
		$this->assertInstanceOf('Cart',$objCart);

	}

	/** Check if cart exists in session, load otherwise create */
	public function testCreateCart() {

		$objCart = Cart::GetCart();
		$this->assertInstanceOf('Cart',$objCart);

	}

    /** Add customer to cart, adds customer object */
	public function testAddCustomer() {

		$objCustomer = Customer::model()->findByPk(1);
		$this->assertInstanceOf('Customer',$objCustomer);

		$objCart = Cart::GetCart();
		$this->assertInstanceOf('Cart',$objCart);

		$objCart->customer_id = $objCustomer->id;
		$blnResult = $objCart->save();
		print_r($objCart->getErrors());
		$this->assertEquals(true,$blnResult);

	}

	/** Add product to cart through AddProduct function directly */
	public function testAddProduct() {

		$objCart = Cart::GetCart();
		$this->assertInstanceOf('Cart',$objCart);

		$objProd = Product::model()->findByPk(11); //Test grilled cheese
		$this->assertInstanceOf('Product',$objProd);

		echo "price is ".$objProd->getPriceValue(1,1);

		$rowid = $objCart->AddProduct($objProd,1);
		echo "row is ".print_r($rowid,true);
		$this->assertGreaterThanOrEqual(1,$rowid);


		$arrItems = $objCart->cartItems;

		$this->assertEquals(11,$arrItems[0]->product_id);


	}

	/** AddToCart which in turn calls AddProduct if non-soap add */
	public function testAddToCart() {

		$objCart = Yii::app()->shoppingcart;
		$objCart->clearCart();

		$intProductId = 88; //7UP test product
		$intQty = 2;


		$objProduct = Product::model()->findByPk($intProductId);
		if ($objProduct) {
			$intRowId = $objCart->addProduct($objProduct,$intQty);
			echo $intRowId;
			$this->assertGreaterThanOrEqual(1,$intRowId);
		}

		$arrItems = $objCart->cartItems;

		$this->assertEquals(88,$arrItems[0]->product_id);
	}

	/** Query for pending orders */
	public function testPending() {
		$intPending = Cart::GetPending();
		$this->assertGreaterThanOrEqual(0,$intPending);

		
	}

	public function testNextId()
	{


		$this->assertEquals(0,Cart::GetCartLastIdStr()); //Because cart table has no completed orders with WO-, should be blank
		$objCart = Cart::InitializeCart();
		$this->assertEquals('WO-30000',$objCart->GetCartNextIdStr()); //should pull next from configuration
		$objCart->SetIdStr(); //finds and saves id_str to cart
		$this->assertEquals('WO-30000',$objCart->id_str); //verify that model really was saved

		$objCartOld = Cart::LoadByIdStr("WO-30000");
		$this->assertEquals('WO-30000',$objCartOld->GetCartNextIdStr()); //Should not have changed
		$this->assertEquals('WO-30000',$objCartOld->GetCartNextIdStr()); //Should not have changed
		$this->assertEquals('WO-30000',$objCartOld->GetCartNextIdStr()); //Should not have changed
		$this->assertEquals('WO-30000',$objCartOld->GetCartNextIdStr()); //Should not have changed
		$objCartOld->SetIdStr(); //Because already assigned, will return same

		$this->assertEquals('WO-30000',$objCartOld->id_str); //Should not have changed


		//Let's load up a cart we'll keep working with for the rest of the unit tests and finish it out

		$objCart = Cart::model()->findByPk(($objCartOld->id)-1);
		$objCart->SetIdStr(); //Should set next in sequence even though it's an earlier row id
		$objCart->UpdateCart();

		$this->assertEquals('WO-30001',$objCart->id_str);

		//Now that we have some cart table entries, this should now return the last used as a pure number
		$this->assertNotEquals('WO-30001',Cart::GetCartLastIdStr());
		$this->assertEquals(30001,Cart::GetCartLastIdStr());


		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(28.60,$objCart->total);
		else
			$this->assertEquals(3.38,$objCart->total);
		$this->assertEquals(1,$objCart->item_count);




	}

	public function testAttachingAddress()
	{
		$objCart = Cart::LoadByIdStr('WO-30001');
		$objBillAddress = CustomerAddress::model()->findByPk(1);
		$objShipAddress = CustomerAddress::model()->findByPk(2);

		$objCart->billaddress_id = $objBillAddress->id;
		$objCart->shipaddress_id = $objShipAddress->id;


		$this->assertEquals($objCart->shipaddress->first_name,'Thomas');
		$this->assertEquals($objCart->billaddress->city,'Los Angeles');

		$objCart->customer_id=1;
		$objCart->save();
	}


	public function testAddingShippingandPayment()
	{

		$objP = new CartPayment;
		$objP->payment_method='Web Credit Card';
		$objP->payment_module='paypalpro';
		$objP->payment_data='92K41173GW217453C';
		$objP->payment_amount=3.38;
		$objP->datetime_posted='2012-12-03 17:47:09';
		$objP->save();

		$this->assertEquals(1,$objP->id);

		$objCart = Cart::LoadByIdStr('WO-30001');
		$objCart->payment_id=$objP->id;
		$objCart->save();

		//See if we can get to it through the relation now
		$this->assertEquals('Web Credit Card',$objCart->payment->payment_method);
		$this->assertEquals('paypalpro',$objCart->payment->payment_module);
		$this->assertEquals('92K41173GW217453C',$objCart->payment->payment_data);
		$this->assertEquals(3.38,$objCart->payment->payment_amount);
		$this->assertEquals('Paypal Pro (TEST MODE)',$objCart->payment->payment_name);


		$objS = new CartShipping;
		$objS->shipping_method = 'SHIPPING';
		$objS->shipping_module = 'ups';
		$objS->shipping_data = 'UPS 3 Day Select';
		$objS->shipping_cost = 16.17;
		$objS->shipping_sell = 19.17;
		$objS->save();

		$this->assertEquals(1,$objS->id);

		$objCart->shipping_id=$objS->id;
		$objCart->save();

		//See if we can get to it through the relation now
		$this->assertEquals('SHIPPING',$objCart->shipping->shipping_method);
		$this->assertEquals('ups',$objCart->shipping->shipping_module);
		$this->assertEquals('UPS 3 Day Select',$objCart->shipping->shipping_data);
		$this->assertEquals(16.17,$objCart->shipping->shipping_cost);
		$this->assertEquals(19.17,$objCart->shipping->shipping_sell);



	}


	//Test links
	public function testGetLink() {

		//GetLink()
		//GenerateLink
		//LoadCartByLink()
		$objCart = Cart::LoadByIdStr('WO-30000');
		$objCart->GenerateLink();

		$link = $objCart->linkid;
		echo $link;
		$objCart->save();

		$this->assertGreaterThanOrEqual(20,strlen($link));

		//Full URL
		$strReturn = $objCart->Link; //Calls GetLink()
		$this->assertContains($link,$strReturn);
		$this->assertContains('cart/receipt',$strReturn);


		//ToDo: Revisit loading by link to determine best way to handle emailed carts
//		$objCart2 = Cart::LoadCartByLink($link);
//		$this->assertInstanceOf('Cart',$objCart2);


	}



	/**
	 * Test Emailing Receipt
	 * @group email
	 */
	public function testEmailReceipt()
	{



		$headers = array(
			'MIME-Version: 1.0',
			'Content-type: text/html; charset=utf8'
		);


		$objCart = Cart::LoadByIdStr('WO-30001');


		Yii::app()->controller = $controller = new CartController("default");

		$id = $controller->EmailReceipts($objCart);
		echo "id is $id";


		$objMails = EmailQueue::model()->findAllByAttributes(array('customer_id'=>$objCart->customer_id));

		foreach ($objMails as $objMail) {

			if ($objMail->to == "george@example.com") $objMail->to="kris.white@lightspeedretail.com";

			$this->assertContains('Billing',$objMail->htmlbody);
//			$blnResult =_xls_send_email($objMail->id,true);
//			$this->assertEquals(1,$blnResult); //1 is true, but assert doesn't think so so we can't use assertTrue
//			if($blnResult) $objMail->delete();
		}

	}

	public function testHtmlConversion()
	{

		$objHtml = new HtmlToText;

		$strText =  $objHtml->convert_html_to_text("<html><head><title>test</title></head><body>This is a test<p>of this page</p></body></html>");
	$this->assertEquals('This is a test
of this page',$strText);



	}


	public function testCartItemFunctions()
	{

		$objCart = Cart::LoadByIdStr('WO-30001');

		foreach ($objCart->cartItems as $item)
		{

			echo $item->code;

			//Testing the IsDiscounted
			$strReturn = $item->Discounted;
			$this->assertFalse($strReturn);

			$item->Discount = 0.03;
			$item->save();
			$strReturn = $item->Discounted;
			$this->assertTrue($strReturn);

			//Testing Price
			$strReturn = $item->Price;
			if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
				$this->assertEquals(14.27,$strReturn);
			else
				$this->assertEquals(1.66,$strReturn);

			$strReturn = $item->sell_total;
			if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
				$this->assertEquals(28.54,$strReturn);
			else
				$this->assertEquals(3.32,$strReturn);

		}


	}


	public function testSaveCart() { }



	public function testAddSoapProduct() { }
	public function testClearCart() { }


	public function testCloneCart() {

		$objCart = Cart::LoadByIdStr('WO-30001');
		$newCart = Cart::CloneCart($objCart);

		$this->assertInstanceOf('Cart',$newCart);
		$this->assertNotEquals($objCart->id,$newCart->id);


		$newCart->SetIdStr();
		$newCart->save();
		$this->assertEquals('WO-30002',$newCart->id_str);

	}




	public function testFullDelete() {

		$objCart = Cart::LoadByIdStr('WO-30002');
		$objCart->FullDelete();
		//$objCart->refresh();

		$objCart = Cart::LoadByIdStr('WO-30002');

		$this->assertNotInstanceOf('Cart',$objCart);

	}


	public function testCartDimensions() {

		//Our first cart has an item that does not have any dimensions but does have a weight
		$objCart = Cart::LoadByIdStr('WO-30001');

		//Should be zeros but we have qty of 2 for 0.5 lbs each
		$this->assertEquals(0,$objCart->Height);
		$this->assertEquals(0,$objCart->Width);
		$this->assertEquals(0,$objCart->Length);
		$this->assertEquals(1,$objCart->Weight);

		//Let's add a product with some dimensions

		$objProduct = Product::model()->findByPk(17);
		$retVal = $objCart->AddProduct($objProduct,2);
		$this->assertGreaterThanOrEqual(1,$retVal);

		$this->assertEquals(3,$objCart->Height);
		$this->assertEquals(3,$objCart->Width);
		$this->assertEquals(20,$objCart->Length); //cumulative
		$this->assertEquals(1.5,$objCart->Weight); //Added x2 @ 0.25lbs each




	}

	public function testTaxCalculation()
	{

		$fltPrice = 3.32;
		$taxCode = 104;
		$taxStatus = 0;

		list($fltTaxedPrice, $arrTaxes) = Tax::CalculatePricesWithTax($fltPrice, $taxCode, $taxStatus);

		$this->assertEquals(3.59,$fltTaxedPrice);
		$this->assertContains(0.2739,$arrTaxes);

	}

	public function testTaxScenarios() {

		//We need to apply various tax scenarios to this and test our calculations

		$objCart = Cart::LoadByIdStr('WO-30001');

		//Set tax in Texas
		$objCart->tax_code_id=104;
		$objCart->save();
		$objCart->UpdateTaxExclusive();
		$objCart->save();
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(0,$objCart->tax1);
		else
			$this->assertEquals(0.36795,$objCart->tax1);

		//Change tax to Utah
		$objCart->tax_code_id=146;
		$objCart->save();
		$objCart->UpdateCart();
		$objCart->save();
		$fltTotalAfter = $objCart->total;

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(0,$objCart->tax1);
		else
			$this->assertEquals(0.23415,$objCart->tax1);


		$objCart->UpdateCart();


		$expectedTotal = $objCart->subtotal + $objCart->shipping->shipping_sell
			+ round(round($objCart->tax1,2)+round($objCart->tax2,2)+
				round($objCart->tax3,2)+round($objCart->tax4,2)+round($objCart->tax5,2),2);


		$this->assertEquals($expectedTotal,$objCart->total);

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(0,$objCart->tax1);
		else
			$this->assertEquals(0.23415,$objCart->tax1);

		//Turn this on to test taxing shipping
		_xls_set_conf('SHIPPING_TAXABLE',1);

		//Set our shipping product to be Default status to we can test this.
		//Since our default unittest setup has the shipping product as NoTax
		$objShipProduct = Product::LoadByCode($objCart->shipping->shipping_method);
		$objShipProduct->tax_status_id = 0;
		$objShipProduct->save();


		$objCart->UpdateCart();
		$objCart->save();

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
		{
			$this->assertEquals(1.006425,$objCart->tax1);
			$this->assertEquals(49.92,$objCart->total);
		} else {
			$this->assertEquals(1.240575,$objCart->tax1);
			$this->assertEquals(24.87,$objCart->total);
		}


		_xls_set_conf('SHIPPING_TAXABLE',0);
		$objCart->UpdateCart();
		$objCart->save();
	}



	public function testSaveUpdatedCartItems() {

		$objCart = Cart::LoadByIdStr('WO-30001');
		foreach ($objCart->cartItems as $item)
			$item->qty += 2;
		$objCart->SaveUpdatedCartItems();
		foreach ($objCart->cartItems as $item)
			$this->assertEquals(4,$item->qty);

	}


	public function testGetPending() {

		$objCart = Cart::LoadByIdStr('WO-30001');
		$objCart->cart_type = CartType::order;
		$objCart->save();

		$retVal = Cart::GetPending();
		$this->assertEquals(1,$retVal);
	}

	public function testIsExpired() {

		$objCart = Cart::LoadByIdStr('WO-30001');

		$retVal = $objCart->IsExpired();
		$this->assertFalse($retVal);

		$objCart->datetime_due = "2012-05-05";
		$objCart->save();

		$retVal = $objCart->IsExpired();
		$this->assertTrue($retVal);

	}


	public function testLoadLastCartInProgress() {
		$objCartx = Cart::LoadByIdStr('WO-30000');
		$objCartx->customer_id=1;


		$objCartx->save();
		$objCartx->AddProduct(Product::LoadByCode('7up'));
		$objCartx->AddProduct(Product::LoadByCode('7up'));
		$objCartx->AddProduct(Product::LoadByCode('Кока-кола'));
		$objCartx->save();

		unset($objCartx);

		$objCart = Cart::LoadLastCartInProgress(1);
		$this->assertInstanceOf('cart',$objCart);
		$this->assertEquals('WO-30000',$objCart->id_str);


	}




	public function testRecalculateInventoryOnCartItems() {

		$objCart = Cart::LoadByIdStr('WO-30000');

		$arrCurrentNumbers=array();

		foreach ($objCart->cartItems as $item)
			$arrCurrentNumbers[] = $item->product->inventory_avail;

		//This is before recalculating happens
		//$this->assertEquals(74, $arrCurrentNumbers[0]); //7up should have 74
		//$this->assertEquals(444, $arrCurrentNumbers[1]); //Russian Coke should have 444

		$objCart->cart_type = CartType::order;
		$objCart->status = OrderStatus::AwaitingProcessing;
		$objCart->save();
		$objCart->RecalculateInventoryOnCartItems();
		$objCart->refresh();
		$arrNewNumbers=array();

		//This is AFTER recalculating happens
		foreach ($objCart->cartItems as $item)
			$arrNewNumbers[] = $item->product->inventory_avail;

		$this->assertEquals(72, $arrNewNumbers[0]); //7up should have 74
		$this->assertEquals(443, $arrNewNumbers[1]); //Russian Coke should have 444


	}


	public function testUpdateCartCustomer() {

	//Testing logging in as customer and setting cart to current customer

		//Lets create another cart in progress
		$objCartx = new Cart;
		$objCartx->customer_id=1;
		$objCartx->save();
		$objCartx->AddProduct(Product::LoadByCode('NonPowerBar'));
		$objCartx->AddProduct(Product::LoadByCode('NonPowerBar'));
		$objCartx->AddProduct(Product::LoadByCode('SPTURKEY'));
		$objCartx->save();

		//Now let's use a customer record and log in as them
		$objCustomer = Customer::model()->findByPk(1);
		$strPassword = _xls_decrypt($objCustomer->password);

		Yii::app()->user->logout();

		//Perform login procedure
		$identity=new UserIdentity($objCustomer->email,$strPassword);
		$identity->authenticate();
		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
		if($identity->errorCode==UserIdentity::ERROR_NONE)
		{
			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			$duration=3600*24*30; // 30 days
			Yii::app()->user->login($identity,$duration);
			Yii::app()->shoppingcart->loginMerge();
		}

		//Load a cart which should load our previous cart in progress automatically
		$objCart = Yii::app()->shoppingcart;

		$this->assertEquals(1,$objCart->customer_id);
		Yii::app()->user->logout();
		Yii::app()->user->clearStates();


		//Let's start a new cart as if we were a new visitor
		$objCart = Yii::app()->shoppingcart;
		$objCart->addProduct(Product::LoadByCode('SWGC'));


		//Now let's use a customer record and log in as them
		$objCustomer = Customer::model()->findByPk(2);
		$strPassword = _xls_decrypt($objCustomer->password);
		$identity=new UserIdentity($objCustomer->email,$strPassword);
		$identity->authenticate();
		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
		if($identity->errorCode==UserIdentity::ERROR_NONE)
		{
			$duration=3600*24*30; // 30 days
			Yii::app()->user->login($identity,$duration);
			Yii::app()->shoppingcart->assignCustomer($objCustomer);
			Yii::app()->shoppingcart->loginMerge();
		}
		$this->assertEquals(2,Yii::app()->user->id);

		//And since we've logged in with a cart in progress, this should now show up
		$this->assertEquals(2,$objCart->customer_id);

		Yii::app()->user->logout();
		Yii::app()->user->clearStates();


	}

	public function testUpdateCountAndSubtotal() {

		$objCart = Cart::InitializeCart();
		$objCart->customer_id=1;
		$objCart->save();
		$objCart->AddProduct(Product::LoadByCode('7Up'));
		$objCart->AddProduct(Product::LoadByCode('7Up'));
		$objCart->AddProduct(Product::LoadByCode('NonPowerBar'));
		$objCart->AddProduct(Product::LoadByCode('SPTURKEY'));
		$objCart->save();

		$objCart->subtotal = 0;
		$objCart->item_count = 0;
		$objCart->save();

		$this->assertEquals(0,$objCart->subtotal);
		$this->assertEquals(0,$objCart->item_count);
		$objCart->UpdateCountAndSubtotal();


		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(39.10,$objCart->subtotal);
		else
			$this->assertEquals(13.36,$objCart->subtotal);
		$this->assertEquals(3,$objCart->item_count);

	}


	public function testUpdateDiscountExpiry() {

		$objCart = Cart::LoadByIdStr('WO-30001');
		$objCart->cart_type = CartType::cart;
		$objCart->status = null;
		$objCart->save();

		foreach ($objCart->cartItems as $item)
		{
			$item->discount = 0.25;
			$item->sell_discount = $item->sell_base-0.25;
			$item->sell_total = $item->sell_discount*$item->qty;
			$item->save();
		}
		$objCart->UpdateCart(); //total it out

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(76.77,$objCart->total);
		else
			$this->assertEquals(26.58,$objCart->total);

		//From earlier tests, our due time is already old 2012-05-05 00:00:00
		$objCart->UpdateDiscountExpiry();
		$objCart->UpdateCart();

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(78.77,$objCart->total);
		else
			$this->assertEquals(28.68,$objCart->total);  //discount removed






	}
	public function testUpdateItemQuantity() {


		$objCart = Cart::LoadByIdStr('WO-30001');

		$objItem = $objCart->cartItems[0];
		$objCart->UpdateItemQuantity($objItem,10);
		$objCart->save();

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
		{
			$this->assertEquals(143,$objItem->sell_total);
			$this->assertEquals(164.57,$objCart->total);
		} else {
			$this->assertEquals(16.90,$objItem->sell_total);
			$this->assertEquals(39.36,$objCart->total);
		}

	}

	public function testUpdateMissingProducts() {
		//Test for a product that is no longer available

		$objProduct = Product::model()->findByPk(17);
		$objProduct->web=0;
		$objProduct->save(); //make this item no longer available

		$objCart = Cart::LoadByIdStr('WO-30001');

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(164.57,$objCart->total);
		else
			$this->assertEquals(39.36,$objCart->total);

		$objCart->UpdateMissingProducts();


		foreach($objCart->cartItems as $item)
			$this->assertNotEquals('13225',$item->code);


		//New total after the item was removed
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(162.17,$objCart->total);
		else
			$this->assertEquals(36.96,$objCart->total);


		//Now we put the product back
		$objProduct = Product::model()->findByPk(17);
		$objProduct->web=1;
		$objProduct->save(); //make this item no longer available
		$objCart->AddProduct(Product::LoadByCode('13225'),4);

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(164.57,$objCart->total);
		else
			$this->assertEquals(39.36,$objCart->total);


	}
	public function testUpdatePromoCode() {


		$objCart = Cart::LoadByIdStr('WO-30001');

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(164.57,$objCart->total);
		else
			$this->assertEquals(39.36,$objCart->total);

		$objCart->fk_promo_id=1;
		$objCart->save();

		$retVal = $objCart->UpdatePromoCode();

		$this->assertTrue($retVal);
		$objCart->save();
		$objCart->UpdateCart();

		$objCart = Cart::LoadByIdStr('WO-30001');
		$item = $objCart->cartItems[0];

		//Make sure 50% discount has been applied
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(7.15,$item->discount);
		else
			$this->assertEquals(0.845,$item->discount);

		//Total has been updated
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(93.07,$objCart->total);
		else
			$this->assertEquals(30.46,$objCart->total);

		//Remove promo code
		$objCart->fk_promo_id=null;
		$objCart->ResetDiscounts();
		$objCart->UpdateCart();


		//Total has been updated back to original total
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(164.57,$objCart->total);
		else
			$this->assertEquals(39.36,$objCart->total);


	}




}


