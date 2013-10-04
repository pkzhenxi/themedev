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

class FamilyTest extends PHPUnit_Framework_TestCase
{
	public function testConvertSEO() {

		Yii::app()->db->createCommand("update xlsws_family set request_url=null")->execute();
		Family::ConvertSEO();

		$objCategory = Family::model()->findByPk(13);
		$this->assertEquals('coca-cola-brands',$objCategory->request_url);

	}

	public function testFamilies()
	{
		$objFamily = Family::LoadByRequestUrl('coca-cola');
		$this->assertInstanceOf('Family',$objFamily);

		$strReturn = $objFamily->Link;
		$this->assertContains('brand/coca-cola',$strReturn);


		$strReturn = $objFamily->PageTitle;
		$this->assertEquals('Coca-Cola : LightSpeed Web Store',$strReturn);


		$strReturn = $objFamily->RequestUrl;
		$this->assertEquals('coca-cola',$strReturn);


		$objFamily = Family::LoadByFamily("Campbell's");
		$this->assertInstanceOf('Family',$objFamily);



	}


}