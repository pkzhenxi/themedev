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

class SoapDownloadTest extends PHPUnit_Framework_TestCase
{

	public function testTimeStamp()
	{

		$intDttLastModified = "1355851144";
		echo date("Y-m-d H:i:s",$intDttLastModified);



	}

	public function testSoapSetup()
	{

		$objCart = Cart::LoadByIdStr('WO-30001');
		$objCart->cart_type = CartType::order;
		$objCart->status = OrderStatus::AwaitingProcessing;
		$objCart->currency = "USD";
		$objCart->printed_notes = "WO-30001";
		$objCart->origin = "127.0.0.1";
		$objCart->shipaddress_id=2;
		$objCart->billaddress_id=1;
		$objCart->GenerateLink();
		if (!$objCart->save())
			print_r($objCart->getErrors());


		$objCart = Cart::LoadByIdStr('WO-30000');
		$objCart->cart_type = CartType::cart;
		$objCart->save();


	}
	/**
	 * This uses our SOAP transaction log and basically emulates a full wipe and upload
	 * @group soap
	 */
	public function testSoapDownload()
	{

		$url = 'http://www.copper.site/xls_soap.php';

		$dbC = Yii::app()->db->createCommand();
		$dbC->setFetchMode(PDO::FETCH_OBJ);//fetch each row as Object


		$dbC->select()->from('phpunittest.soap_download')->order('testid,id');

		foreach ($dbC->queryAll() as $row) {

			echo "testing ".$row->soap_action. " ".$row->affected_object." row ".$row->id."\n";

			$ch = curl_init();
			//error_log("******************************************".date("H:i:s"));
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST,           true );
			curl_setopt($ch, CURLOPT_POSTFIELDS,    $row->envelope);
			curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml; charset=utf-8',
				'Content-Length: '.strlen($row->envelope),'Testdb: true','SOAPAction: '.$row->soap_action ));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			//execute post
			$response = curl_exec($ch);
			//error_log($response);



			//First we test the response back from the SOAP transaction itself
			$strResponse = $row->expected_response;
			if (empty($strResponse) || $strResponse=="OK")
				$strResponse = '<'.$row->soap_action.'Result xsi:type="xsd:string">'.$row->expected_response.'</'.$row->soap_action.'Result>';
			$this->assertContains($strResponse,$response);


			//Then we make sure the transaction really did what it was supposed to do
			switch($row->soap_action) {

				case 'db_flush':
					$strTableName = $row->affected_object;
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




	}




}