<?php

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFix900Test extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();

	}

	/**
	 * WS-930 - TUC use S3 for product images as they are uploaded, and thumbnails created
	 * @group taxout
	 */

	public function testWS930()
	{



		Modules::model()->updateAll(array('active'=>0),'module = "wsphoto"');
		Modules::model()->updateAll(array('active'=>1),'module = "wscloud"');

		$file = "../photos/88.png";
		$pinfo = mb_pathinfo($file);
		if (is_numeric(($pinfo['filename']))) {

			$url = 'http://'.$_SERVER['testini']['SERVER_NAME'].'/index-test.php/soap/image/product/'.$pinfo['filename'].'/index/0/';
			error_log("posting to ".$url);
			$imageString = file_get_contents('../photos/'.$file);

			echo $url;

			//set the url, number of POST vars, POST data
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST,           true );
			curl_setopt($ch, CURLOPT_POSTFIELDS,    $imageString);
			curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/html; charset=utf-8', 'Content-Length: '.strlen($imageString),'PassKey: '.'webstore' ));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$response = curl_exec($ch);
			$expected = "Image saved for product ".$pinfo['filename'];
			echo($response);
			//$this->assertEquals($expected,$response);




			curl_close($ch);
		}


//		Modules::model()->updateAll(array('active'=>1),'module = "wsphoto"');
//		Modules::model()->updateAll(array('active'=>0),'module = "wscloud"');
	}

	/**
	 * WS-960 - Product Colours and Sizes that contain an ampersand break the Google Merchant feed
	 * @group taxout
	 */

	public function testWS960()
	{
		//undo db changes in case
		_dbx("DELETE FROM `xlsws_product` WHERE `code` = 'NIKE-SHOE'");

		//lets create a product
		$prod = new Product();
		$prod->title = 'Nike Shoe';
		$prod->code =  'NIKE-SHOE';
		$prod->current = $prod->inventoried = $prod->web = 1;
		$prod->gift_card = $prod->master_model = 0;
		$prod->inventory = $prod->inventory_total = $prod->inventory_avail = 3;
		$prod->product_height = $prod->product_length = $prod->product_width = $prod->product_weight = 0;
		$prod->tax_status_id = 0;
		$prod->sell = $prod->sell_web = 99;
		$prod->request_url = 'nike-shoe';
		$prod->featured = 1; //essentially just to test that we see it on the test site

		//give it a color with a reserved character
		$prod->product_color = 'Black & Blue';
		if (!$prod->save())
			print_r($prod->getErrors());

		$url = 'http://'.$_SERVER['testini']['SERVER_NAME'].'/index-test.php/googlemerchant.xml';

		$retVal = file_get_contents($url);
		$this->assertContains('Black & Blue',$retVal);

		//give it a size with a reserved character
		$prod->product_size = 'High & Narrow';
		if (!$prod->save())
			print_r($prod->getErrors());

		$retVal = file_get_contents($url);
		$this->assertContains('High & Narrow',$retVal);

		// undo db changes
		_dbx("DELETE FROM `xlsws_product` WHERE `code` = 'NIKE-SHOE'");

	}

	/**
	 * WS-957 - user is not prompted to enter outside admin password once an email address is specified during the install
	 * @group taxout
	 */

	public function testWS957()
	{
		$model = new InstallForm();

		$model->iagree = 1;
		$model->page = 2;
		$model->scenario = 'page2';
		$model->LSKEY = 'password';
		$model->TIMEZONE = 'America/Montreal';
		$model->encryptionKey = '37381d41c4d9a233fe3527ed49995efe';
		$model->encryptionSalt = '47659430722e99198d9c7b2aa2236be3';
		$model->loginemail = "kevin@example.com";

		$this->assertEquals(false,$model->validate());
		$errors = $model->getErrors();
		$this->assertContains('Password cannot be blank if Email is entered',print_r($errors,true));

	}

}


