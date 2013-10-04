<?php


require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class ConfigurationTest extends PHPUnit_Framework_TestCase
{

	public function testConfiguration()
	{

		$retVal = Configuration::exportConfig();
		$this->assertTrue($retVal);

		$filename = Yii::app()->basepath."/../../config/wsconfig.php";
		$handle = fopen($filename, "r");
		$contents = fread($handle, filesize($filename));
		$this->assertContains('theme',$contents);
		fclose($handle);


	}

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


