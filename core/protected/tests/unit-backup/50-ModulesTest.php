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

class ModulesTest extends PHPUnit_Framework_TestCase
{

	public function testOutput() {

		foreach(CHtml::listData(Modules::model()->findAllByAttributes(array('active'=>1,'category'=>'shipping','category'=>'payment')),'id','module') as $key=>$val)
		{
			echo("$val")."\n";
			$retValue = Yii::app()->getModule($val)->Name;
			echo $retValue."\n";
			$retValue = Yii::app()->getModule($val)->AdminName;
			echo $retValue."\n";
			$retValue = Yii::app()->getModule($val)->DefaultName;
			echo $retValue."\n";
			echo "\n";
		}

	}


	public function testPaypalIPN()
	{
		$ipn = "mc_gross=19.95&";
		$ipn .= "protection_eligibility=Eligible&";
		$ipn .= "address_status=confirmed&";
		$ipn .= "payer_id=LPLWNMTBWMFAY&";
		$ipn .= "tax=0.00&";
		$ipn .= "address_street=1+Main+St&";
		$ipn .= "payment_date=20%3A12%3A59+Jan+13%2C+2009+PST&";
		$ipn .= "payment_status=Completed&";
		$ipn .= "charset=windows-1252&";
		$ipn .= "address_zip=95131&";
		$ipn .= "first_name=Test&";
		$ipn .= "mc_fee=0.88&";
		$ipn .= "address_country_code=US&";
		$ipn .= "address_name=Test+User&";
		$ipn .= "notify_version=2.6&";
		$ipn .= "custom=&";
		$ipn .= "payer_status=verified&";
		$ipn .= "address_country=United+States&";
		$ipn .= "address_city=San+Jose&";
		$ipn .= "quantity=1&";
		$ipn .= "verify_sign=AtkOfCXbDm2hu0ZELryHFjY-Vb7PAUvS6nMXgysbElEn9v-1XcmSoGtf&";
		$ipn .= "payer_email=gpmac_1231902590_per%40paypal.com&";
		$ipn .= "txn_id=61E67681CH3238416&";
		$ipn .= "payment_type=instant&";
		$ipn .= "last_name=User&";
		$ipn .= "address_state=CA&";
		$ipn .= "receiver_email=gpmac_1231902686_biz%40paypal.com&";
		$ipn .= "payment_fee=0.88&";
		$ipn .= "receiver_id=S8XGHLYDW9T3S&";
		$ipn .= "txn_type=express_checkout&";
		$ipn .= "item_name=&";
		$ipn .= "mc_currency=USD&";
		$ipn .= "item_number=&";
		$ipn .= "residence_country=US&";
		$ipn .= "test_ipn=1&";
		$ipn .= "handling_amount=0.00&";
		$ipn .= "transaction_subject=&";
		$ipn .= "payment_gross=19.95&";
		$ipn .= "shipping=0.00";

		$url = "http://www.copper.site/cart/payment/wsppaypal";

		$ch = curl_init();
		//error_log("******************************************".date("H:i:s"));
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,           true );
		curl_setopt($ch, CURLOPT_POSTFIELDS,    $ipn);
		//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/html; charset=utf-8', 'Content-Length: '.strlen($ipn)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//execute post
		$response = curl_exec($ch);
		//error_log($response);

