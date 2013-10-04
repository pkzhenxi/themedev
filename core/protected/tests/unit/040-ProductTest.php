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

class ProductTest extends PHPUnit_Framework_TestCase
{

	public function testConvertSEO() {

		Yii::app()->db->createCommand("update xlsws_product set request_url=null")->execute();
		Product::ConvertSEO();

		$objProduct = Product::model()->findByPk(5);
		$this->assertEquals('roasted-red-pepper-and-spinach-with-asiago-cheese',$objProduct->request_url);

	}

	public function testProductLoad() {

		$strName = CHtml::encode(Yii::app()->name);
		$this->assertEquals("Web Store",$strName);


	}

	public function testLoad() {

		$objProduct = Product::model()->findByPk(88);

		$this->assertEquals("7Up Soda 12 ounce can",$objProduct->title);

		$objProduct2 = Product::LoadByCode('7Up');
		$this->assertEquals($objProduct,$objProduct2);

		$objProduct3 = Product::LoadByRequestUrl('7up-soda-12-ounce-can');
		$this->assertEquals($objProduct,$objProduct2);

	}

	public function testCalcAvail()
	{

		$id = 88; //7Up Product
		$objProduct = Product::model()->findByPk($id);
		$intResult = $objProduct->CalculateReservedInventory();

		$this->assertEquals(2,$intResult);

	}


	public function testSetFeatured()
	{
		Product::SetFeaturedByKeyword('Sale');
		$objProduct = Product::model()->findByPk(88);
		$this->assertEquals(0,$objProduct->featured);

		$objProduct = Product::model()->findByPk(34);
		$this->assertEquals(1,$objProduct->featured);

	}


	public function testGetMaster()
	{

		$id = 83; //$25 gift card
		$objProduct = Product::model()->findByPk($id);
		$objProductM = $objProduct->FkProductMaster;
		$this->assertInstanceOf('Product',$objProductM);
		$this->assertEquals(82,$objProductM->id);

	}

	public function testGets()
	{

		_xls_set_conf('PREVIEW_IMAGE_HEIGHT',60);
		_xls_set_conf('PREVIEW_IMAGE_WIDTH',60);

		$objProduct = Product::model()->findByPk(88);
		

		$this->assertEquals('7Up Soda 12 ounce can',$objProduct->Name);
		$this->assertFalse($objProduct->IsMaster);
		$this->assertFalse($objProduct->IsChild);
		$this->assertTrue($objProduct->IsIndependent);
		$this->assertTrue($objProduct->IsAddable);
		$this->assertEquals('7Up',$objProduct->Slug);
		$this->assertEquals('7Up',$objProduct->Code);
		$this->assertNull($objProduct->FkProductMaster);
		$this->assertEquals('7Up Soda 12 ounce can',$objProduct->Name);
		$this->assertEquals('/7up-soda-12-ounce-can/dp/88',$objProduct->Link);
		$this->assertEquals('7Up-Soda-12-ounce-can',$objProduct->SEOName);
		$this->assertEquals('http://www.copper.site/7up-soda-12-ounce-can/dp/88',$objProduct->CanonicalUrl);


		//Get these values from our config
		if(isset(Yii::app()->theme->config))
		{
			$pw = Yii::app()->theme->config->PREVIEW_IMAGE_WIDTH;
			$ph = Yii::app()->theme->config->PREVIEW_IMAGE_HEIGHT;
		} else
		{$pw = 60; $ph = 60; }

		$this->assertEquals('/images/product/7/7up-soda-12-ounce-can-180px-190px.jpg',$objProduct->ListingImage);
		$this->assertEquals('/images/product/7/7up-soda-12-ounce-can-30px-30px.jpg',$objProduct->MiniImage);
		$this->assertEquals('/images/product/7/7up-soda-12-ounce-can-'.$pw.'px-'.$ph.'px.jpg',$objProduct->PreviewImage);
		$this->assertEquals('/images/product/7/7up-soda-12-ounce-can-90px-90px.jpg',$objProduct->SliderImage);
		$this->assertEquals('/images/product/7/7up-soda-12-ounce-can-180px-180px.jpg',$objProduct->CategoryImage);
		$this->assertEquals('/images/product/7/7up-soda-12-ounce-can-256px-256px.jpg',$objProduct->PDetailImage);
		$this->assertEquals('/images/product/7/7up-soda-12-ounce-can-180px-190px.jpg',$objProduct->SmallImage);
		$this->assertEquals('/images/product/7/7up-soda-12-ounce-can.png',$objProduct->Image);

		$this->assertEquals('Size',$objProduct->SizeLabel);
		$this->assertEquals('Color',$objProduct->ColorLabel);
		$this->assertEquals(72,$objProduct->Inventory);
		$this->assertEquals('7Up Soda 12 ounce can : LightSpeed Web Store',$objProduct->PageTitle);
		$this->assertEquals('7 Up is a brand of a lemon-lime flavored non-caffeinated soft drink. The rights to the brand are held by Dr Pepper Snapple Group in the United States, and PepsiCo (or its licensees) in the rest of the world, including Puerto Rico, where the concentrate is...',$objProduct->PageDescription);



		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
		{
			$objCart=Yii::app()->shoppingcart;
			$objCart->assignCustomer(3);

			$this->assertEquals('7Up',$objProduct->OriginalCode);
			$this->assertEquals('$14.30',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);
			$this->assertEquals('14.3',$objProduct->PriceValue);

		} else {

			$this->assertEquals('7Up',$objProduct->OriginalCode);
			$this->assertEquals('$1.69',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);
			$this->assertEquals('1.69',$objProduct->PriceValue);



		}

		//Load a product where the web price is different than the regular price
		$objProduct = Product::model()->findByPk(14);
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
		{
			$this->assertEquals('NESTEACan',$objProduct->OriginalCode);
			$this->assertEquals('$1.45',$objProduct->Price);
			$this->assertEquals('$1.57',$objProduct->SlashedPrice);
			$this->assertEquals('1.57',$objProduct->SlashedPriceValue);
			$this->assertEquals('1.45',$objProduct->PriceValue);

		} else {

			$this->assertEquals('NESTEACan',$objProduct->OriginalCode);
			$this->assertEquals('$1.45',$objProduct->Price);
			$this->assertEquals('$1.49',$objProduct->SlashedPrice);
			$this->assertEquals('1.49',$objProduct->SlashedPriceValue);
			$this->assertEquals('1.45',$objProduct->PriceValue);



		}

		}



