<?php
/**
 * Unit tests for all our helper functions
 */

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";


class PaymentTest extends PHPUnit_Framework_TestCase
{
	public $checkoutForm;
	public $objCart;
	public $POST;
	public static $fakestatic;

	public static function setUpBeforeClass()
	{
		self::$fakestatic = self::makefake();
	}


	protected static function makefake()
	{



		$API_Endpoint = "http://www.fakenamegenerator.com";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, "Safari WebKit");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$resp = curl_exec($ch);
		curl_close($ch);

		//print_r($resp);

		$resp = str_replace("\n","",$resp);
		$resp = str_replace("\r","",$resp);
		preg_match('/<div class=\"adr\">(.*?)<\/div>/',$resp,$matches);

		$add = explode("<br/>",$matches[1]);
		$zip = substr(trim($add[1]),-5);
		$address1 = trim($add[0]);
		$cs = trim(str_replace($zip,"",trim($add[1])));

		$csa = explode(",",$cs);
		$city = trim($csa[0]);
		$state = trim($csa[1]);


		preg_match('/<div class=\"address\">(.*?)<\/h3>/',$resp,$matches);

		$fullname = trim(str_replace("<h3>","",trim($matches[1])));
		$fullnamea = explode(" ",$fullname);
		if(count($fullnamea)==3)
		{
			$fn = $fullnamea[0]." ".$fullnamea[1];
			$ln = $fullnamea[2];
		} else {
			$fn = $fullnamea[0];
			$ln = $fullnamea[1];
		}

		preg_match('/MasterCard:(.*?)<br\/>/',$resp,$matches);
		if (isset($matches[1]))
			$cc = $matches[1];
		else
		{
			preg_match('/Visa:(.*?)<br\/>/',$resp,$matches);
			$cc = $matches[1];
		}
		$cc=_xls_number_only($cc);


		preg_match('/Expires:(.*?)<br\/>/',$resp,$matches);
		$exp = $matches[1];
		$exp = str_replace('<br>', '', $exp);
		$exp = str_replace('</br>', '', $exp);
		$exp = str_replace('</li>', '', $exp);
		$exp = preg_replace('/[^0-9\/]/', '', $exp);
		$expa = explode("/",$exp);

		preg_match('/CVC2(.*?)<br\/>/',$resp,$matches);
		if (isset($matches[1]))
		{
			$CVC2 = $matches[1];
			$CVC2 = preg_replace('/[^0-9]/', '', $CVC2);
		}else {
			preg_match('/CVV2(.*?)<br\/>/',$resp,$matches);
			if (isset($matches[1]))
			{
				$CVC2 = $matches[1];
				$CVC2 = preg_replace('/[^0-9]/', '', $CVC2);
			}
		}

		preg_match('/Phone:(.*?)<br\/>/',$resp,$matches);
		$phone = $matches[1];
		$phone = preg_replace('/[^0-9]/', '', $phone);

		preg_match('/<li class=\"email\"><span class=\"value\">(.*?)<\/span>/',$resp,$matches);
		$email = $matches[1];



		$fake = new CheckoutForm();
		$fake->contactFirstName = $fn;
		$fake->contactLastName = $ln;
		$fake->billingAddress1 = $address1;
		$fake->billingCity = $city;
		$fake->billingState = $state;
		$fake->billingPostal = $zip;
		$fake->billingCountry = 'US';
		$fake->contactPhone = $phone;
		$fake->contactEmail = $email;
		$fake->contactEmail_repeat = $email;



		$fake->cardNumber = $cc;
		$fake->cardExpiryMonth = sprintf('%02d', $expa[0]);
		$fake->cardExpiryYear = $expa[1];
		$fake->cardCVV = $CVC2;

