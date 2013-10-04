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

class CartInventorySequence extends PHPUnit_Framework_TestCase
{


	/**
	 * @group taxout
	 */
	public function testInventory() {

		Yii::app()->user->logout();

		//Establish test
		_dbx("delete from xlsws_cart_item where code='SOUP-BB'");
		_dbx("update xlsws_cart set document_id=null");

		_dbx("delete from xlsws_document_item where code='SOUP-BB' AND (cart_type=4 or cart_type=1)");
		_dbx("delete from xlsws_document where cart_id is not null");
		$objProduct = Product::LoadByCode('SOUP-BB');
		$objProduct->inventory=10;
		$objProduct->inventory_total=10;
		$objProduct->save();

		$soap = '<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/save_product"><ns1:save_product><passkey>webstore</passkey><intRowid>2</intRowid><strCode>SOUP-BB</strCode><strName>Black Bean Soup Here</strName><blbImage></blbImage><strClassName>Soup</strClassName><blnCurrent>1</blnCurrent><strDescription></strDescription><strDescriptionShort></strDescriptionShort><strFamily>Campbells</strFamily><blnGiftCard>0</blnGiftCard><blnInventoried>1</blnInventoried><fltInventory>10.000000</fltInventory><fltInventoryTotal>10.000000</fltInventoryTotal><blnMasterModel>0</blnMasterModel><intMasterId>0</intMasterId><strProductColor></strProductColor><strProductSize></strProductSize><fltProductHeight>0.000000</fltProductHeight><fltProductLength>0.000000</fltProductLength><fltProductWidth>0.000000</fltProductWidth><fltProductWeight>0.000000</fltProductWeight><intTaxStatusId>0</intTaxStatusId><fltSell>5.990000</fltSell><fltSellTaxInclusive>6.480000</fltSellTaxInclusive><fltSellWeb>0.000000</fltSellWeb><strUpc></strUpc><blnOnWeb>1</blnOnWeb><strWebKeyword1>_new</strWebKeyword1><strWebKeyword2></strWebKeyword2><strWebKeyword3></strWebKeyword3><blnFeatured>0</blnFeatured><strCategoryPath>Default</strCategoryPath></ns1:save_product></SOAP-ENV:Body></SOAP-ENV:Envelope>';


		$objProduct->SetAvailableInventory();

		//Create a cart
		$objCart = Yii::app()->shoppingcart;
		$objCart->addProduct($objProduct,2);

		$objCart->customer_id=1;
		$objCart->shipaddress_id=2;
		$objCart->billaddress_id=1;
		$objCart->shipping_id=1;
		$objCart->payment_id=1;
		$objCart->cart_type = CartType::order;
		$objCart->status = "Awaiting Processing";
		$objCart->tax_code_id=146;
		$objCart->SetIdStr();
		$objCart->save();

		$cartController = new CartController('cart');

		$cartController::FinalizeCheckout($objCart,true);

		$objProduct->refresh();
		$this->assertEquals($objProduct->inventory_reserved,2);
		$this->assertEquals($objProduct->inventory_avail,8);


		//Fake download
		Cart::model()->updateByPk($objCart->id_str,array('downloaded'=>1,'status'=>OrderStatus::Downloaded));

		//Fake upload
		$soap1='<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/add_order"><ns1:add_order><passkey>webstore</passkey><strId>'.$objCart->id_str.'</strId><intDttDate>1323234000</intDttDate><intDttDue>1323234000</intDttDue><strPrintedNotes>100000502</strPrintedNotes><strStatus>Requested</strStatus><strEmail>kris@xsilva.com</strEmail><strPhone>9725177126</strPhone><strZipcode>75025</strZipcode><intTaxcode>0</intTaxcode><fltShippingSell>0.000000</fltShippingSell><fltShippingCost>0.000000</fltShippingCost></ns1:add_order></SOAP-ENV:Body></SOAP-ENV:Envelope>';

		$url = 'http://www.copper.site/xls_soap.php';

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,           true );
		curl_setopt($ch, CURLOPT_POSTFIELDS,    $soap1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml; charset=utf-8',
			'Content-Length: '.strlen($soap1),'Testdb: true','SOAPAction: add_order'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

		$response = curl_exec($ch);


		$soap1 = '<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/add_order_item"><ns1:add_order_item><passkey>webstore</passkey><strOrder>'.$objCart->id_str.'</strOrder><intProductId>2</intProductId><fltQty>2.000000</fltQty><strDescription>Black Bean Soup Here</strDescription><fltSell>5.99</fltSell><fltDiscount>0.000000</fltDiscount></ns1:add_order_item></SOAP-ENV:Body></SOAP-ENV:Envelope>';

		curl_setopt($ch, CURLOPT_POSTFIELDS,    $soap1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml; charset=utf-8',
			'Content-Length: '.strlen($soap1),'Testdb: true','SOAPAction: add_order'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

		$response = curl_exec($ch);

	}









}