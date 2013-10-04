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

class DeleteTests extends PHPUnit_Framework_TestCase
{

	//Run our delete tests at the end so we can destroy data and not have other tests relying on it

	/**
	 * Test a cascading delete in our category table
	 * @group delete
	 */
	public function testCategoryDelete() {

		$objCategory = Category::model()->findByPk(11);
		$objCategory->CascadeDelete();

	}

	/**
	 * Test a delete images
	 * @group delete
	 */
	public function testImageDelete() {
		$objImage = Images::LoadByWidthHeightParent(256,256,54);
		$this->assertInstanceOf('Images',$objImage);
		$objImage->Delete();


		$objImage = Images::LoadByWidthHeightParent(256,256,54);
		$this->assertNotInstanceOf('Images',$objImage);

	}

	/**
	 * Test a delete product delete image
	 * @group delete
	 */	public function testProductDeleteImages() { }

	/**
	 * Test delete shipping product
	 * @group delete
	 */
	public function testDeleteShipping() {
		PromoCode::DeleteShippingPromoCodes();
		$objCode = PromoCode::model()->findByPk(3);
		$this->assertNotInstanceOf('PromoCode',$objCode);
	}
}
