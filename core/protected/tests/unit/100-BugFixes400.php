<?php

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFixTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();

	}




	//Bug fix tests

	/**
	 * Set Tax Inclusive
	 * @group taxin
	 */
	public function testWS443()
	{

		//To make this easy, let's turn off all shipping except two
		Modules::model()->updateAll(array('active'=>0),'category="shipping"');
		Modules::model()->updateAll(array('active'=>1),'module="freeshipping"');
		Modules::model()->updateAll(array('active'=>1),'module="fedex"');

		//Remove restrictions on free shipping so it works
		$objModule = Modules::LoadByName('freeshipping');
		$config = $objModule->GetConfigValues();

		$config['promocode']="";
		$objModule->SaveConfigValues($config);
		$objModule->active = 1;
		$objModule->save();

		//Reset any restrictions
		$objP = PromoCode::LoadByShipping('freeshipping');
		$objP->code = ':freeshipping';
		$objP->lscodes = null;
		$objP->threshold = 0;
		$objP->valid_from = null;
		$objP->valid_until = null;
		$objP->save();

		//4.99 5.25 tax out, tax in
		$obj = Product::LoadByCode("SPTURKEY");
		echo $obj->PriceValue;
		$this->assertEquals(5.25,$obj->PriceValue);

		$cart = Yii::app()->shoppingcart;
		$cart->addProduct($obj,6);
		$this->assertEquals(5.25,$obj->PriceValue);

		//Log in as a customer
		//Now let's use a customer record and log in as them
		$objCustomer = Customer::model()->findByPk(3);
		$strPassword = _xls_decrypt($objCustomer->password);
		$identity=new UserIdentity($objCustomer->email,$strPassword);
		$identity->authenticate();
		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
		if($identity->errorCode==UserIdentity::ERROR_NONE)
			Yii::app()->user->login($identity,3600*24*30);

		$cartController = new CartController('cart');

		$form = $cartController->beginWidget('CActiveForm', array(
			'id'=>'checkout',
			'enableClientValidation'=>false,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		));

		ob_clean();
		ob_start();
		$_POST['CheckoutForm']['intShippingAddress'] = 5;

		$_POST['CheckoutForm']['shippingLabel'] = '';
		$_POST['CheckoutForm']['shippingFirstName'] = '';
		$_POST['CheckoutForm']['shippingLastName'] = '';
		$_POST['CheckoutForm']['shippingAddress1'] = '';
		$_POST['CheckoutForm']['shippingAddress2'] = '';
		$_POST['CheckoutForm']['shippingCity'] = '';
		$_POST['CheckoutForm']['shippingCountry'] = 224;
		$_POST['CheckoutForm']['shippingState'] = '';
		$_POST['CheckoutForm']['shippingPostal'] = '';
		$_POST['CheckoutForm']['shippingResidential'] = 1;
		$_POST['CheckoutForm']['billingSameAsShipping'] = 1;
		$_POST['CheckoutForm']['billingLabel'] = '';
		$_POST['CheckoutForm']['billingAddress1'] = '1409 Mullins Dr';
		$_POST['CheckoutForm']['billingAddress2'] = '';
		$_POST['CheckoutForm']['billingCity'] = 'Plano';
		$_POST['CheckoutForm']['billingCountry'] = 224;
		$_POST['CheckoutForm']['billingState'] = 46;
		$_POST['CheckoutForm']['billingPostal'] = '75025';
		$_POST['CheckoutForm']['billingResidential'] = 1;
		$_POST['CheckoutForm']['promoCode'] = '';
		$_POST['CheckoutForm']['shippingProvider'] = 76;
		$_POST['CheckoutForm']['shippingPriority'] = 0;
		$_POST['CheckoutForm']['paymentProvider'] = 64;
		$_POST['CheckoutForm']['cardType'] = 'MasterCard';
		$_POST['CheckoutForm']['cardNumber'] = '';
		$_POST['CheckoutForm']['cardCVV'] = '';
		$_POST['CheckoutForm']['cardExpiryMonth'] = '';
		$_POST['CheckoutForm']['cardExpiryYear'] = '';
		$_POST['CheckoutForm']['cardNameOnCard'] = '';
		$_POST['CheckoutForm']['orderNotes'] = '';
		$_POST['CheckoutForm']['acceptTerms'] = 1;
		$cartController->actionAjaxCalculateShipping();
		$retVal = ob_get_contents();
		ob_end_clean();
		$retVal = json_decode($retVal);

		$this->assertContains('$5.25',$retVal->cartitems->{95});


		//We switch to a different address that does not include tax
		ob_start();
		$_POST['CheckoutForm']['intShippingAddress'] = 6;
		$cartController->actionAjaxCalculateShipping();
		$retVal = ob_get_contents();
		ob_end_clean();
		$retVal = json_decode($retVal);

		$this->assertContains('$4.99',$retVal->cartitems->{95});

	}


	public function testWS464()
	{

		$cart = Yii::app()->shoppingcart;
		$cart->clearCart();
		$obj = Product::LoadByCode("SPTURKEY");
		$cart->addProduct($obj,1);

		$CheckoutForm = new BaseCheckoutForm();

		$arr = $CheckoutForm->getStates('shipping');
		foreach($arr as $key=>$val)
			$this->assertGreaterThan(1,$key);





		//Reset destination table
		_dbx("truncate table xlsws_destination");
		_dbx("INSERT INTO `xlsws_destination` (`id`, `country`, `state`, `zipcode1`, `zipcode2`, `taxcode`, `label`, `base_charge`, `ship_free`, `ship_rate`, `modified`)
VALUES
	(1, 224, 56, '', '', 104, NULL, NULL, NULL, NULL, '2013-06-03 10:24:59'),
	(2, NULL, NULL, '', '', 0, NULL, NULL, NULL, NULL, '2013-06-03 10:25:06');
");


		//Test destination directly first
		$CheckoutForm->shippingCountry=224;
		$CheckoutForm->shippingState=56;
		$CheckoutForm->shippingPostal='75025';

		$objDestination = Destination::LoadMatching($CheckoutForm->shippingCountry, $CheckoutForm->shippingState, $CheckoutForm->shippingPostal);
		$this->assertEquals(104,$objDestination->taxcode);


		$objModule = Modules::LoadByName('destinationshipping');
		$objModule->active=1;
		$objModule->configuration = 'a:5:{s:5:"label";s:20:"Destination Shipping";s:3:"per";s:4:"item";s:13:"offerservices";s:16:"what destination";s:15:"restrictcountry";s:4:"null";s:7:"product";s:8:"SHIPPING";}';
		$objModule->save();

		//Log in as a customer
		//Now let's use a customer record and log in as them
		$objCustomer = Customer::model()->findByPk(3);
		$strPassword = _xls_decrypt($objCustomer->password);
		$identity=new UserIdentity($objCustomer->email,$strPassword);
		$identity->authenticate();
		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
		if($identity->errorCode==UserIdentity::ERROR_NONE)
			Yii::app()->user->login($identity,3600*24*30);

		$cartController = new CartController('cart');

		$form = $cartController->beginWidget('CActiveForm', array(
			'id'=>'checkout',
			'enableClientValidation'=>false,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		));

		ob_clean();
		ob_start();
		$_POST['CheckoutForm']['intShippingAddress'] = 5;

		$_POST['CheckoutForm']['shippingLabel'] = '';
		$_POST['CheckoutForm']['shippingFirstName'] = '';
		$_POST['CheckoutForm']['shippingLastName'] = '';
		$_POST['CheckoutForm']['shippingAddress1'] = '';
		$_POST['CheckoutForm']['shippingAddress2'] = '';
		$_POST['CheckoutForm']['shippingCity'] = '';
		$_POST['CheckoutForm']['shippingCountry'] = 224;
		$_POST['CheckoutForm']['shippingState'] = '';
		$_POST['CheckoutForm']['shippingPostal'] = '';
		$_POST['CheckoutForm']['shippingResidential'] = 1;
		$_POST['CheckoutForm']['billingSameAsShipping'] = 1;
		$_POST['CheckoutForm']['billingLabel'] = '';
		$_POST['CheckoutForm']['billingAddress1'] = '1409 Mullins Dr';
		$_POST['CheckoutForm']['billingAddress2'] = '';
		$_POST['CheckoutForm']['billingCity'] = 'Plano';
		$_POST['CheckoutForm']['billingCountry'] = 224;
		$_POST['CheckoutForm']['billingState'] = 56;
		$_POST['CheckoutForm']['billingPostal'] = '75025';
		$_POST['CheckoutForm']['billingResidential'] = 1;
		$_POST['CheckoutForm']['promoCode'] = '';
		$_POST['CheckoutForm']['shippingProvider'] = 76;
		$_POST['CheckoutForm']['shippingPriority'] = 0;
		$_POST['CheckoutForm']['paymentProvider'] = 64;
		$_POST['CheckoutForm']['cardType'] = 'MasterCard';
		$_POST['CheckoutForm']['cardNumber'] = '';
		$_POST['CheckoutForm']['cardCVV'] = '';
		$_POST['CheckoutForm']['cardExpiryMonth'] = '';
		$_POST['CheckoutForm']['cardExpiryYear'] = '';
		$_POST['CheckoutForm']['cardNameOnCard'] = '';
		$_POST['CheckoutForm']['orderNotes'] = '';
		$_POST['CheckoutForm']['acceptTerms'] = 1;
		$cartController->actionAjaxCalculateShipping();
		$retVal = ob_get_contents();
		ob_end_clean();
		$retVal = json_decode($retVal);

		//Our destination table doesn't have prices, so this should fail
		$this->assertNotContains("Destination",$retVal->provider);


		ob_clean();
		ob_start();

		//Put in prices in destination table
		_dbx("truncate table xlsws_destination");
		_dbx("INSERT INTO `xlsws_destination` (`id`, `country`, `state`, `zipcode1`, `zipcode2`, `taxcode`, `label`, `base_charge`, `ship_free`, `ship_rate`, `modified`)
VALUES
	(1, 224, 56, '', '', 104, NULL, 10,0,5, '2013-06-03 10:24:59'),
	(2, NULL, NULL, '', '', 0, NULL, 50,0,10, '2013-06-03 10:25:06');
");

		$cartController->actionAjaxCalculateShipping();
		$retVal = ob_get_contents();
		ob_end_clean();
		$retVal = json_decode($retVal);

		//Our destination table now has prices, so this should succeed
		$this->assertContains("Destination",$retVal->provider);
		$price = $retVal->prices->{124};
		$this->assertEquals('$15.00',$price[0]);


		//Put back what is there
		$objModule = Modules::LoadByName('destinationshipping');
		$objModule->active=0;
		$objModule->save();



		_dbx("truncate table xlsws_destination");
		_dbx("INSERT INTO `xlsws_destination` (`id`, `country`, `state`, `zipcode1`, `zipcode2`, `taxcode`, `label`, `base_charge`, `ship_free`, `ship_rate`, `modified`)
VALUES
	(16, 224, 56, '', '', 104, NULL, -5, 0, 5, '2012-09-19 11:04:40'),
	(21, NULL, NULL, '', '', 0, NULL, NULL, NULL, NULL, '2012-09-20 06:14:43'),
	(22, 39, 137, 'V5TA1A', 'V5Z 1E4', 104, NULL, 0, NULL, NULL, '2013-06-06 08:30:23');
");
	}


	public function testWS460()
	{


		//Reset destination table
		//US is texas only
		//Canada is whole country
		_dbx("truncate table xlsws_destination");
		_dbx("INSERT INTO `xlsws_destination` (`id`, `country`, `state`, `zipcode1`, `zipcode2`, `taxcode`, `label`, `base_charge`, `ship_free`, `ship_rate`, `modified`)
			VALUES
				(1, 224, 56, '', '', 104, NULL, NULL, NULL, NULL, '2013-06-03 10:24:59'),
				(2, 39, NULL, '', '', 0, NULL, NULL, NULL, NULL, '2013-06-03 10:25:06');
			");


		//Test US
		//Start by turning off destination restrictions
		_xls_set_conf('SHIP_RESTRICT_DESTINATION',0);

		$CheckoutForm = new BaseCheckoutForm();
		$arr = $CheckoutForm->getStates('shipping',224);
		$this->assertCount(54,$arr);

		//Turn on restrictions
		_xls_set_conf('SHIP_RESTRICT_DESTINATION',1);
		$arr = $CheckoutForm->getStates('shipping',224);
		$this->assertCount(1,$arr); //Should just get Texas here


		//Test CA
		//Start by turning off destination restrictions
		_xls_set_conf('SHIP_RESTRICT_DESTINATION',0);

		$CheckoutForm = new BaseCheckoutForm();
		$arr = $CheckoutForm->getStates('shipping',39);
		$this->assertCount(13,$arr);

		//Turn on restrictions
		_xls_set_conf('SHIP_RESTRICT_DESTINATION',1);
		$arr = $CheckoutForm->getStates('shipping',39);
		$this->assertCount(13,$arr); //Should just all of Canada Here


		_dbx("truncate table xlsws_destination");
		_dbx("INSERT INTO `xlsws_destination` (`id`, `country`, `state`, `zipcode1`, `zipcode2`, `taxcode`, `label`, `base_charge`, `ship_free`, `ship_rate`, `modified`)
			VALUES
				(16, 224, 56, '', '', 104, NULL, -5, 0, 5, '2012-09-19 11:04:40'),
				(21, NULL, NULL, '', '', 0, NULL, NULL, NULL, NULL, '2012-09-20 06:14:43'),
				(22, 39, 137, 'V5TA1A', 'V5Z 1E4', 104, NULL, 0, NULL, NULL, '2013-06-06 08:30:23');
			");
	}


	public function testWS433()
	{


		$c = new ProductController('product');
		$model = Product::LoadByCode('CASE24COKE');

		//Related products are ids 17, 16, and 27
		//27 Two dollar fee where web=0
		//17 Powerbar Pure & Simple Bar Cranberry/Oatmeal -3 inventory
		//16 Powerbar Pure & Simple Bar Roasted Peanut 9 inventory


		//In this scenario, we should get both powerbars even without of stock
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryAllowBackorders);
		$dataProvider = $model->related();
		$dpKeys = $dataProvider->Keys;
		$dpKeys = array_combine($dpKeys, $dpKeys);
		print_r($dpKeys);
		$this->assertArrayNotHasKey(27,$dpKeys);
		$this->assertArrayHasKey(17,$dpKeys);
		$this->assertArrayHasKey(16,$dpKeys);
		$this->assertEquals(2,$dataProvider->totalItemCount);


		//In this scenario, we should get both powerbars even without of stock
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryDisplayNotOrder);
		$dataProvider = $model->related();
		$dpKeys = $dataProvider->Keys;
		$dpKeys = array_combine($dpKeys, $dpKeys);
		print_r($dpKeys);
		$this->assertArrayNotHasKey(27,$dpKeys);
		$this->assertArrayHasKey(17,$dpKeys);
		$this->assertArrayHasKey(16,$dpKeys);
		$this->assertEquals(2,$dataProvider->totalItemCount);

		//In this scenario, we should get only the in stock powerbar
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryMakeDisappear);
		$dataProvider = $model->related();
		$dpKeys = $dataProvider->Keys;
		$dpKeys = array_combine($dpKeys, $dpKeys);
		print_r($dpKeys);
		$this->assertArrayNotHasKey(27,$dpKeys);
		$this->assertArrayNotHasKey(17,$dpKeys);
		$this->assertArrayHasKey(16,$dpKeys);
		$this->assertEquals(1,$dataProvider->totalItemCount);
	}

	public function testWS471()
	{

		//Remove any categories now
		ProductCategoryAssn::model()->deleteAllByAttributes(array('product_id'=>109));

		$soapadd = <<<EOD
<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/save_product"><ns1:save_product><passkey>webstore</passkey><intRowid>109</intRowid><strCode>Yoohoo</strCode><strName>Yoohoo Chocolate Drink</strName><blbImage></blbImage><strClassName>%class</strClassName><blnCurrent>1</blnCurrent><strDescription></strDescription><strDescriptionShort xsi:nil="1"></strDescriptionShort><strFamily xsi:nil="1"></strFamily><blnGiftCard>0</blnGiftCard><blnInventoried>1</blnInventoried><fltInventory>0.000000</fltInventory><fltInventoryTotal>0.000000</fltInventoryTotal><blnMasterModel>1</blnMasterModel><intMasterId>0</intMasterId><strProductColor xsi:nil="1"></strProductColor><strProductSize xsi:nil="1"></strProductSize><fltProductHeight>0.000000</fltProductHeight><fltProductLength>0.000000</fltProductLength><fltProductWidth>0.000000</fltProductWidth><fltProductWeight>0.000000</fltProductWeight><intTaxStatusId>0</intTaxStatusId><fltSell>2.990000</fltSell><fltSellTaxInclusive>3.150000</fltSellTaxInclusive><fltSellWeb>0.000000</fltSellWeb><strUpc xsi:nil="1"></strUpc><blnOnWeb>1</blnOnWeb><strWebKeyword1 xsi:nil="1"></strWebKeyword1><strWebKeyword2 xsi:nil="1"></strWebKeyword2><strWebKeyword3 xsi:nil="1"></strWebKeyword3><blnFeatured>0</blnFeatured><strCategoryPath>Beverages</strCategoryPath></ns1:save_product></SOAP-ENV:Body></SOAP-ENV:Envelope>
EOD;

	sendSoap('save_product',$soapadd);

		$objProduct = Product::model()->findByPk(109);
		$this->assertCount(1,$objProduct->xlswsCategories);
		foreach($objProduct->xlswsCategories as $item)
			print_r($item->id);

		$soapadd2 = <<<EOD
<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/save_product"><ns1:save_product><passkey>webstore</passkey><intRowid>109</intRowid><strCode>Yoohoo</strCode><strName>Yoohoo Chocolate Drink</strName><blbImage></blbImage><strClassName>%class</strClassName><blnCurrent>1</blnCurrent><strDescription></strDescription><strDescriptionShort xsi:nil="1"></strDescriptionShort><strFamily xsi:nil="1"></strFamily><blnGiftCard>0</blnGiftCard><blnInventoried>1</blnInventoried><fltInventory>0.000000</fltInventory><fltInventoryTotal>0.000000</fltInventoryTotal><blnMasterModel>1</blnMasterModel><intMasterId>0</intMasterId><strProductColor xsi:nil="1"></strProductColor><strProductSize xsi:nil="1"></strProductSize><fltProductHeight>0.000000</fltProductHeight><fltProductLength>0.000000</fltProductLength><fltProductWidth>0.000000</fltProductWidth><fltProductWeight>0.000000</fltProductWeight><intTaxStatusId>0</intTaxStatusId><fltSell>2.990000</fltSell><fltSellTaxInclusive>3.150000</fltSellTaxInclusive><fltSellWeb>0.000000</fltSellWeb><strUpc xsi:nil="1"></strUpc><blnOnWeb>1</blnOnWeb><strWebKeyword1 xsi:nil="1"></strWebKeyword1><strWebKeyword2 xsi:nil="1"></strWebKeyword2><strWebKeyword3 xsi:nil="1"></strWebKeyword3><blnFeatured>0</blnFeatured><strCategoryPath>Snacks</strCategoryPath></ns1:save_product></SOAP-ENV:Body></SOAP-ENV:Envelope>
EOD;



		sendSoap('save_product',$soapadd2);

		$objProduct = Product::model()->findByPk(109);
		$this->assertCount(1,$objProduct->xlswsCategories);
		foreach($objProduct->xlswsCategories as $item)
			print_r($item->id);

	}


	//Sizecolor matrix behavior
	//if color filtering is off, always show all colors
	//if color filtering is on, and inventory allows backorders, show all colors
	//if color filtering is on, and inventory is not allowing backorders, filter by colors
	//Size color matrix should show all colors when color filtering is on and allow backorders is on
	public function testWS424()
	{

		//scenario 1
		//First confirm color filtering off shows everything
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryAllowBackorders);
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();
		$objProduct = Product::LoadByCode('Sunkist');
		$this->assertCount(3,$objProduct->sizes);
		$this->assertCount(2,$objProduct->getColors('Medium'));
		$colors = $objProduct->getColors('Medium');


		//scenario 2
		//if color filtering is on, and inventory allows backorders, show all colors
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryAllowBackorders);
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();
		$objProduct = Product::LoadByCode('Sunkist');
		$this->assertCount(3,$objProduct->sizes);
		$this->assertCount(2,$objProduct->getColors('Medium'));

		//scenario 3
		//if color filtering is on, and inventory allows display without ordering, show all colors
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryDisplayNotOrder);
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();
		$objProduct = Product::LoadByCode('Sunkist');
		$this->assertCount(3,$objProduct->sizes);
		$this->assertCount(2,$objProduct->getColors('Medium'));

		//scenario 4
		//if color filtering is on, and inventory is not allowing backorders, filter by colors
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryMakeDisappear);
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();
		$objProduct = Product::LoadByCode('Sunkist');
		$this->assertCount(1,$objProduct->sizes); //should only be medium
		$this->assertCount(1,$objProduct->getColors('Medium')); //should only be diet

		//scenario 5
		//if color filtering is off, and inventory is allows back orders, show all colors
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryDisplayNotOrder);
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();
		$objProduct = Product::LoadByCode('Sunkist');
		$this->assertCount(3,$objProduct->sizes); //should only be medium
		$this->assertCount(2,$objProduct->getColors('Medium')); //should only be diet

		//scenario 6
		//if color filtering is off, and inventory is allows back orders, show all colors
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryAllowBackorders);
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();
		$objProduct = Product::LoadByCode('Sunkist');
		$this->assertCount(3,$objProduct->sizes); //should only be medium
		$this->assertCount(2,$objProduct->getColors('Medium')); //should only be diet

		//scenario 7
		//if color filtering is on, and inventory is allows back orders, show all colors
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryAllowBackorders);
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();
		$objProduct = Product::LoadByCode('Sunkist');
		$this->assertCount(3,$objProduct->sizes); //should only be medium
		$this->assertCount(2,$objProduct->getColors('Medium')); //should only be diet

		//So all that was hitting the model directly, now test the controller
		$c = new ProductController('product');
		$model = Product::LoadByCode('CASE24COKE');

	}

	//Redirect to URL not working in custom page
	public function testWS492()
	{

		$obj = CustomPage::LoadByKey("new");
		$savePage = $obj->page;

		$obj->page = "<p>Page coming soon...</p>";
		$obj->save();
		$this->assertEquals("<p>Page coming soon...</p>",$obj->page);
		$this->assertEquals("http://www.copper.site/new-products",$obj->Link);

		$obj->page = "<p>
	http://www.cnn.com
</p>";
		$obj->save();
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();
		$this->assertEquals("http://www.cnn.com",$obj->Link);

		$obj = CustomPage::LoadByKey("new");
		$obj->page = "<p>Page coming soon...</p>";
		$obj->save();

	}


	public function testWS503()
	{

		//Sequence of tests
		//As logged out user, add item to cart
		//tax out mode
		//


		//set up taxes for test
		$sql1 = "INSERT INTO `xlsws_tax` (`id`, `lsid`, `tax`, `max_tax`, `compounded`)
VALUES
(1, 1, 'VAT', 0, 0),
	(2, 2, '', 0, 0),
	(3, 3, '', 0, 0),
	(4, 4, '', 0, 0),
	(5, 5, '', 0, 0);
";

		$sql2 = "INSERT INTO `xlsws_tax_code` (`id`, `lsid`, `code`, `list_order`, `tax1_rate`, `tax2_rate`, `tax3_rate`, `tax4_rate`, `tax5_rate`)
VALUES
	(1, 0, 'NOTAX', 3, 0, 0, 0, 0, 0),
	(2, 104, 'UK', 1, 20, 0, 0, 0, 0);
";

		$sql3 = "INSERT INTO `xlsws_tax_status` (`id`, `lsid`, `status`, `tax1_status`, `tax2_status`, `tax3_status`, `tax4_status`, `tax5_status`)
VALUES
	(1, 0, 'Default', 0, 0, 0, 0, 0),
	(2, 38, 'NoTax', 1, 1, 1, 1, 1);
";

		//Put taxes back like we found them
		$sql1 = "INSERT INTO `xlsws_tax` (`id`, `lsid`, `tax`, `max_tax`, `compounded`)
VALUES
	(1, 1, 'TX', 0, 0),
	(2, 2, '', 0, 0),
	(3, 3, '', 0, 0),
	(4, 4, '', 0, 0),
	(5, 5, '', 0, 0);
";
		$sql2 = "INSERT INTO `xlsws_tax_code` (`id`, `lsid`, `code`, `list_order`, `tax1_rate`, `tax2_rate`, `tax3_rate`, `tax4_rate`, `tax5_rate`)
VALUES
	(1, 104, 'Texas', 2, 8.25, 0, 0, 0, 0),
	(2, 146, 'Ut', 1, 5.25, 0, 0, 0, 0),
	(3, 0, 'NOTAX', 5, 0, 0, 0, 0, 0);
";

		$sql3 = "INSERT INTO `xlsws_tax_status` (`id`, `lsid`, `status`, `tax1_status`, `tax2_status`, `tax3_status`, `tax4_status`, `tax5_status`)
VALUES
	(1, 0, 'Default', 0, 0, 0, 0, 0),
	(2, 38, 'NoTax', 1, 1, 1, 1, 1);
";



	}

}


