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

class DestinationTest extends PHPUnit_Framework_TestCase
{

	public function testSetup()
	{


		_dbx("TRUNCATE TABLE `xlsws_destination`;
		INSERT INTO `xlsws_destination` (`id`, `country`, `state`, `zipcode1`, `zipcode2`, `taxcode`, `base_charge`, `ship_free`, `ship_rate`, `modified`)
VALUES
	(16, 224, 56, '', '', 104, NULL, NULL, NULL, '2012-09-19 11:04:40'),
	(21, null, null, '', '', 0, NULL, NULL, NULL, '2012-09-20 06:14:43');
");


		$objDest = new Destination();
		$objDest->country = 39;
		$objDest->state = 137;
		$objDest->zipcode1 = 'V5TA1A';
		$objDest->zipcode2 = 'V5Z 1E4';
		$objDest->taxcode = 104;

		if (!$objDest->save())
			die(print_r($objDest->getErrors()));

	}

	public function testLoadDefault() {
		$retValue = Destination::LoadDefault();
		$this->assertEquals(21,$retValue->id);

	}

	public function testGetDefaultOrdering() {
		$retValue = Destination::GetDefaultOrdering();
		$this->assertArrayHasKey('order',$retValue);
	}


	public function testLoadByCountry() {
		$retValue = Destination::LoadByCountry('US');
		$this->assertEquals(56,$retValue[0]->state);
		$this->assertNull($retValue[1]->state);


		$retValue = Destination::LoadByCountry('CA');
		$this->assertEquals(137,$retValue[0]->state);
		$this->assertNull($retValue[1]->state);

		$retValue = Destination::LoadByCountry('US',true);

		$this->assertEquals(10,$retValue[0]->state); //AL
		$this->assertEquals(11,$retValue[1]->state); //AK
	}





	public function testLoadMatching() {

		$retValue = Destination::LoadMatching('US', 'TX', '75025');
		$this->assertEquals(16,$retValue->id);
		$retValue = Destination::LoadMatching('US', 'CA', '90210');
		$this->assertEquals(21,$retValue->id);

		$retValue = Destination::LoadMatching('CA', 'BC', 'V4T1C9');
		$this->assertEquals(21,$retValue->id);

		$retValue = Destination::LoadMatching('CA', 'BC', 'V5Z1C8');
		$this->assertEquals(22,$retValue->id);


	}



	public function testGets()
	{

		$objDest = Destination::model()->findByAttributes(array('country'=>39,'state'=>137));

		$this->assertEquals('V5TA1A',$objDest->Zipcode1);
		$this->assertEquals('V5Z1E4',$objDest->Zipcode2);


	}



	public function testQC()
	{


		$objDestination = Destination::LoadMatching('CA', 'QC', 'H3W 1V3');
		print_r($objDestination);
	}


}