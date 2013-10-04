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

class CustomerAddressTest extends PHPUnit_Framework_TestCase
{

	//Assumes a blanked out truncated customer table
	//Tests will add customer to table which will be used by later tests


	public function testCreatingCustomerAddressInformation() {

		_dbx('SET FOREIGN_KEY_CHECKS=0;
			TRUNCATE TABLE `xlsws_customer_address`;
			SET FOREIGN_KEY_CHECKS=1;
		');

		//Load our sample customer
		$objCustomer = Customer::LoadByEmail('george@example.com');
		$this->assertInstanceOf('Customer',$objCustomer);


		$objAddress = new CustomerAddress;
		$objAddress->first_name = "George";
		$objAddress->last_name = "Washington";
		$objAddress->company = "Paramount Pictures";
		$objAddress->address1 = "5555 Melrose Avenue";
		$objAddress->city = "Los Angeles";
			$objCountry = Country::LoadByCode('US');
		$objAddress->country_id = $objCountry->id;
			$objState = State::LoadByCode('CA',$objAddress->country_id);
		$objAddress->state_id = $objState->id;
		$objAddress->postal = "90038";
		$objAddress->residential = CustomerAddress::BUSINESS;
		$objAddress->customer_id = $objCustomer->id;
		if (!$objAddress->save())
			print_r($objAddress->GetErrors());

		$this->assertEquals(1,$objAddress->id);

		$objCustomer->default_shipping_id = $objAddress->id;


		$objAddress2 = new CustomerAddress;
		$objAddress2->first_name = "Thomas";
		$objAddress2->last_name = "Jefferson";
		$objAddress2->company = "Warner Bros.";
		$objAddress2->address1 = "4000 Warner Blvd";
		$objAddress2->address2 = "Building 44 LL";
		$objAddress2->city = "Burbank";
			$objCountry = Country::LoadByCode('US');
		$objAddress2->country_id = $objCountry->id;
			$objState = State::LoadByCode('NY',$objAddress2->country_id);
		$objAddress2->state_id = $objState->id;
		$objAddress2->postal = "01101";
		$objAddress2->residential = CustomerAddress::BUSINESS;
		$objAddress2->customer_id = $objCustomer->id;
		if (!$objAddress2->save())
			print_r($objAddress2->GetErrors());

		$objAddress->refresh();
		$objAddress2->refresh();

		$this->assertEquals(2,$objAddress2->id);

		$objCustomer->default_billing_id = $objAddress2->id;

		if (!$objCustomer->save(false))
		{
			print_r($objCustomer->getErrors());
			$this->assertTrue($objCustomer->save());
		}

		$this->assertEquals($objCustomer->default_shipping_id,$objAddress->id);
		$this->assertEquals($objCustomer->default_billing_id,$objAddress2->id);

		echo $objAddress->created;

		$this->assertContains(date("Y-m-d"),$objAddress->created);
		$this->assertContains(date("Y-m-d"),$objAddress->modified);

		$this->assertContains(date("Y-m-d"),$objAddress2->created);
		$this->assertContains(date("Y-m-d"),$objAddress2->modified);

		//These should be real-time resolving based on the codes
		$this->assertEquals('NY',$objAddress2->state);
		$this->assertEquals('US',$objAddress->country);


		//CREATE SECOND CUSTOMER INFO



		//Load our sample customer
		$objCustomer = Customer::LoadByEmail('john@example.com');
		$this->assertInstanceOf('Customer',$objCustomer);


		$objAddress = new CustomerAddress;
		$objAddress->first_name = "John";
		$objAddress->last_name = "MacDonald";
		$objAddress->address1 = "867 W 8th Ave";
		$objAddress->city = "Vancouver";
		$objCountry = Country::LoadByCode('CA');
		$objAddress->country_id = $objCountry->id;
		$objState = State::LoadByCode('BC',$objAddress->country_id);
		$objAddress->state_id = $objState->id;
		$objAddress->postal = "V5T 3E2";
		$objAddress->residential = CustomerAddress::RESIDENTIAL;
		$objAddress->customer_id = $objCustomer->id;
		if (!$objAddress->save())
			print_r($objAddress->GetErrors());

		$this->assertEquals(3,$objAddress->id);

		$objCustomer->default_shipping_id = $objAddress->id;


		$objAddress2 = new CustomerAddress;
		$objAddress2->first_name = "Alexander";
		$objAddress2->last_name = "Mackenzie";
		$objAddress2->company = "Xsilva";
		$objAddress2->address1 = "7049 St-Urbain";
		$objAddress2->address2 = "Porte Ouest";
		$objAddress2->city = "Montréal";
		$objCountry = Country::LoadByCode('CA');
		$objAddress2->country_id = $objCountry->id;
		$objState = State::LoadByCode('QC',$objAddress2->country_id);
		$objAddress2->state_id = $objState->id;
		$objAddress2->postal = "H2S3H4";
		$objAddress2->residential = CustomerAddress::BUSINESS;
		$objAddress2->customer_id = $objCustomer->id;
		if (!$objAddress2->save())
			print_r($objAddress2->GetErrors());

		$objAddress->refresh();
		$objAddress2->refresh();

		$this->assertEquals(4,$objAddress2->id);

		$objCustomer->default_billing_id = $objAddress2->id;

		if (!$objCustomer->save(false))
		{
			print_r($objCustomer->getErrors());
			$this->assertTrue($objCustomer->save());
		}

		$this->assertEquals($objCustomer->default_shipping_id,$objAddress->id);
		$this->assertEquals($objCustomer->default_billing_id,$objAddress2->id);

		echo $objAddress->created;

		$this->assertContains(date("Y-m-d"),$objAddress->created);
		$this->assertContains(date("Y-m-d"),$objAddress->modified);

		$this->assertContains(date("Y-m-d"),$objAddress2->created);
		$this->assertContains(date("Y-m-d"),$objAddress2->modified);

		//These should be real-time resolving based on the codes
		$this->assertEquals('QC',$objAddress2->state);
		$this->assertEquals('CA',$objAddress->country);

	}


	public function testTexasAddress()
	{

		//Load our sample d
		$objCustomer = Customer::LoadByEmail('davey@example.com');
		$this->assertInstanceOf('Customer',$objCustomer);


		$objAddress = new CustomerAddress;
		$objAddress->first_name = "Davey";
		$objAddress->last_name = "Crockett";
		$objAddress->address1 = "910 Alamo Way";
		$objAddress->city = "San Antonio";
		$objCountry = Country::LoadByCode('US');
		$objAddress->country_id = $objCountry->id;
		$objState = State::LoadByCode('TX',$objAddress->country_id);
		$objAddress->state_id = $objState->id;
		$objAddress->postal = "78220";
		$objAddress->residential = CustomerAddress::RESIDENTIAL;
		$objAddress->customer_id = $objCustomer->id;
		if (!$objAddress->save())
			print_r($objAddress->GetErrors());

		$this->assertEquals(5,$objAddress->id);

		$objCustomer->default_shipping_id = $objAddress->id;
		$objCustomer->default_billing_id = $objAddress->id;
		$objCustomer->save();

	}

	public function testNonsenseAddress()
	{

		//Load our sample d
		$objCustomer = Customer::LoadByEmail('davey@example.com');
		$this->assertInstanceOf('Customer',$objCustomer);


		$objAddress = new CustomerAddress;
		$objAddress->address_label = "asdfasfd";
		$objAddress->first_name = "asf";
		$objAddress->last_name = "asdf";
		$objAddress->address1 = "3030 April";
		$objAddress->city = "asdf";
		$objCountry = Country::LoadByCode('US');
		$objAddress->country_id = $objCountry->id;
		$objState = State::LoadByCode('IL',$objAddress->country_id);
		$objAddress->state_id = $objState->id;
		$objAddress->postal = "33333";
		$objAddress->residential = CustomerAddress::RESIDENTIAL;
		$objAddress->customer_id = $objCustomer->id;
		if (!$objAddress->save())
			print_r($objAddress->GetErrors());

		$this->assertEquals(6,$objAddress->id);

		$objCustomer->default_shipping_id = $objAddress->id;
		$objCustomer->default_billing_id = $objAddress->id;
		$objCustomer->save();

	}
	

}