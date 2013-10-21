<?php

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFix800Test extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();

	}

	/**
	 * WS-801 - Email address field is case-sensitive (email addresses are not)
	 * @group taxout
	 */

	public function testWS801()
	{

        _dbx("DELETE from xlsws_customer WHERE email = 'nick@example.com';");

        $objCustomer = new Customer;

        $objCustomer->first_name = "Nick";
        $objCustomer->last_name = "Tesla";

        $objCustomer->record_type = Customer::REGISTERED;
        $objCustomer->email = "NiCk@example.com";
        $objCustomer->mainphone = "553-555-1212";
        $objCustomer->mainphonetype = "mobile";
        $objCustomer->allow_login = 1;

        $retVal = $objCustomer->save();

        $objtest = Customer::LoadByEmail("nick@example.com");
        $this->assertEquals("nick@example.com",$objtest->email);

        $model=new LoginForm();

        $model->email = "NICK@example.com";
        $model->password = "nick";

        $retVal1 = $model->validate();
        $arr = $model->getError('password');
        $this->assertEquals('Incorrect password.',$arr);
        //print_r($arr);

	}



}


