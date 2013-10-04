<?php

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class CategoryTest extends PHPUnit_Framework_TestCase
{

	public function test_ConvertSEO()
	{
	}

	public function test_Delete()
	{
	}

	public function test_GetAncestors()
	{
	}

	public function test_GetChildren()
	{
	}

	public function test_GetDirLink()
	{
	}

	public function test_GetImageLink()
	{
	}

	public function test_getInstance()
	{
	}

	public function test_GetLink()
	{
	}

	public function test_GetMetaDescription()
	{
	}

	public function test_GetMetaKeywords()
	{
	}

	public function test_GetPageMeta()
	{
	}

	public function test_GetParent()
	{
	}

	public function test_GetSEOPath()
	{
	}

	public function test_GetSlug()
	{
	}

	public function test_GetTrail()
	{
	}

	public function test_GetTrailByProductId()
	{
	}

	public function test_GetTree()
	{
	}

	public function test_HasChildOrProduct()
	{
	}

	public function test_HasChildren()
	{
	}

	public function test_HasImage()
	{
	}

	public function test_HasProduct()
	{
	}

	public function test_HasProducts()
	{
	}

	public function test_InitializeManager()
	{
	}

	public function test_IsPrimary()
	{
	}

	public function test_LoadByNameParent()
	{
	}

	public function test_LoadByRequestUrl()
	{
	}

	public function test_model()
	{
	}

	public function test_parseTree()
	{
	}

	public function test_PrintCategory()
	{
	}

	public function test_QueryArray()
	{
	}

	public function test_UpdateChildCount()
	{
		//Load some data from our copper test database and compare it with expected results
		$arrProducts = array(14=>2,16=>7,30=>2);
		foreach ($arrProducts as $key=>$value)
		{
			$obj = Category::model()->findByPk($key);
			$obj->UpdateChildCount();
			$model = Category::model()->findByAttributes(array('id'=>$key));
			$this->assertEquals($value,$model->child_count);

		}



	}


	public function test_getIdByTrail()
	{

		$test0 = array(); //should produce null
		$test1 = array('Beverages'); //should produce 11
		$test2 = array('Beverages','Carbonated'); //should produce 16
		$test3 = array('Beverages','Carbonated','orange'); //should produce 26

		$retValue = Category::GetIdByTrail($test0);
		$this->assertNull($retValue);

		$retValue = Category::GetIdByTrail(array('thiswillnotbefound'));
		$this->assertNull($retValue);

		$retValue = Category::GetIdByTrail($test1);
		$this->assertEquals(11,$retValue);

		$retValue = Category::GetIdByTrail($test2);
		$this->assertEquals(16,$retValue);

		$retValue = Category::GetIdByTrail($test3);
		$this->assertEquals(26,$retValue);


	}


}