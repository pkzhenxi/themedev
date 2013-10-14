<?php

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFix600Test extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();

	}

	/**
	 * WS-649 - Non-inventoried products do not display when "Make product disappear" option is selected
	 * @group taxout
	 */
	public function testWS649()
	{

		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',1); //allow backorders

		//http://www.copper.site/search/results?q=gift
		$_GET['q']="gift";
		Yii::app()->controller = new SearchController('search');
		ob_clean();
		ob_start();
		Yii::app()->controller->actionResults();
		$retVal = ob_get_contents();
		ob_end_clean();


		$this->assertContains('/gift-card-for-web-site/dp/82',$retVal);

		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',0); //Make product disappaer
		//Should still show gift card because it's non-inventoried

		ob_clean();
		ob_start();
		Yii::app()->controller->actionResults();
		$retVal = ob_get_contents();
		ob_end_clean();
		$this->assertContains('/gift-card-for-web-site/dp/82',$retVal);

	}

    /**
     * WS-625 - Products cannot be deleted if they have certain properties
     * @group taxout
     */
    public function testWS625()
    {
        //add a product to the database
        $product = new Product;
        $product->title = 'You Cannot Delete Me';
        $product->class_id = 2;
        $product->code = 'NO-DELETE';
        $product->current = 1;
        $product->inventoried = 1;
        $product->inventory_avail = 5;
        $product->inventory_total = 5;
        $product->inventory =  5;
        $product->family_id = 10;
        $product->created = date("Y-m-d H:i:s");
        $product->modified = $product->created;
        $product->request_url = 'you-cannot-delete-me';
        $product->sell = 8.95;
        $product->web = 1;
        $product->save();

//        print_r($product->attributes);

        //assign it a related product
        $relprod = new ProductRelated;
        $relprod->product_id = $product->id;
        $relprod->related_id = 75;
        $relprod->save();

        //assign it as a related product of another
        $relprod1 = new ProductRelated;
        $relprod1->product_id = 81;
        $relprod1->related_id = $product->id;
        $relprod1->save();

        //add quantity pricing
        $qtypr = new ProductQtyPricing;
        $qtypr->product_id = $product->id;
        $qtypr->pricing_level = 1;
        $qtypr->qty = 5;
        $qtypr->price = 8.15;
        $qtypr->save();

        //add a tags
        $tags = new ProductTags;
        $tags->product_id = $product->id;
        $tags->tag_id = 1;
        $tags->save();

        //attempt to delete product
        $Controller = new DatabaseadminController('');
        $_POST['pk'] = $product->id;
        $_POST['name'] = 'code';
        $_POST['value'] = '';

        ob_clean();
        ob_start();
        $Controller->actionProducts();
        $retVal = ob_get_contents();
        ob_end_clean();

        //is product still there?
        $this->assertEquals('delete',$retVal);
    }
}


