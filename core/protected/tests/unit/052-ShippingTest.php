<?php
	/**
	 * Unit tests for all our helper functions
	 */

	require_once "../bootstrap.php";
	require_once "PHPUnit/Autoload.php";

class ShippingTest extends PHPUnit_Framework_TestCase
{
	public $checkoutForm;
	public $objCart;

	public function setUp()
	{

		$this->checkoutForm = new CheckoutForm();

		parent::setUp();

		$this->checkoutForm->shippingFirstName = "Kris";
		$this->checkoutForm->shippingLastName = "White";
		$this->checkoutForm->shippingAddress1 = "1409 Mullins Dr.";
		$this->checkoutForm->shippingCity = "Plano";
		$this->checkoutForm->shippingState = "TX";
		$this->checkoutForm->shippingPostal = "75025";
		$this->checkoutForm->shippingCountry = "US";

		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');


		$objModule = Modules::LoadByName('usps');
		$objModule->configuration = 'a:7:{s:5:"label";s:4:"USPS";s:14:"originpostcode";s:5:"11222";s:8:"username";s:12:"786ALTER3964";s:13:"offerservices";a:5:{i:0;s:12:"Express Mail";i:1;s:13:"Priority Mail";i:2;s:13:"Standard Post";i:3;s:26:"Express Mail International";i:4;s:27:"Priority Mail International";}s:15:"restrictcountry";s:4:"null";s:6:"markup";s:1:"3";s:7:"product";s:8:"SHIPPING";}';
		$objModule->save();

		$objModule = Modules::LoadByName('ups');
		$objModule->configuration = 'a:16:{s:5:"label";s:3:"UPS";s:4:"mode";s:3:"UPS";s:13:"origincountry";s:3:"224";s:11:"originstate";s:2:"56";s:8:"username";s:0:"";s:8:"password";s:0:"";s:9:"accesskey";s:0:"";s:22:"customerclassification";s:2:"04";s:14:"originpostcode";s:5:"78759";s:8:"ratecode";s:20:"Regular Daily Pickup";s:7:"package";s:2:"CP";s:14:"regionservices";N;s:13:"offerservices";a:3:{i:0;s:2:"03";i:1;s:2:"11";i:2;s:2:"12";}s:15:"restrictcountry";s:4:"null";s:6:"markup";s:1:"3";s:7:"product";s:8:"SHIPPING";}';
		$objModule->save();

$objModule = Modules::LoadByName('canadapost');
		$objModule->configuration = 'a:7:{s:5:"label";s:11:"Canada Post";s:14:"originpostcode";s:7:"V5T 3E2";s:3:"cpc";s:17:"CPC_DUNBAR_CYCLES";s:13:"offerservices";a:8:{i:0;s:7:"Regular";i:1;s:10:"Xpresspost";i:2;s:16:"Priority Courier";i:3;s:9:"Expedited";i:4;s:14:"Xpresspost USA";i:5;s:21:"Expedited US Business";i:6;s:17:"Small Packets Air";i:7;s:21:"Small Packets Surface";}s:15:"restrictcountry";s:4:"null";s:6:"markup";s:1:"3";s:7:"product";s:8:"SHIPPING";}';
		$objModule->save();

$objModule = Modules::LoadByName('australiapost');
		$objModule->configuration = 'a:7:{s:5:"label";s:14:"Australia Post";s:7:"api_key";s:36:"8d23792c-a296-4aaf-ac82-85a234844907";s:14:"originpostcode";s:4:"4000";s:13:"offerservices";a:12:{i:0;s:18:"AUS_PARCEL_REGULAR";i:1;s:30:"AUS_PARCEL_REGULAR_SATCHEL_3KG";i:2;s:18:"AUS_PARCEL_EXPRESS";i:3;s:30:"AUS_PARCEL_EXPRESS_SATCHEL_3KG";i:4;s:25:"INTL_SERVICE_ECI_PLATINUM";i:5;s:18:"INTL_SERVICE_ECI_M";i:6;s:18:"INTL_SERVICE_ECI_D";i:7;s:16:"INTL_SERVICE_EPI";i:8;s:16:"INTL_SERVICE_PTI";i:9;s:16:"INTL_SERVICE_RPI";i:10;s:21:"INTL_SERVICE_AIR_MAIL";i:11;s:21:"INTL_SERVICE_SEA_MAIL";}s:15:"restrictcountry";s:4:"null";s:6:"markup";s:1:"4";s:7:"product";s:8:"SHIPPING";}';
		$objModule->save();

$objModule = Modules::LoadByName('destinationshipping');
		$objModule->configuration = 'a:5:{s:5:"label";s:20:"Destination Shipping";s:3:"per";s:4:"item";s:13:"offerservices";s:16:"what destination";s:15:"restrictcountry";s:4:"null";s:7:"product";s:8:"SHIPPING";}';
		$objModule->save();

$objModule = Modules::LoadByName('fedex');
		$objModule->configuration = 'a:17:{s:5:"label";s:5:"FedEx";s:9:"accnumber";s:9:"294946276";s:11:"meternumber";s:9:"102942395";s:12:"securitycode";s:25:"st0xxm7g6jxGh2czWs3TIWOmF";s:7:"authkey";s:16:"BzUmPf8YjAvWasAN";s:10:"originadde";s:15:"1409 Mullins Dr";s:10:"origincity";s:5:"Plano";s:14:"originpostcode";s:5:"75025";s:13:"origincountry";s:3:"224";s:11:"originstate";s:2:"56";s:9:"packaging";s:14:"YOUR_PACKAGING";s:8:"ratetype";s:10:"RATED_LIST";s:7:"customs";s:12:"CLEARANCEFEE";s:13:"offerservices";a:6:{i:0;s:15:"FIRST_OVERNIGHT";i:1;s:18:"STANDARD_OVERNIGHT";i:2;s:18:"PRIORITY_OVERNIGHT";i:3;s:22:"INTERNATIONAL_PRIORITY";i:4;s:21:"INTERNATIONAL_ECONOMY";i:5;s:12:"FEDEX_GROUND";}s:15:"restrictcountry";s:4:"null";s:6:"markup";s:1:"3";s:7:"product";s:8:"SHIPPING";}';
		$objModule->save();






	}