		echo $response;
	}
	
	

	public function testUPS() {


		$model=new CheckoutForm;

		$model->contactFirstName = 'Kris';
		$model->contactLastName= 'White';
		$model->contactCompany= '';
		$model->contactPhone= '5144414128';
		$model->contactEmail= 'kris@xsilva.com';
		$model->billingAddress1= '123 Fake St';
		$model->billingAddress2= '';
		$model->billingCity= 'Montreal';
		$model->billingCountry= '39';
		$model->billingState= '146';
		$model->billingPostal= '75025';
		$model->shippingAddress1= '123 Fake St';
		$model->shippingAddress2= '';
		$model->shippingCity= 'Plano';
		$model->shippingCountry= '224';
		$model->shippingState= '56';
		$model->shippingPostal= '75025';
		$model->promoCode= '';
		$model->shippingProvider= '';
		$model->shippingPriority= '';
		$model->paymentProvider= '49';
		$model->orderNotes= '';
		$model->acceptTerms= '0';


		$CheckoutForm = $model;

		$CheckoutForm->billingState = State::CodeById($CheckoutForm->billingState);
		$CheckoutForm->billingCountry = Country::CodeById($CheckoutForm->billingCountry);
		$CheckoutForm->shippingState = State::CodeById($CheckoutForm->shippingState);
		$CheckoutForm->shippingCountry = Country::CodeById($CheckoutForm->shippingCountry);



		$objCurrentCart = Yii::app()->shoppingcart;
		//$this->assertInstanceOf('Cart',$objCurrentCart);

		$objProd = Product::model()->findByPk(11); //Test grilled cheese


		$objCurrentCart->AddProduct($objProd,1);



		foreach(CHtml::listData(Modules::model()->findAllByAttributes(array('active'=>1,'category'=>'shipping')),'id','module') as $key=>$val)
		{
			error_log("$val");
			$arrProvider[$key]=Yii::app()->getModule($val)->Name;
			$arrModuleName[$key]=$val;
		}

//We actually contact each module and get shipping
		foreach ($arrModuleName as $key=>$value)
		{
			$arrModuleReturn= Yii::app()->getModule($arrModuleName[$key])->run($CheckoutForm,$objCurrentCart);
			error_log(print_r($arrModuleReturn,true));
			print "\n";
			foreach($arrModuleReturn as $arrService)
			{

				print_r(CHtml::radioButtonList('shippingPriority',false,array($arrService['level']=>$arrService['label'])));
				print "\n";
				print_r($arrService['price']);
				print "\n";
			}


		}






	}


	public function testPaymentModules() {


		$model=new CheckoutForm;

		$model->contactFirstName = 'Kris';
		$model->contactLastName= 'White';
		$model->contactCompany= '';
		$model->contactPhone= '5144414128';
		$model->contactEmail= 'kris@xsilva.com';
		$model->billingAddress1= '123 Fake St';
		$model->billingAddress2= '';
		$model->billingCity= 'Montreal';
		$model->billingCountry= '39';
		$model->billingState= '146';
		$model->billingPostal= '75025';
		$model->shippingFirstName= 'Margaret';
		$model->shippingLastName= 'Whitt';
		$model->shippingAddress1= '123 Fake St';
		$model->shippingAddress2= '';
		$model->shippingCity= 'Plano';
		$model->shippingCountry= '224';
		$model->shippingState= '56';
		$model->shippingPostal= '75025';
		$model->promoCode= '';
		$model->shippingProvider= '';
		$model->shippingPriority= '';
		$model->paymentProvider= '49';
		$model->orderNotes= '';
		$model->acceptTerms= '0';



		$model->shippingProvider =

		$model->cardNumber="5268 8338 3550 3194";
		$model->cardExpiryMonth="09";
		$model->cardExpiryYear="2015";
		$model->cardCVV="789";
		$model->cardNameOnCard="Margaret J. Whitt";


		$model->setScenario('formSubmit');
		$retVal = $model->validate();
		$retArr = $model->getErrors();
		print_r($retArr);
		$this->assertArrayNotHasKey('cardNumber',$retArr);

		$CheckoutForm = $model;

		$CheckoutForm->billingState = State::CodeById($CheckoutForm->billingState);
		$CheckoutForm->billingCountry = Country::CodeById($CheckoutForm->billingCountry);
		$CheckoutForm->shippingState = State::CodeById($CheckoutForm->shippingState);
		$CheckoutForm->shippingCountry = Country::CodeById($CheckoutForm->shippingCountry);



		$objCurrentCart = Yii::app()->shoppingcart;
		//$this->assertInstanceOf('Cart',$objCurrentCart);

		$objProd = Product::model()->findByPk(11); //Test grilled cheese


		//Make sure our customer id and shipping id are valid
		$objAddress = CustomerAddress::model()->findByPk(1);
		$objAddress->address1 = $model->shippingAddress1;
		$objAddress->address2 = $model->shippingAddress2;
		$objAddress->city = $model->shippingCity;
		$objAddress->state = $model->shippingState;
		$objAddress->postal = $model->shippingPostal;
		$objAddress->first_name = $model->shippingFirstName;
		$objAddress->last_name = $model->shippingLastName;
		$objAddress->save();



		$objCurrentCart->AddProduct($objProd,1);
		$objCurrentCart->customer_id=1;
		$objCurrentCart->shipping_id=1;
		$objCurrentCart->payment_id=2;
		$objCurrentCart->billaddress_id=1;
		$objCurrentCart->shipaddress_id=1;
		$objCurrentCart->save();



		$arrModules = array('wsppaypalpro');

		foreach ($arrModules as $module) {

			//Test to make sure the submit string is valid
			$CheckoutForm->debug = true;
			$arrModuleReturn= Yii::app()->getModule($module)->run($CheckoutForm,$objCurrentCart);
			$this->assertContains("ACCT=5268833835503194",$arrModuleReturn);
			$this->assertContains("STREET=123+Fake+St",$arrModuleReturn);
			$CheckoutForm->debug = false;

			$arrModuleReturn= Yii::app()->getModule($module)->run($CheckoutForm,$objCurrentCart);

			$this->assertEquals('0.05',$arrModuleReturn['amount_paid']);
			$this->assertContains(date("Y-m-d"),$arrModuleReturn['payment_date']);

		}








	}

}