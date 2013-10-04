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

class StateTest extends PHPUnit_Framework_TestCase
{

	//Assumes a blanked out truncated customer table
	//Tests will add customer to table which will be used by later tests

	public function testState()
	{

		$obj= State::Load(27);
		$this->assertInstanceOf('State',$obj);

		$this->assertEquals('Iowa',$obj->state);



		$obj = State::LoadByCode('TX',224);
		$this->assertInstanceOf('State',$obj);

		$this->assertEquals('TX',$obj->code);
		$this->assertEquals(56,$obj->id);

	}




}