		return $fake;


	}

	public function setUp()
	{
		$this->fake = self::$fakestatic;

		$this->checkoutForm = new CheckoutForm();

		parent::setUp();
		Yii::app()->controller = new CartController("default");

		$this->POST['CheckoutForm']['shippingFirstName'] = $this->POST['CheckoutForm']['contactFirstName'] = $this->fake->contactFirstName;
		$this->POST['CheckoutForm']['shippingLastName'] = $this->POST['CheckoutForm']['contactLastName'] = $this->fake->contactLastName ;
		$this->POST['CheckoutForm']['contactEmail'] = $this->fake->contactEmail;
		$this->POST['CheckoutForm']['contactEmail_repeat'] = $this->fake->contactEmail;
		$this->POST['CheckoutForm']['contactPhone'] = $this->fake->contactPhone;
		$this->POST['CheckoutForm']['billingAddress1'] = $this->fake->billingAddress1;
		$this->POST['CheckoutForm']['billingCity'] = $this->fake->billingCity;
		$this->POST['CheckoutForm']['billingState'] = $this->fake->billingState;
		$this->POST['CheckoutForm']['billingPostal'] = $this->fake->billingPostal;
		$this->POST['CheckoutForm']['billingCountry'] = $this->fake->billingCountry;
		$this->POST['CheckoutForm']['billingLabel'] = "Home";

		$this->POST['CheckoutForm']['shippingFirstName'] = $this->fake->contactFirstName;
		$this->POST['CheckoutForm']['shippingLastName'] = $this->fake->contactLastName;
		$this->POST['CheckoutForm']['shippingAddress1'] = $this->fake->billingAddress1;
		$this->POST['CheckoutForm']['shippingCity'] = $this->fake->billingCity;
		$this->POST['CheckoutForm']['shippingState'] = $this->fake->billingState;
		$this->POST['CheckoutForm']['shippingPostal'] = $this->fake->billingPostal;
		$this->POST['CheckoutForm']['shippingCountry'] = $this->fake->billingCountry;

		$this->POST['CheckoutForm']['shippingLabel'] = "Work";
		$this->POST['CheckoutForm']['paymentProvider'] = 49;

		$this->POST['CheckoutForm']['cardNumber'] = $this->fake->cardNumber;
		$this->POST['CheckoutForm']['cardExpiryMonth'] = $this->fake->cardExpiryMonth;
		$this->POST['CheckoutForm']['cardExpiryYear'] = $this->fake->cardExpiryYear;
		$this->POST['CheckoutForm']['cardNameOnCard'] = $this->POST['CheckoutForm']['contactFirstName'] . " ".$this->POST['CheckoutForm']['contactLastName'];

		$this->checkoutForm->attributes = $this->POST['CheckoutForm'];
		//print_r($this->checkoutForm);
		$this->objCart = Yii::app()->shoppingcart;


		$this->objCart->addProduct(Product::LoadByCode('GG206'));
		$this->objCart->id_str =  "WO-".date("His");

		$objS = new CartShipping;
		$objS->shipping_method = 'SHIPPING';
		$objS->shipping_module = 'ups';
		$objS->shipping_data = 'UPS 3 Day Select';
		$objS->shipping_cost = 16.00;
		$objS->shipping_sell = 19.00;
		$objS->save();

		$this->objCart->shipping_id=$objS->id;

		$this->objCart->Recalculate();


		_xls_set_conf('DEFAULT_COUNTRY',224); //US

		$objModules = Modules::LoadByName('paypal');
		$objModules->configuration = "a:4:{s:5:\"label\";s:6:\"PayPal\";s:5:\"login\";s:36:\"kris.w_1331482444_biz@eightounce.com\";s:4:\"live\";s:4:\"test\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}";
		$objModules->save();

		$objModule = Modules::LoadByName('beanstreamaim');
		$objModule->configuration = 'a:4:{s:5:"label";s:31:"Beanstream Advanced Integration";s:5:"login";s:9:"263770000";s:15:"restrictcountry";s:4:"null";s:17:"ls_payment_method";s:11:"Credit Card";}';
		$objModule->save();

	$objModule = Modules::LoadByName('ewayaim');
		$objModule->configuration = 'a:4:{s:5:"label";s:4:"eWay";s:5:"login";s:8:"87654321";s:4:"live";s:4:"test";s:17:"ls_payment_method";s:15:"Web Credit Card";}';
		$objModule->save();

	$objModule = Modules::LoadByName('moneris');
		$objModule->configuration = 'a:9:{s:5:"label";s:7:"Moneris";s:8:"store_id";s:7:"moneris";s:9:"api_token";s:6:"hurgle";s:4:"live";s:4:"test";s:3:"ccv";s:1:"1";s:3:"avs";s:1:"1";s:11:"specialcode";N;s:15:"restrictcountry";s:4:"null";s:17:"ls_payment_method";s:15:"Web Credit Card";}';
//		$objModule->configuration = 'a:9:{s:5:"label";s:7:"Moneris";s:8:"store_id";s:6:"store5";s:9:"api_token";s:6:"yesguy";s:4:"live";s:4:"live";s:3:"ccv";s:1:"1";s:3:"avs";s:1:"1";s:11:"specialcode";N;s:15:"restrictcountry";s:4:"null";s:17:"ls_payment_method";s:15:"Web Credit Card";}';
		$objModule->save();

	$objModule = Modules::LoadByName('merchantware');
		$objModule->configuration = 'a:5:{s:5:"label";s:12:"Merchantware";s:4:"name";s:6:"Xsilva";s:7:"site_id";s:8:"6VBYB5BC";s:9:"trans_key";s:29:"DW8YD-9C77X-AZP81-AN9M8-AXGX3";s:17:"ls_payment_method";s:11:"Credit Card";}';
		$objModule->save();

	$objModule = Modules::LoadByName('axia');
		$objModule->configuration = 'a:6:{s:5:"label";s:36:"Credit card (Visa, Mastercard, Amex)";s:10:"source_key";s:32:"tBLbnzONj82GH1kWcBCqfu7b6DZoksqT";s:14:"source_key_pin";s:0:"";s:4:"live";s:4:"test";s:15:"restrictcountry";s:4:"null";s:17:"ls_payment_method";s:11:"Credit Card";}';
		$objModule->save();

	}
	protected function tearDown()
	{
		$this->objCart->clearCart();
	}

	public function testForm()
	{

		$this->checkoutForm->setScenario('formSubmitGuest');
		$arrReturn = $this->checkoutForm->validate();
		print_r($this->checkoutForm->getErrors());
		$this->assertTrue($arrReturn);


	}

	public function testpurchaseorder()
	{

		$objPaymentModule = Modules::LoadByName('purchaseorder');

		if(isset(Yii::app()->getComponent($objPaymentModule->module)->subform))
		{
			$paymentSubform = Yii::app()->getComponent($objPaymentModule->module)->subform;
			$paymentSubformModel = new $paymentSubform;
			if (isset($this->POST['purchaseorderform']))
				$paymentSubformModel->attributes = $this->POST['purchaseorderform'];

			$paymentSubformModel->validate();
			$arrReturn = $paymentSubformModel->getErrors();
			$this->assertEquals('Purchase Order cannot be blank.',$arrReturn['po'][0]);

			$this->POST['purchaseorderform']['po'] = 49;
			$paymentSubformModel->attributes = $this->POST['purchaseorderform'];
			$paymentSubformModel->validate();

		}

		$arrReturn = Yii::app()->getComponent($objPaymentModule->module)->setCheckoutForm($this->checkoutForm)->setSubForm($paymentSubformModel)->run();

		$this->assertEquals('49',$arrReturn['result']);

	}

	public function testphoneorder()
	{
		//Should test that our form is being returned with our title element
		//this particular form doesn't have any subform processing

		if(isset(Yii::app()->getComponent('phoneorder')->subform))
		{
			$paymentSubform = Yii::app()->getComponent('phoneorder')->subform;
			$paymentSubformModel = new $paymentSubform;
			$form = new CForm($paymentSubformModel->Subform, $paymentSubformModel);

			$this->assertContains('your credit card details',$form->title);
		}

		$arrReturn = Yii::app()->getComponent('phoneorder')->setCheckoutForm($this->checkoutForm)->setSubForm($paymentSubformModel)->run();

		$this->assertEquals('Phone Order',$arrReturn['result']);

	}
	public function testcheque()
	{
		//Should test that our form is being returned with our title element
		//this particular form doesn't have any subform processing

		if(isset(Yii::app()->getComponent('cheque')->subform))
		{
			$paymentSubform = Yii::app()->getComponent('cheque')->subform;
			$paymentSubformModel = new $paymentSubform;
			$form = new CForm($paymentSubformModel->Subform, $paymentSubformModel);

			$this->assertContains('Please note your order will be pending',$form->title);
		}

		$arrReturn = Yii::app()->getComponent('cheque')->setCheckoutForm($this->checkoutForm)->setSubForm($paymentSubformModel)->run();

		$this->assertEquals('Check',$arrReturn['result']);

	}



	public function testAuthAIM()
	{
		//Should test that our form is being returned with our title element
		//this particular form doesn't have any subform processing

		$arrReturn = Yii::app()->getComponent('authorizedotnetaim')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		$this->assertEquals('1',$arrReturn['success']);
		$this->assertContains('TEST',$arrReturn['result']);

	}


	public function testAuthSIM()
	{


		$arrReturn = Yii::app()->getComponent('authorizedotnetsim')->setCheckoutForm($this->checkoutForm)->run();

		$this->assertContains('<input type="hidden" name="x_description" value="LightSpeed Web Store Order">',$arrReturn['jump_form']);
		$this->assertContains('<input type="hidden" name="x_state" value="'.$this->fake->billingState.'">',$arrReturn['jump_form']);

		$this->assertContains('<input type="hidden" name="x_phone" value="'.$this->fake->contactPhone.'">',$arrReturn['jump_form']);
		$this->assertContains('<input type="hidden" name="x_relay_url" value="http://www.copper.site/cart/payment/authorizedotnetsim">',$arrReturn['jump_form']);


	}

	public function testWorldpaySIM()
	{


		$arrReturn = Yii::app()->getComponent('worldpaysim')->setCheckoutForm($this->checkoutForm)->run();

		$this->assertContains('<input type="hidden" name="desc" value="LightSpeed Web Store Order">',$arrReturn['jump_form']);
		$this->assertContains('<input type="hidden" name="region" value="'.$this->fake->billingState.'">',$arrReturn['jump_form']);
		$this->assertContains('<input type="hidden" name="postcode" value="'.$this->fake->billingPostal.'">',$arrReturn['jump_form']);

		$this->assertContains('<input type="hidden" name="tel" value="'.$this->fake->contactPhone.'">',$arrReturn['jump_form']);
		$this->assertContains('<input type="hidden" name="MC_callback" value="http://www.copper.site/cart/payment/worldpaysim">',$arrReturn['jump_form']);


	}
	public function testBeanstreamSIM()
	{

		$arrReturn = Yii::app()->getComponent('beanstreamsim')->setCheckoutForm($this->checkoutForm)->run();

		$this->assertContains('<input type="hidden" name="ordPhoneNumber" value="'.$this->fake->contactPhone.'">',$arrReturn['jump_form']);
		$this->assertContains('<input type="hidden" name="approvedPage" value="http://www.copper.site/cart/payment/beanstreamsim">',$arrReturn['jump_form']);


	}


	/*
	 * For beanstream, we can't use our fake CC from our fake profile, we have to use specially provided card numbers
	 * from https://beanstreamsupport.pbworks.com/w/page/26445759/Test-Card-Numbers
	 */
	public function testBeanstreamAIM()
	{
		//Should test that our form is being returned with our title element
		//this particular form doesn't have any subform processing


		$this->checkoutForm->cardNumber = "4716-8523-7130-0741";



		$arrReturn = Yii::app()->getComponent('beanstreamaim')->setCheckoutForm($this->checkoutForm)->run();

		$this->assertEquals('DECLINE',$arrReturn['result']);


		$this->checkoutForm->cardNumber = "4030000010001234"; //beanstream has their own test cards that work
		$arrReturn = Yii::app()->getComponent('beanstreamaim')->setCheckoutForm($this->checkoutForm)->run();


		//Because we run these tests a lot and reuse the same WO numbers, these could get flagged in the same day
		if ($arrReturn['result'] != "Duplicate Transaction - This transaction has already been approved") {
			$this->assertEquals('1',$arrReturn['success']);
			$this->assertEquals('TEST',$arrReturn['result']); //Beanstream returns TEST for the auth code in test mode
		}
		//Put the number back
		$this->checkoutForm->cardNumber = $this->fake->cardNumber;

	}

	/**
	 * @group taxout
	 */
	public function testeWayAIM()
	{
		//Should test that our form is being returned with our title element
		//this particular form doesn't have any subform processing




		$this->checkoutForm->cardNumber = "4716-8523-7130-0741";


		$arrReturn = Yii::app()->getComponent('ewayaim')->setCheckoutForm($this->checkoutForm)->run();

		$this->assertFalse($arrReturn['success']);
		$this->assertContains('Invalid credit card provided',$arrReturn['result']);

		//Note the total price must end in .00 to return a success with this test account
		$this->checkoutForm->cardNumber = "4444-3333-2222-1111";
		$arrReturn = Yii::app()->getComponent('ewayaim')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		$this->assertEquals("1",$arrReturn['success']);
		$this->assertEquals('123456',$arrReturn['result']);
		$this->assertEquals('89.00',$arrReturn['amount_paid']);

		$this->checkoutForm->cardNumber = $this->fake->cardNumber;


	}
	/**
	 * @group taxin
	 */
	public function testeWayAIMtx()
	{
		//Should test that our form is being returned with our title element
		//this particular form doesn't have any subform processing




		$this->checkoutForm->cardNumber = "4716-8523-7130-0741";


		$arrReturn = Yii::app()->getComponent('ewayaim')->setCheckoutForm($this->checkoutForm)->run();

		$this->assertFalse($arrReturn['success']);
		$this->assertContains('Invalid credit card provided',$arrReturn['result']);

		//Note the total price must end in .00 to return a success with this test account
		$this->checkoutForm->cardNumber = "4444-3333-2222-1111";
		$arrReturn = Yii::app()->getComponent('ewayaim')->setCheckoutForm($this->checkoutForm)->run();

		$this->assertEquals("1",$arrReturn['success']);
		$this->assertEquals('123456',$arrReturn['result']);
		$this->assertEquals('89.00',$arrReturn['amount_paid']);
		$this->checkoutForm->cardNumber = $this->fake->cardNumber;

	}

	public function testMoneris()
	{
		//Should test that our form is being returned with our title element
		//this particular form doesn't have any subform processing


		$arrReturn = Yii::app()->getComponent('moneris')->setCheckoutForm($this->checkoutForm)->run();

		print_r($arrReturn);
		if ($arrReturn['result'] != "Error: The credit card processor is currently unreachable." &&
			$arrReturn['result'] != 'Request was not allowed at this time' &&
			$arrReturn['result'] != "Cancelled: Could not execute prepared statement: persistExtraInfo: Invalid column name 'country'.")
		{
			$this->assertEquals("1",$arrReturn['success']);
			$this->assertGreaterThan(1,$arrReturn['result']);

			//Run again which should generate duplicate ID
			$arrReturn = Yii::app()->getComponent('moneris')->setCheckoutForm($this->checkoutForm)->run();
			$this->assertEquals("The transaction was not sent to the host because of a duplicate order id",$arrReturn['result']);
		}


	}


	/**
	 * @group taxout
	 */
	public function testMW()
	{

		$this->objCart->total = 89.00;

		$s = $this->objCart->id_str;

		$this->objCart->id_str =  date("zis");

		echo "attempting cartid ".$this->objCart->id_str." for price ".$this->objCart->total;


		$arrReturn = Yii::app()->getComponent('merchantware')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		if ($arrReturn['result'] != 'DECLINED,DUPLICATE: decline')
			$this->assertEquals("1",$arrReturn['success']);

		$this->objCart->id_str = $s;

	}

/**
	 * @group taxout
	 */
	public function testAxia()
	{


		$this->checkoutForm->cardNumber = "4314444455788445";
		$arrReturn = Yii::app()->getComponent('axia')->setCheckoutForm($this->checkoutForm)->run();

		$this->assertFalse($arrReturn['success']);
		$this->assertContains('Invalid Card Number',$arrReturn['result']);

		//Note the total price must end in .00 to return a success with this test account
		$this->checkoutForm->cardNumber = $this->fake->cardNumber;
		$arrReturn = Yii::app()->getComponent('axia')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		$this->assertEquals("1",$arrReturn['success']);



	}


}