	public function testUPS()
	{
		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');

		echo Yii::app()->getComponent('ups')->Name;

		$arrReturn = Yii::app()->getComponent('ups')->setCheckoutForm($this->checkoutForm)->run();

		print_r($arrReturn);
	}

	public function testUSPS()
	{

		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');

		echo Yii::app()->getComponent('usps')->Name;

		$arrReturn = Yii::app()->getComponent('usps')->setCheckoutForm($this->checkoutForm)->run();

		print_r($arrReturn);

		$this->checkoutForm->shippingCity = "Vancouver";
		$this->checkoutForm->shippingState = "BC";
		$this->checkoutForm->shippingPostal = "V5T 3E2";
		$this->checkoutForm->shippingCountry = "CA";
		$arrReturn = Yii::app()->getComponent('usps')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		$this->objCart->clearCart();

	}

	public function testCanadaPost()
	{
		//Canada post keep raising their rates, just make sure we're getting a figure
		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');

		echo Yii::app()->getComponent('canadapost')->Name;

		$arrReturn = Yii::app()->getComponent('canadapost')->setCheckoutForm($this->checkoutForm)->run();


		$this->assertGreaterThanOrEqual(32.99,$arrReturn[2]['price']); //current price 29.99 + 3 markup
		$this->assertEquals('Xpresspost USA',$arrReturn[2]['level']);


		$this->checkoutForm->shippingFirstName = "Kris";
		$this->checkoutForm->shippingLastName = "White";
		$this->checkoutForm->shippingAddress1 = "2416 Main St.";
		$this->checkoutForm->shippingCity = "Vancouver";
		$this->checkoutForm->shippingState = "BC";
		$this->checkoutForm->shippingPostal = "V5T 3E2";
		$this->checkoutForm->shippingCountry = "CA";

		$arrReturn = Yii::app()->getComponent('canadapost')->setCheckoutForm($this->checkoutForm)->run();



		$this->assertGreaterThanOrEqual(10.29,$arrReturn[0]['price']); //7.29 + 3 markup
		$this->assertEquals('Regular',$arrReturn[0]['level']);



		$this->objCart->clearCart();
	}

