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

class CountryTest extends PHPUnit_Framework_TestCase
{

	//Assumes a blanked out truncated customer table
	//Tests will add customer to table which will be used by later tests

	public function testCountry()
	{

		$obj = Country::Load(39);
		$this->assertInstanceOf('Country',$obj);

		$this->assertEquals('CA',$obj->code);



		$obj = Country::LoadByCode('US');
		$this->assertInstanceOf('Country',$obj);

		$this->assertEquals('US',$obj->code);
		$this->assertEquals(224,$obj->id);

	}
}

