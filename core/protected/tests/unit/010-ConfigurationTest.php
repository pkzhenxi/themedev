<?php


require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class ConfigurationTest extends PHPUnit_Framework_TestCase
{


	public function testNextIdKey()
	{
		$objKey = Configuration::LoadByKey('NEXT_ORDER_ID');
		$this->assertEquals(30000,$objKey->key_value);
	}

	public function testNonKey()
	{

		$retVal = _xls_get_conf('BLAH_BLAH','75');
		$this->assertEquals(75,$retVal);
	}

}