	public function testFedEx()
	{

		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');

		echo Yii::app()->getComponent('fedex')->Name;

		$arrReturn = Yii::app()->getComponent('fedex')->setCheckoutForm($this->checkoutForm)->run();

		//Fedex keeps updating their prices weekly (based on gas prices??) Makes a unit test testing a price impossible.
		//$this->assertEquals(9.28,$arrReturn[0]['price']); //6.28 + 3 markup
		$this->assertEquals('FEDEX_GROUND',$arrReturn[0]['level']);
		$this->objCart->clearCart();
	}

	public function testFreeShipping()
	{

		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');

		echo Yii::app()->getComponent('freeshipping')->Name;

		$arrReturn = Yii::app()->getComponent('freeshipping')->setCheckoutForm($this->checkoutForm)->run();

		$this->assertEquals(0,$arrReturn[0]['price']);
		$this->assertEquals('Standard 3-5 Business Days',$arrReturn[0]['level']);
		$this->objCart->clearCart();
	}	
	
	public function testFlatrate()
	{
		//Set config for this test
		$config = Yii::app()->getComponent('flatrate')->getConfigValues();
		$config['per']="item";
		$config['rate']=1;
		Yii::app()->getComponent('flatrate')->setConfigValues($config);

		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');
		$this->objCart->addProduct('7up');
		$this->objCart->addProduct('goldbar');

		$arrReturn = Yii::app()->getComponent('flatrate')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		$this->assertEquals(3,$arrReturn[0]['price']);
		$this->objCart->clearCart();


	}
	public function testaustraliapost()
	{


		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');
		$this->objCart->addProduct('7up');
		$this->objCart->addProduct('goldbar');


		$arrReturn = Yii::app()->getComponent('australiapost')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		$this->assertGreaterThan(23.35,$arrReturn[0]['price']); //19.35 plus 4 dollar mar
		$this->objCart->clearCart();


		$this->checkoutForm->shippingFirstName = "Kris";
		$this->checkoutForm->shippingLastName = "White";
		$this->checkoutForm->shippingAddress1 = "14 Kipling Rise";
		$this->checkoutForm->shippingCity = "Melbourne";
		$this->checkoutForm->shippingState = "VIC";
		$this->checkoutForm->shippingPostal = "3752";
		$this->checkoutForm->shippingCountry = "AU";




		$arrReturn = Yii::app()->getComponent('australiapost')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		$this->assertEquals(16.7,$arrReturn[0]['price']);
		$this->objCart->clearCart();


	}

	public function testTieredshipping()
	{
	_dbx("TRUNCATE TABLE `xlsws_shipping_tiers`");
	_dbx("INSERT INTO `xlsws_shipping_tiers` (`id`, `start_price`, `end_price`, `rate`, `class_name`)
		VALUES
			(1, 1, 49.99, 10, 'tieredshipping'),
			(2, 50, 99.99, 5, 'tieredshipping'),
			(4, 100, 500, 0, 'tieredshipping'),
			(5, 500.01, 9999999, 15, 'tieredshipping');
		");

		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');
		$this->objCart->addProduct('7up');
		$this->objCart->addProduct('goldbar');

		$arrReturn = Yii::app()->getComponent('tieredshipping')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		$this->assertEquals(10,$arrReturn[0]['price']);
		$this->objCart->clearCart();


	}
	public function testDestinationShipping()
	{
		Destination::model()->updateByPk(16,array('base_charge'=>-5,'ship_free'=>0,'ship_rate'=>5));

		$this->objCart = Yii::app()->shoppingcart;
		$this->objCart->addProduct('7up');
		$this->objCart->addProduct('7up');
		$this->objCart->addProduct('goldbar');

		$arrReturn = Yii::app()->getComponent('destinationshipping')->setCheckoutForm($this->checkoutForm)->run();
		print_r($arrReturn);
		$this->assertEquals(10,$arrReturn[0]['price']);
		$this->objCart->clearCart();


	}


}