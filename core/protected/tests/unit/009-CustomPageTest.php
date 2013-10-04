<?php
	/**

	CartMessages.class.php
	CreditCard.class.php
	CustomPage.class

	Configuration.class.php

	 * */

	require_once "../bootstrap.php";
	require_once "PHPUnit/Autoload.php";

class CustomPagesTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		$obj = CustomPage::LoadByKey('new');
		$obj->title = "New Products";
		$obj->save();
		_dbx("delete from xlsws_custom_page where page_key='beverages'");

		_dbx("INSERT INTO `xlsws_custom_page` (`page_key`, `title`, `page`, `request_url`, `meta_keywords`, `meta_description`, `modified`, `created`, `product_tag`, `tab_position`)
VALUES
	('beverages', 'Beverages', '', 'beverages', NULL, '', '2013-04-30 11:27:38', '2013-04-30 11:27:38', '', 0);
");

		$objPage = CustomPage::LoadByRequestUrl("about-us");
		$objPage->meta_description = "This is meta description stuff in this entry blank.";
		$objPage->save();

	}


	public function testCustomPagesSEO()
	{
		Yii::app()->db->createCommand("update xlsws_custom_page set request_url=null")->execute();
		CustomPage::ConvertSEO();

		$objPage = CustomPage::LoadByKey('tc');
		$this->assertEquals('terms-and-conditions',$objPage->request_url);
	}

	public function testCustomPages()
	{


		$objPage = CustomPage::LoadByKey('promo');
		$this->assertInstanceOf('Custompage',$objPage);
		$strResult = $objPage->Link;
		$this->assertContains("/promotions",$strResult);

		$strResult = CustomPage::GetLinkByKey('new');
		$this->assertContains("/new-products",$strResult);

		//This page conficts with a category named Beverages, so we expect the key to be appended
		$strResult = CustomPage::GetLinkByKey('beverages');
		$this->assertContains("/beverages/pg",$strResult);

		$objPage = CustomPage::LoadByRequestUrl("new-products");
		$this->assertInstanceOf('Custompage',$objPage);


		$strResult = $objPage->PageTitle;
		$this->assertContains("New Products : LightSpeed Web Store",$strResult);


		$strResult = $objPage->Link;
		$this->assertContains("new-products",$strResult);

		$strResult = $objPage->CanonicalUrl;
		$this->assertEquals("http://www.copper.site/new-products",$strResult);

		$strResult = $objPage->RequestUrl;
		$this->assertContains("new-products",$strResult);

		$strResult = $objPage->Title;
		$this->assertEquals("New Products",$strResult);

		$objPage = CustomPage::LoadByRequestUrl("about-us");
		$strResult = $objPage->meta_description;
		$this->assertEquals("This is meta description stuff in this entry blank.",$strResult);




	}



}