	public function testGetPriceDisplay() {
	//Test various displays for a matrix product
		$intSave = _xls_get_conf('MATRIX_PRICE');

		$objProduct = Product::LoadByCode('Cupcakes');

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING')==0)
		{
			_xls_set_conf('MATRIX_PRICE',Product::PRICE_RANGE);
			$this->assertEquals('$3.50 - $7.25',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);

			_xls_set_conf('MATRIX_PRICE',Product::LOWEST_PRICE);
			$this->assertEquals('$3.50',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);

			_xls_set_conf('MATRIX_PRICE',Product::HIGHEST_PRICE);
			$this->assertEquals('$7.25',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);

			_xls_set_conf('MATRIX_PRICE',Product::MASTER_PRICE);
			$this->assertEquals('$6.25',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);

			_xls_set_conf('MATRIX_PRICE',Product::CLICK_FOR_PRICING);
			$this->assertEquals('Click for pricing',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);
		}



		if (_xls_get_conf('TAX_INCLUSIVE_PRICING')==1)
		{
			_xls_set_conf('MATRIX_PRICE',Product::PRICE_RANGE);
			$this->assertEquals('$3.50 - $7.63',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);

			_xls_set_conf('MATRIX_PRICE',Product::LOWEST_PRICE);
			$this->assertEquals('$3.50',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);

			_xls_set_conf('MATRIX_PRICE',Product::HIGHEST_PRICE);
			$this->assertEquals('$7.63',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);

			_xls_set_conf('MATRIX_PRICE',Product::MASTER_PRICE);
			$this->assertEquals('$6.58',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);

			_xls_set_conf('MATRIX_PRICE',Product::CLICK_FOR_PRICING);
			$this->assertEquals('Click for pricing',$objProduct->Price);
			$this->assertEquals('',$objProduct->SlashedPrice);

		}

		_xls_set_conf('MATRIX_PRICE',$intSave);




	}

	/**
	 * Set Tax Inclusive off
	 * @group taxout
	 */
	public function testMatrixSamePrice()
	{

		$obj1 = Product::LoadByCode('BOXH2O');
		$obj2 = Product::LoadByCode('BOXH2O-Carton-250ml');
		$obj3 = Product::LoadByCode('BOXH2O-Carton-1L');

		$obj1->sell_web = 41.95;
		$obj2->sell_web = 41.95;
		$obj3->sell_web = 41.95;
		$obj1->save();
		$obj2->save();
		$obj3->save();


		_xls_set_conf('MATRIX_PRICE',Product::PRICE_RANGE);
		$this->assertEquals('$41.95',$obj1->Price);
		$this->assertEquals('',$obj1->SlashedPrice);



		$obj1->sell_web = 41.95;
		$obj2->sell_web = 41.95;
		$obj3->sell_web = 53.95;
		$obj1->save();
		$obj2->save();
		$obj3->save();


	}


