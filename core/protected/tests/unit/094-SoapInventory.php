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

class SoapInventoryTest extends PHPUnit_Framework_TestCase
{


	/**
	 * This uses our SOAP transaction log and basically emulates a full wipe and upload
	 * @group soap
	 */
	public function testSoapInventory()
	{



		$url = 'http://'.$_SERVER['testini']['SERVER_NAME'].'/index-test.php/xls_soap.php';

		$dbC = Yii::app()->db->createCommand();
		$dbC->setFetchMode(PDO::FETCH_OBJ);//fetch each row as Object


		$dbC->select()->from('phpunittest.soap_inventory')->order('testid,id');

		foreach ($dbC->queryAll() as $row) {

			echo "testing ".$row->soap_action. " ".$row->affected_object." row ".$row->id."\n";



			//Test Before scenarios to compare before and after
			switch($row->soap_action) {

				case 'update_inventory':
					$productId = $row->affected_object;
					$objProduct = Product::model()->findByPk($productId);
					$this->assertEquals(120,$objProduct->Inventory);
					break;



			}



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
			$strResponse = $row->expected_response;
			if (empty($strResponse) || $strResponse=="OK")
				$strResponse = '<'.$row->soap_action.'Result xsi:type="xsd:string">'.$row->expected_response.'</'.$row->soap_action.'Result>';
			$this->assertContains($strResponse,$response);


			//Then we make sure the transaction really did what it was supposed to do
			switch($row->soap_action) {

				case 'update_inventory':
					$productId = $row->affected_object;
					$objProduct = Product::model()->findByPk($productId);
					$this->assertEquals(119,$objProduct->Inventory);
					break;



			}



		}


		echo (string)Yii::getLogger()->getExecutionTime();




	}




}