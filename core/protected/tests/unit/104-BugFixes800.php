<?php

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFix800Test extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();

	}

	/**
	 * WS-801 - Email address field is case-sensitive (email addresses are not)
	 * @group taxout
	 */

	public function testWS801()
	{

        _dbx("DELETE from xlsws_customer WHERE email = 'nick@example.com';");

        $objCustomer = new Customer;

        $objCustomer->first_name = "Nick";
        $objCustomer->last_name = "Tesla";

        $objCustomer->record_type = Customer::REGISTERED;
        $objCustomer->email = "NiCk@example.com";
        $objCustomer->mainphone = "553-555-1212";
        $objCustomer->mainphonetype = "mobile";
        $objCustomer->allow_login = 1;

        $retVal = $objCustomer->save();

        $objtest = Customer::LoadByEmail("nick@example.com");
        $this->assertEquals("nick@example.com",$objtest->email);

        $model=new LoginForm();

        $model->email = "NICK@example.com";
        $model->password = "nick";

        $retVal1 = $model->validate();
        $arr = $model->getError('password');
        $this->assertEquals('Incorrect password.',$arr);
        //print_r($arr);

	}

	/**
	 * WS-812 - Null shipping costs not properly translating to 0 on download
	 * @group taxout
	 */
	public function testWS812()
	{

		//set our test WO-30001 to not downloaded to start
		$objCart = Cart::LoadByIdStr('WO-30001');
		$objCart->downloaded=0;
		$objCart->save();

		$objShipping = CartShipping::model()->findByPk($objCart->shipping_id);
		$objShipping->shipping_cost=16.17;
		$objShipping->shipping_sell=19.17;
		$objShipping->save();


		$soap = '<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/get_new_web_orders"><ns1:get_new_web_orders><passkey>webstore</passkey></ns1:get_new_web_orders></SOAP-ENV:Body></SOAP-ENV:Envelope>';

		$resp = sendSoap('get_new_web_orders',$soap);

		//First the prices we expect
		$this->assertContains('ShippingCost:MTYuMTc=',$resp); //16.17
		$this->assertContains('ShippingSell:MTkuMTc=',$resp); //19.17


		//Then null out the lines
		$objShipping = CartShipping::model()->findByPk($objCart->shipping_id);
		$objShipping->shipping_cost=null;
		$objShipping->shipping_sell=null;
		$objShipping->save();

		$resp = sendSoap('get_new_web_orders',$soap);

		$objCart->refresh();
		$objCart->shipping->refresh();

		$this->assertContains('ShippingCost:MA==',$resp);
		$this->assertContains('ShippingSell:MA==',$resp);

	}

	/**
	 * WS-844 - Feature Keyword functionality is not functional
	 * @group taxout
	 */

	public function testWS844()
	{
		//we are going to test 3 products so we need to be sure
		//more than 3 will display by default
		$this->assertGreaterThan(3,_xls_get_conf('PRODUCTS_PER_PAGE'));


		//extra code to undo db changes
//		for ($x=28;$x<41;$x++)
//			_dbx('UPDATE xlsws_product SET featured = 1 WHERE id = '.$x);



		//save ids of products currently featured for undo purposes later
		$featuredIDs = array();
		$temp = Product::model()->findAllByAttributes(array('featured'=>1));
		$this->assertNotEmpty($temp);

		foreach ($temp as $id => $product)
			$featuredIDs[$id] = $product->id;

		//remove featured flag from all products
		foreach ($featuredIDs as $x => $id)
			_dbx('UPDATE xlsws_product SET featured = 0 WHERE id = '.$id);

		//ensure no products flagged as featured
		$temp = Product::model()->findAllByAttributes(array('featured'=>1));
		$this->assertEmpty($temp);
		$this->assertFalse(Product::HasFeatured());

		// check to see if featured page comes up (it shouldn't)
		ob_clean();
		ob_start();
		Yii::app()->controller = new SearchController('search');
		Yii::app()->controller->run('browse');
		$retVal = ob_get_contents();
		ob_end_clean();

//		print_r($retVal);

		$this->assertNotContains('Featured Products',$retVal);

		//most recently modified products show up first in grid.
		//so this product should be the first on the list
		$this->assertContains('yoohoo-chocolate-drink',$retVal);


		//lets flag a few products as featured
		$objProd = Product::model()->findByPk(16); //Powerbar Pure & Simple Bar Roasted Peanut
		$objProd->featured=1;
		$objProd->save();

		$objProd = Product::model()->findByPk(17); //Powerbar Pure & Simple Bar Cranberry/Oatmeal
		$objProd->featured=1;
		$objProd->save();

		$objProd = Product::model()->findByPk(89); //Five Alive
		$objProd->featured=1;
		$objProd->save();

		//check to see if featured page comes up now
		ob_clean();
		ob_start();
		Yii::app()->controller = new SearchController('search');
		Yii::app()->controller->run('browse');
		$retVal = ob_get_contents();
		ob_end_clean();

//		print_r($retVal);

		$this->assertContains('Featured Products',$retVal);

		//only our 3 featured products should show
		//so this product should NOT be there
		$this->assertNotContains('yoohoo-chocolate-drink',$retVal);


		// undo our db changes
		_dbx('UPDATE xlsws_product SET featured = 0 WHERE featured = 1');

		foreach ($featuredIDs as $x => $id)
			_dbx('UPDATE xlsws_product SET featured = 1 WHERE id = '.$id);

		//extra code
//		for ($x=28;$x<41;$x++)
//			_dbx('UPDATE xlsws_product SET featured = 1 WHERE id = '.$x);


	}

}


