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

class EventprocessorsTest extends PHPUnit_Framework_TestCase
{


	public function setUp()
	{

		Yii::import('ext.*');

	}


	public function testPhoto()
	{

		$imageString = file_get_contents('../photos/'."108.png");

		$strControllerName = "LegacysoapController";
		$controller = new $strControllerName($strControllerName);

		$controller->save_product_image(108,$imageString);



	}
}