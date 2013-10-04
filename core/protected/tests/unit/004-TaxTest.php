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

class TaxTest extends PHPUnit_Framework_TestCase
{

	//TEsts tax, tax code, tax status

	public function testCodes() {

		$objTaxCode = TaxCode::GetDefault();
		$this->assertEquals('Ut',$objTaxCode->code);


		$objTaxCode = TaxCode::GetNoTaxCode();
		$this->assertEquals('NOTAX',$objTaxCode->code);

		$this->assertTrue($objTaxCode->IsNoTax());


	}

	public function testAnyDestination()
	{
		//Delete any * * we had before
		$objDestination= Destination::model()->findByAttributes(array('country'=>null,'state'=>null));
		if ($objDestination instanceof Destination)
			$objDestination->delete();

		//Create a new one
		TaxCode::VerifyAnyDestination();

		//and verify it exists
		$objDestination= Destination::model()->findByAttributes(array('country'=>null,'state'=>null));
		$this->assertInstanceOf('Destination',$objDestination);
	}



	public function testRemovingTax()
	{

		$retVal = Tax::StripTaxesFromPrice(1.78,0);
		$this->assertEquals(1.69,$retVal);

		$retVal = Tax::StripTaxesFromPrice(310.49,0);
		$this->assertEquals(295.00,$retVal);

		//print_r($retVal);

	}


	public function testCalculations()
	{

		$arrResult = Tax::CalculatePricesWithTax(50,104,0);

		$this->assertEquals(4.125,$arrResult[1][1]);
		$this->assertEquals(54.13,$arrResult[0]);

	}


	public function testGets()
	{
		$objTax = Tax::LoadByLS(1);
		$this->assertEquals('TX',$objTax->tax);
		$this->assertEquals('TX',$objTax->Tax);


	}



	public function testStatus()
	{
		$objTax = TaxStatus::GetNoTaxStatus();
		$this->assertEquals(38,$objTax->lsid);


		$objTax = TaxStatus::LoadByLS(0);
		$this->assertEquals('Default',$objTax->status);

	}
}