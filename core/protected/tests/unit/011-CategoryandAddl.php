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

class CategoryandAddl extends PHPUnit_Framework_TestCase
{



	public function testConvertSEO() {

		Yii::app()->db->createCommand("update xlsws_category set request_url=null")->execute();
		Category::ConvertSEO();

		$objCategory = Category::model()->findByPk(17);
		$this->assertEquals('beverages-non-carbonated',$objCategory->request_url);

	}


	public function testGetTrail() {

		$objCategory = Category::model()->findByPk(26);
		$arrTrail = $objCategory->GetTrail();
		$this->assertContains('/beverages-carbonated',$arrTrail[1]['link']);
		$this->assertEquals(16,$arrTrail[1]['key']);


	}


	public function testGetIdByTrail() {

		$strResult = Category::GetIdByTrail(array('snacks','cupcakes'));
		$this->assertEquals(18,$strResult);
	}

	public function testGetSEOPath() {

		$objCategory = Category::model()->findbyPk(17);
		$retValue = $objCategory->GetSEOPath();
		$this->assertEquals('beverages-non-carbonated',$retValue);
	}


	public function testGetTrailByProductId() {


		$strResult = Category::GetTrailByProductId(5); //Product with no category assigned
		$this->assertEmpty($strResult);

		$strResult = Category::GetTrailByProductId(77); //Product with category
		$this->assertEquals("Sandwiches",$strResult[0]['name']);

		$strResult = Category::GetTrailByProductId(40); //Product with 2 level category
		$this->assertEquals("Cupcakes",$strResult[1]['name']);

	}

	public function testLoadByNameParent() {

		$objCategory = Category::LoadByNameParent('Crackers',12);
		$this->assertInstanceOf('Category',$objCategory);
		$this->assertEquals('snacks-crackers',$objCategory->request_url);

	}



	public function testUpdateChildCount() {

		$objCategory = Category::LoadByRequestUrl('beverages-non-carbonated');
		$objCategory->child_count=10;
		$objCategory->save();
		$objCategory->UpdateChildCount();
		$this->assertEquals(1,$objCategory->child_count);
	}


	public function testGets()
	{

		$objCategory = Category::LoadByRequestUrl('beverages-non-carbonated');
		$this->assertInstanceOf('Category',$objCategory);

		$objCategory2 = Category::LoadByRequestUrl('snacks');
		$this->assertInstanceOf('Category',$objCategory2);


		$strReturn = $objCategory->IsPrimary;
		$this->assertFalse($strReturn);

		$strReturn = $objCategory2->IsPrimary;
		$this->assertTrue($strReturn);


		$strReturn = $objCategory->Slug;
		$this->assertEquals("Non-Carbonated",$strReturn);

		$strReturn = $objCategory->CanonicalUrl;
		$this->assertEquals("http://www.copper.site/beverages-non-carbonated",$strReturn);


		$strReturn = $objCategory2->CanonicalUrl;
		$this->assertEquals("http://www.copper.site/snacks",$strReturn);


		$strReturn = $objCategory->HasChildren;
		$this->assertFalse($strReturn);

		$strReturn = $objCategory2->HasChildren;
		$this->assertTrue($strReturn);

		$strReturn = $objCategory->HasProducts;
		$this->assertTrue($strReturn);

		$strReturn = $objCategory2->HasProducts;
		$this->assertTrue($strReturn);

		$strReturn = $objCategory->HasChildOrProduct;
		$this->assertTrue($strReturn);

		$strReturn = $objCategory->ParentObject;
		$this->assertEquals("Beverages",$strReturn->label);

		$strReturn = $objCategory->HasImage;
		$this->assertFalse($strReturn);

//		$strReturn = $objCategory->ListingImage;
//		$this->assertEquals("http://www.copper.site/images/product/n/no_product-180px-190px.jpg",$strReturn);
//
//		$strReturn = $objCategory->MiniImage;
//		$this->assertEquals("http://www.copper.site/images/product/n/no_product-30px-30px.jpg",$strReturn);
//
//		$strReturn = $objCategory->PreviewImage;
//		$this->assertEquals("http://www.copper.site/images/product/n/no_product-30px-30px.jpg",$strReturn);
//
//		$strReturn = $objCategory->SliderImage;
//		$this->assertEquals("http://www.copper.site/images/product/n/no_product-90px-90px.jpg",$strReturn);
//
//		$strReturn = $objCategory->CategoryImage;
//		$this->assertEquals("http://www.copper.site/images/product/n/no_product-180px-180px.jpg",$strReturn);
//
//		$strReturn = $objCategory->PDetailImage;
//		$this->assertEquals("http://www.copper.site/images/product/n/no_product-256px-256px.jpg",$strReturn);
//
//		$strReturn = $objCategory->SmallImage;
//		$this->assertEquals("http://www.copper.site/images/product/n/no_product-180px-190px.jpg",$strReturn);
//
//		$strReturn = $objCategory->Image;
//		$this->assertEquals("http://www.copper.site/images/product/n/no_product-100px-100px.jpg",$strReturn);

		$strReturn = $objCategory->DirLink;
		$this->assertEquals("Beverages/Non-Carbonated/",$strReturn);

		$strReturn = $objCategory2->DirLink;
		$this->assertEquals("Snacks/",$strReturn);

		$strReturn = $objCategory->Link;
		$this->assertEquals("/beverages-non-carbonated",$strReturn);

		$strReturn = $objCategory->PageTitle;
		$this->assertEquals("Non-Carbonated : LightSpeed Web Store",$strReturn);

		$strReturn = $objCategory->PageDescription;
		$this->assertEquals("Non-Carbonated",$strReturn);





		}




}
