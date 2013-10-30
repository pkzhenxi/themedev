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

class ProductTagsTest extends PHPUnit_Framework_TestCase
{

	public function testTags()
	{

		$objProduct = Product::LoadByRequestUrl('cupcakes-for-you-pink-l');

		print_r($objProduct->productTags[1]->tag->tag);
		foreach ($objProduct->productTags as $tag)
			if ($tag->tag->tag=="new")
				echo "yes"; else echo "no";


		print_r( CHtml::listData($objProduct->productTags,'tag.tag','tag.tag'));

	}

}