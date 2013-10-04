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

class LogTest extends PHPUnit_Framework_TestCase
{

	public function testLog()
	{

		Log::GarbageCollect();

		$objLog = new Log();
		$objLog->created = "2009-12-12 12:12";
		$objLog->message = "to be deleted";
		$objLog->save();
		$intStart = Log::model()->count();

		//Test our module while we're here
		Yii::app()->cronJobs->run();


		$intEnd = Log::model()->count();
		$this->assertEquals(1,$intStart-$intEnd);

	}

	public function testWishGarbage()
	{

		_dbx("update xlsws_cart set cart_type=1 where id=2890");

		Wishlist::GarbageCollect();

	}
}