	public function testCalculateReservedInventory() {

		$objProduct = Product::LoadByCode('7up');
		$retVal = $objProduct->CalculateReservedInventory();
		$this->assertEquals(2,$retVal);
	}
	public function testCalculateTax() {
		$objProduct = Product::LoadByCode('7up');
		$arrReturn = $objProduct->CalculateTax(146,$objProduct->PriceValue);

		//Though we don't actually calculate tax in a TaxIn environment
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
			$this->assertEquals(0.75075,$arrReturn[1]);
		else
			$this->assertEquals(0.088725,$arrReturn[1]);


	}
	public function testGetAggregateInventory() {

		$objProduct = Product::LoadByCode('Cupcakes');
		$this->assertEquals(1290,$objProduct->inventory);

		$objProduct = Product::LoadByCode('Cupcakes-Pink-M');
		$objProduct->inventory = 0;
		$objProduct->save(); //Save should click off inventory count for master

		$objProduct = Product::LoadByCode('Cupcakes');
		$this->assertEquals(1192,$objProduct->inventory);

		$objProduct = Product::LoadByCode('Cupcakes-Pink-M');
		$objProduct->inventory = 98;
		$objProduct->save(); //Save should click off inventory count for master
		$objProduct = Product::LoadByCode('Cupcakes');
		$this->assertEquals(1290,$objProduct->inventory);


	}



	public function testGetQuantityPrice() {

		$objProduct = Product::model()->findByPk(99);

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING'))
		{
			$this->assertEquals(1.67,$objProduct->GetQuantityPrice(5));
			$this->assertEquals(1.67,$objProduct->GetQuantityPrice(9));
			$this->assertEquals(1.04,$objProduct->GetQuantityPrice(10));
			$this->assertEquals(0.82,$objProduct->GetQuantityPrice(20));
			$this->assertEquals(0.82,$objProduct->GetQuantityPrice(50));

		} else {
			$this->assertEquals(1.59,$objProduct->GetQuantityPrice(5));
			$this->assertEquals(1.59,$objProduct->GetQuantityPrice(9));
			$this->assertEquals(0.99,$objProduct->GetQuantityPrice(10));
			$this->assertEquals(0.78,$objProduct->GetQuantityPrice(20));
			$this->assertEquals(0.78,$objProduct->GetQuantityPrice(50));
		}


	}


	public function testGetTaxRateGrid() {

		$objProduct = Product::model()->findByPk(99);
		$arrReturn = $objProduct->GetTaxRateGrid();

		$this->assertEquals(8.25,$arrReturn[0][2]);


	}
	public function testInventoryDisplayandHas() {

		_xls_set_conf('INVENTORY_DISPLAY',1);

		$objProduct = Product::model()->findByPk(88);
		$this->assertTrue($objProduct->HasInventory());
		$this->assertEquals('72 Available',$objProduct->InventoryDisplay());


		$objProduct = Product::model()->findByPk(24);
		$this->assertFalse($objProduct->HasInventory());
		$this->assertEquals('This item is not currently available',$objProduct->InventoryDisplay());


		$objProduct = Product::model()->findByPk(88);
		$this->assertTrue($objProduct->HasInventory());
		$this->assertEquals('72 Available',$objProduct->InventoryDisplay());


		$objProduct = Product::model()->findByPk(24);
		$this->assertFalse($objProduct->HasInventory());
		$this->assertEquals('This item is not currently available',$objProduct->InventoryDisplay());



		//Turn inventory display off and check
		_xls_set_conf('INVENTORY_DISPLAY',0);
		$objProduct = Product::model()->findByPk(88);
		$this->assertEquals('',$objProduct->InventoryDisplay());


		$objProduct = Product::model()->findByPk(24);
		$this->assertEquals('',$objProduct->InventoryDisplay());

		_xls_set_conf('INVENTORY_DISPLAY',1);


		$objProduct = Product::model()->findByPk(88);
		$this->assertEquals('72 Available',$objProduct->InventoryDisplay());


		$objProduct = Product::model()->findByPk(24);
		$this->assertEquals('This item is not currently available',$objProduct->InventoryDisplay());


		//And just put everything back
		_xls_set_conf('INVENTORY_DISPLAY',1);


	}






}