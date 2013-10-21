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

class CustomerTest extends PHPUnit_Framework_TestCase
{

	//Assumes a blanked out truncated customer table
	//Tests will add customer to table which will be used by later tests

	public function testLogin()
	{

		$identity = new UserIdentity("username","password");
		Yii::app()->user->login($identity,3600);


	}

	public function testCreatingCustomer() {

		_dbx('SET FOREIGN_KEY_CHECKS=0;
			TRUNCATE TABLE `xlsws_cart_messages`;
			TRUNCATE TABLE `xlsws_cart_item`;
			TRUNCATE TABLE `xlsws_cart`;
			TRUNCATE TABLE `xlsws_cart_shipping`;
			TRUNCATE TABLE `xlsws_cart_payment`;
			TRUNCATE TABLE `xlsws_customer`;
			TRUNCATE TABLE `xlsws_customer_address`;
			SET FOREIGN_KEY_CHECKS=1;
		');

		$objCustomer = new Customer;

		$objCustomer->first_name = "George";
		$objCustomer->last_name = "Washington";

		$objCustomer->record_type = Customer::REGISTERED;
		$objCustomer->email = "george@example.com";
		$objCustomer->mainphone = "202-555-1212";
		$objCustomer->mainphonetype = "mobile";
		$objCustomer->allow_login=1;

		$retVal = $objCustomer->save();
		$this->assertEquals(1,$retVal);

		$this->assertEquals($objCustomer->preferred_language,_xls_get_conf('LANG_CODE'));
		$this->assertEquals($objCustomer->currency,_xls_get_conf('CURRENCY_DEFAULT'));


		$objCustomer = new Customer;

		$objCustomer->first_name = "John";
		$objCustomer->last_name = "McDonald";

		$objCustomer->record_type = Customer::REGISTERED;
		$objCustomer->email = "john@example.com";
		$objCustomer->mainphone = "416-555-1212";
		$objCustomer->mainphonetype = "mobile";
		$objCustomer->currency = "CAD";
		$objCustomer->password = Customer::GeneratePassword(6);
		$objCustomer->allow_login=1;

		$retVal = $objCustomer->save();
		$this->assertEquals(2,$retVal);

		$this->assertEquals($objCustomer->preferred_language,_xls_get_conf('LANG_CODE'));
		$this->assertEquals($objCustomer->currency,'CAD');


		$objCustomer = new Customer;

		$objCustomer->first_name = "Davey";
		$objCustomer->last_name = "Crockett";

		$objCustomer->record_type = Customer::REGISTERED;
		$objCustomer->email = "davey@example.com";
		$objCustomer->mainphone = "210-555-1212";
		$objCustomer->mainphonetype = "mobile";
		$objCustomer->currency = "USD";
		$objCustomer->password = Customer::GeneratePassword(6);
		$objCustomer->allow_login=1;

		$retVal = $objCustomer->save();
		$this->assertEquals(3,$retVal);

		$this->assertEquals($objCustomer->preferred_language,_xls_get_conf('LANG_CODE'));
		$this->assertEquals($objCustomer->currency,'USD');
	}

	public function testGeneratePassword() {
		$strReturn = Customer::GeneratePassword(2);

		$this->assertNotEmpty($strReturn);


	}
	public function testGenerateTempPassword() {

		$objCustomer = Customer::model()->findByPk(1);
		$strReturn = $objCustomer->GenerateTempPassword();

		$this->assertNotNull($objCustomer->temp_password);
		$this->assertEquals(_xls_decrypt($objCustomer->temp_password),$strReturn);



	}
//	public function testGetCurrent() {
//
//		//To test this, we need to perform a login
//
//		//First, reset the password to something we know
//		$strReturn = Customer::GeneratePassword(12);
//		$objCustomer = Customer::model()->findByPk(1);
//		$objCustomer->password = _xls_encrypt($strReturn);
//		$objCustomer->save();
//
//		//Perform login procedure
//		$identity=new UserIdentity($objCustomer->email,$strReturn);
//
//		$identity->errorCode = UserIdentity::ERROR_NONE;
//		//$identity->authenticate();
//		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
//		if($identity->errorCode==UserIdentity::ERROR_NONE)
//		{
//			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
//			$duration=3600*24*30; // 30 days
////			$mockSession = $this->getMock('CHttpSession', array('regenerateID'));
////			Yii::app()->setComponent('session', $mockSession);
//			Yii::app()->user->login($identity,$duration);
//		}
//
//		$objCustomerTest = Customer::GetCurrent();
//
//		$this->assertEquals($objCustomer->id,$objCustomerTest->id);
//
//		Yii::app()->user->logout();
//		Yii::app()->user->clearStates();
//
//
//	}

	public function testLoadByEmail() {

		$objCustomer = Customer::LoadByEmail('notfound@example.com');
		$this->assertNotInstanceOf('Customer',$objCustomer);

		$objCustomer = Customer::LoadByEmail('george@example.com');
		$this->assertInstanceOf('Customer',$objCustomer);

	}
	public function testVerifyPasswordStrength() {

		$strReturn = Customer::VerifyPasswordStrength('a');
		$this->assertContains('Password too short',$strReturn);

		$strReturn = Customer::VerifyPasswordStrength('abcdefgh');
		$this->assertFalse($strReturn);



	}



	

}