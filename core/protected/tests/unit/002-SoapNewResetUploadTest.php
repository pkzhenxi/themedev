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

class SoapNewResetUploadTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Set Tax Inclusive off
	 * @group taxout
	 */
	public function testSetTaxExclusive()
	{

		_xls_set_conf('TAX_INCLUSIVE_PRICING',0);

	}

	/**
	 * Set Tax Inclusive off
	 * @group taxin
	 */
	public function testSetTaxInclusive()
	{

		_xls_set_conf('TAX_INCLUSIVE_PRICING',1);

	}

	/**
	 * This uses our SOAP transaction log and basically emulates a full wipe and upload
	 * @group soap
	 */
	public function testFullSoapUpload()
	{

		_dbx('update xlsws_modules set configuration=\'a:15:{s:17:"PRODUCTS_PER_PAGE";s:2:"12";s:19:"LISTING_IMAGE_WIDTH";s:3:"180";s:20:"LISTING_IMAGE_HEIGHT";s:3:"190";s:18:"DETAIL_IMAGE_WIDTH";s:3:"256";s:19:"DETAIL_IMAGE_HEIGHT";s:3:"256";s:16:"MINI_IMAGE_WIDTH";s:2:"30";s:17:"MINI_IMAGE_HEIGHT";s:2:"30";s:20:"CATEGORY_IMAGE_WIDTH";s:3:"180";s:21:"CATEGORY_IMAGE_HEIGHT";s:3:"180";s:19:"PREVIEW_IMAGE_WIDTH";s:2:"30";s:20:"PREVIEW_IMAGE_HEIGHT";s:2:"30";s:18:"SLIDER_IMAGE_WIDTH";s:2:"90";s:19:"SLIDER_IMAGE_HEIGHT";s:2:"90";s:11:"CHILD_THEME";s:5:"light";s:16:"IMAGE_BACKGROUND";s:7:"#FFFFFF";}\' where module=\'brooklyn\'');

		$url = 'http://'.$_SERVER['testini']['SERVER_NAME'].'/index-test.php/soap/bronze';

		$dbC = Yii::app()->db->createCommand();
		$dbC->setFetchMode(PDO::FETCH_OBJ);//fetch each row as Object


		$dbC->select()->from('phpunittest.soap_upload')->order('testid,id');

		foreach ($dbC->queryAll() as $row) {

			echo "testing ".$row->soap_action. " ".$row->affected_object." row ".$row->id."\n";

			$ch = curl_init();
			//error_log("******************************************".date("H:i:s"));
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST,           true );
			curl_setopt($ch, CURLOPT_POSTFIELDS,    $row->envelope);
			curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml; charset=utf-8',
				'Content-Length: '.strlen($row->envelope),'SOAPAction: '.$row->soap_action ));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			//execute post
			$response = curl_exec($ch);
			//error_log($response);



			//First we test the response back from the SOAP transaction itself
			$strResponse = '>'.$row->expected_response.'</return>';
			if (!is_null($row->expected_response))
				$this->assertContains($strResponse,$response);


			//Then we make sure the transaction really did what it was supposed to do
			switch($row->soap_action) {

				case 'db_flush':
					$strTableName = $row->affected_object;
					echo $row->affected_object;
					$item_count = $strTableName::model()->count();
					$this->assertEquals(0,$item_count);
					break;


				case 'add_tax':
					$checkRow = Yii::app()->db->createCommand(array(
						'select' => array('id', 'lsid','tax'),
						'from' => 'xlsws_tax',
						'where' => 'tax=:id',
						'params' => array(':id'=>$row->affected_object),
					))->queryRow();
					$this->assertEquals($row->affected_object,$checkRow['tax']);
					break;

				case 'add_tax_code':
					$checkRow = Yii::app()->db->createCommand(array(
						'select' => array('id', 'lsid','code'),
						'from' => 'xlsws_tax_code',
						'where' => 'lsid=:id',
						'params' => array(':id'=>$row->affected_object),
					))->queryRow();
					$this->assertEquals($row->affected_object,$checkRow['lsid']);
					break;

				case 'add_tax_status':
					$checkRow = Yii::app()->db->createCommand(array(
						'select' => array('id', 'lsid','status'),
						'from' => 'xlsws_tax_status',
						'where' => 'lsid=:id',
						'params' => array(':id'=>$row->affected_object),
					))->queryRow();
					$this->assertEquals($row->affected_object,$checkRow['lsid']);
					break;


				case 'add_family':
					$checkRow = Yii::app()->db->createCommand(array(
						'select' => array('id', 'family','request_url'),
						'from' => 'xlsws_family',
						'where' => 'family=:id',
						'params' => array(':id'=>$row->affected_object),
					))->queryRow();
					$this->assertEquals($row->affected_object,$checkRow['family']);
					$this->assertNotNull($checkRow['request_url']);
					break;

				case 'save_product':
					//<passkey>webstore</passkey><intRowid>79</intRowid><strCode>COFFEE</strCode>
					$xml = simplexml_load_string($row->envelope);
					$xml->registerXPathNamespace('envoy', 'http://10.80.0.169/'.$row->soap_action);
					$m = $xml->xpath('//envoy:'.$row->soap_action);
					$intRow = $m[0]->intRowid;
					$checkRow = Yii::app()->db->createCommand(array(
						'select' => array('id','request_url'),
						'from' => Product::model()->tableName(),
						'where' => 'id=:id',
						'params' => array(':id'=>$intRow),
					))->queryRow();
					$this->assertEquals($intRow,$checkRow['id']);
					$this->assertNotNull($checkRow['request_url']);
					break;

			}








		}


		echo (string)Yii::getLogger()->getExecutionTime();

		//Check various import scenarios

		$objProduct = Product::model()->findByPk(6);
		$this->assertEquals('SPTURKEY',$objProduct->code);

		//$objProduct = Product::model()->findByPk(152);
		//$this->assertEquals('percent-sign-test-product',$objProduct->request_url);


		_dbx("INSERT INTO `xlsws_category_integration` (`category_id`, `module`, `foreign_id`, `extra`)
			VALUES
			(11, 'amazon', 9642, 'Beverages'),
				(28, 'amazon', 25203, NULL),
				(30, 'amazon', 592, NULL),
				(14, 'amazon', 10143, NULL),
				(12, 'amazon', 10261, NULL),
				(35, 'amazon', 14173, NULL);");


	}

	/**
	 * @group soap
	 * @group soapimage
	 */
	public function testImageUpload() {


		$files = scandir('../photos/');
		foreach($files as $file) {
			$pinfo = mb_pathinfo($file);
			if (is_numeric(($pinfo['filename']))) {

				$url = 'http://'.$_SERVER['testini']['SERVER_NAME'].'/index-test.php/soap/image/product/'.$pinfo['filename'].'/index/0/';
				error_log("posting to ".$url);
				$imageString = file_get_contents('../photos/'.$file);


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
				error_log($response);
				$this->assertEquals($expected,$response);




				curl_close($ch);
			}
		}
	}



}