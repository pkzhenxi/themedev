<?php
/**
 * Unit tests for all our Wishlist functions
 */

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class WishlistTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		_dbx('SET FOREIGN_KEY_CHECKS=0;');
		_dbx("truncate table xlsws_wishlist");
		_dbx("INSERT INTO `xlsws_wishlist` (`id`, `registry_name`, `registry_description`, `visibility`, `event_date`, `html_content`, `ship_option`, `after_purchase`, `customer_id`, `gift_code`, `created`, `modified`)
VALUES
	(1, 'test1', 'test1', 1, '0000-00-00', '', '0', 1, 1, 'be58ce45b1cd4c4048419d094c1b0bdd', '2013-05-13 11:47:14', '2013-05-13 11:47:14'),
	(2, 'test2', 'test2', 1, '0000-00-00', '', '0', 1, 1, 'dc97f17b6299edd8abf5281661e522fa', '2013-05-13 11:47:24', '2013-05-13 11:47:24');
");



	}


	public function testWish()
	{


		$objWish = Wishlist::model()->updateByPk(1,array('visibility'=>Wishlist::PUBLICLIST));
		$objWish = Wishlist::model()->updateByPk(2,array('visibility'=>Wishlist::PRIVATELIST));

		$objLists = Wishlist::LoadUserLists();

		Yii::app()->controller = $cartController = new WishlistController('default');
		$cartController->init(); //Run init on module first

		ob_start();

		$_POST['WishlistSearch']['email']='george@example.com';
		$cartController->actionSearch();
		$retVal = ob_get_contents();
		$this->assertContains('>test1<',$retVal);
		$this->assertNotContains('>test2<',$retVal);

	}


}


