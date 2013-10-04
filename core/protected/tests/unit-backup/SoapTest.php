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

class SoapTest extends PHPUnit_Framework_TestCase
{




	public function test_SetUp() {



		$intRowId=12;
		$objCategoryAddl = CategoryAddl::model()->findByPk(12);
		//Now that we've successfully saved in our cache table, update the regular Category table
		$objCategory = Category::model()->findByPk($intRowId);
		// Failing that, create a new Category
		if (!$objCategory) {
			$objCategory = new Category();
			$objCategory->created = new CDbExpression('NOW()');
			$objCategory->id = $intRowId;
		}
		if ($objCategory) {
			$objCategory->label = $objCategoryAddl->label;
			$objCategory->parent = $objCategoryAddl->parent;
			$objCategory->menu_position = $objCategoryAddl->menu_position;
		}
		if (!$objCategory->save())
		{
			_xls_log('Error saving category '.$strCategory);
			return self::UNKNOWN_ERROR;
		}

	}


	public function actionIndex()
	{
	}

	public function add_additional_product_image()
	{
	}

	public function add_additional_product_image_at_index()
	{
	}

	public function add_family()
	{
	}

	public function add_order()
	{
	}

	public function add_order_item()
	{
	}

	public function add_product_qty_pricing()
	{
	}

	public function add_quote()
	{
	}

	public function add_quote_item()
	{
	}

	public function add_related_product()
	{
	}

	public function add_sro()
	{
	}

	public function add_sro_item()
	{
	}

	public function add_sro_repair()
	{
	}

	public function add_tax()
	{
	}

	public function add_tax_code()
	{
	}

	public function add_tax_status()
	{
	}

	public function blank_record()
	{
	}

	public function categ_get_id()
	{
	}

	public function changeRowId()
	{
	}

	public function check_passkey()
	{
	}

	public function column_select()
	{
	}

	public function confirm_passkey()
	{
	}

	public function date_format()
	{
	}

	public function datetime_format()
	{
	}

	public function db_flush()
	{
	}

	public function db_sql_backup()
	{
	}

	public function delete()
	{
	}

	public function delete_category()
	{
	}

	public function delete_order()
	{
	}

	public function delete_quote()
	{
	}

	public function delete_sro()
	{
	}

	public function do_count()
	{
	}

	public function document_flush()
	{
	}

	public function edit_orm_field()
	{
	}

	public function error()
	{
	}

	public function fetch_array()
	{
	}

	public function first_cell()
	{
	}

	public function first_column()
	{
	}

	public function first_row()
	{
	}

	public function flush_category()
	{
	}

	public function get_config()
	{
	}

	public function get_customer_by_email()
	{
	}

	public function get_customer_by_wsid()
	{
	}

	public function get_customers()
	{
	}

	public function get_new_web_orders()
	{
	}

	public function get_product_by_code()
	{
	}

	public function get_quote_link()
	{
	}

	public function get_records()
	{
	}

	public function get_timestamp()
	{
	}

	public function get_web_order_by_wsid()
	{
	}

	public function get_web_order_items()
	{
	}

	public function get_web_orders()
	{
	}

	public function insert()
	{
	}

	public function insert_id()
	{
	}

	public function load_record()
	{
	}

	public function load_records()
	{
	}

	public function output()
	{
	}

	public function outputSoap()
	{
	}

	public function perform()
	{
	}

	public function prepare_input()
	{
	}

	public function publishWsdl()
	{
	}

	public function qobject_to_string()
	{
	}

	public function qobjects_to_string()
	{
	}

	public function query()
	{
	}

	public function remove_family()
	{
	}

	public function remove_product()
	{
	}

	public function test_remove_product_images()
	{

		$soap='<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/remove_product_images"><ns1:remove_product_images><passkey>webstore</passkey><intRowid>113</intRowid></ns1:remove_product_images></SOAP-ENV:Body></SOAP-ENV:Envelope>';

		$obj = new LegacySoapController('index');
		$strResponse = $obj->remove_product_images('webstore', 113, '0', 'Snacks', '', '', '', '2', '');

		$this->assertEquals('OK',$strResponse);

	}

	public function remove_product_qty_pricing()
	{
	}

	public function remove_related_product()
	{
	}

	public function remove_related_products()
	{
	}

	public function remove_tax()
	{
	}

	public function remove_tax_code()
	{
	}

	public function remove_tax_status()
	{
	}

	public function run_command()
	{
	}

	public function save_category()
	{
	}

	public function test_save_category_with_id()
	{
		$intId = 12;

		Yii::app()->db->createCommand()->delete('xlsws_product_category_assn',"category_id=18");
		Yii::app()->db->createCommand()->delete('xlsws_product_category_assn',"category_id=19");
		Yii::app()->db->createCommand()->delete('xlsws_product_category_assn',"category_id=20");
		Yii::app()->db->createCommand()->delete('xlsws_product_category_assn',"category_id=12");

		Yii::app()->db->createCommand()->delete('xlsws_category',"id=18");
		Yii::app()->db->createCommand()->delete('xlsws_category',"id=19");
		Yii::app()->db->createCommand()->delete('xlsws_category',"id=20");
		Yii::app()->db->createCommand()->delete('xlsws_category',"id=12");


		Yii::app()->db->createCommand()->truncateTable('xlsws_category_addl');

		$obj = new LegacySoapController('index');
		$obj->save_category_with_id('webstore', $intId, '0', 'Snacks', '', '', '', '2', '');

		//This should be updated in two places
		$objCategory = CategoryAddl::model()->findByPk($intId);
		if ($this->assertInstanceOf('CategoryAddl',$objCategory))
			$this->assertEquals($intId,$objCategory->id);

		//This should be updated in two places
		$objCategory2 = Category::model()->findByPk($intId);
		if ($this->assertInstanceOf('Category',$objCategory2))
			$this->assertEquals($intId,$objCategory2->id);



		$obj->save_category_with_id('webstore', '18', '12', 'Under Snacks', '', '', '', '2', '');

		//This should be updated in two places
		$objCategory = CategoryAddl::model()->findByPk(18);
		if ($this->assertInstanceOf('CategoryAddl',$objCategory))
			$this->assertEquals($intId,$objCategory->id);

		//This should be updated in two places
		$objCategory2 = Category::model()->findByPk(18);
		if ($this->assertInstanceOf('Category',$objCategory2))
			$this->assertEquals($intId,$objCategory2->id);
	}

	public function save_customer()
	{
	}

	public function save_header_image()
	{
	}

	public function test_save_product()
	{
		$obj = new LegacySoapController('index');
		echo $obj->save_product('webstore', '116', 'Sunkist', 'Sunkist Soda', '', 'Beverages', '1',
			'Sunkist is a br...', '', '', '0', '1', '0.000000', '0.000000', '1', '0', '', '', '0.000000', '0.000000', '0.000000', '0.000000', '0',
			'1.990000', '2.090000', '0.000000', '', '1', '', '', '', '0', 'Beverages');
	}

	public function save_product_categ_assn()
	{
	}

	public function test_save_product_image()
	{
		$obj = new LegacySoapController('index');
		$PassKey="webstore";
		$data = 'iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABl'
			. 'BMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDr'
			. 'EX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r'
			. '8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==';
		$postdata = base64_decode($data);
		$id=88;
		$obj->save_product_image($PassKey, $id, $postdata);

	}

	public function search_param()
	{
	}

	public function sys_log()
	{
	}

	public function timediff()
	{
	}

	public function unique_count()
	{
	}

	public function update_config()
	{
	}

	public function update_inventory()
	{
	}

	public function update_order_downloaded_status_by_id()
	{
	}

	public function update_order_downloaded_status_by_ts()
	{
	}

	public function update_passkey()
	{
	}

	public function ws_version()
	{
	}

	public function xls_input()
	{
	}

	public function xls_output()
	{
	}




		public function testSoapTransmissions()
	{

		$url = 'http://www.copper.site/xls_soap.php';

		for ($x=1; $x<=5; $x++) {
			switch($x) {

				case 1: $soap = '<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/ws_version"><ns1:ws_version><passkey>webstore</passkey></ns1:ws_version></SOAP-ENV:Body></SOAP-ENV:Envelope>';
					$soapaction = "ws_version";
					$expected = '<ws_versionResult xsi:type="xsd:string">3.0.0</ws_versionResult>';
					break;



				case 2: $soap = '<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/save_product"><ns1:save_product><passkey>webstore</passkey><intRowid>33</intRowid><strCode>Cupcakes-Pink-M</strCode><strName>Cupcakes for you, Pink, M</strName><blbImage></blbImage><strClassName>Desserts</strClassName><blnCurrent>1</blnCurrent><strDescription></strDescription><strDescriptionShort></strDescriptionShort><strFamily>House Brand</strFamily><blnGiftCard>0</blnGiftCard><blnInventoried>1</blnInventoried><fltInventory>98.000000</fltInventory><fltInventoryTotal>98.000000</fltInventoryTotal><blnMasterModel>0</blnMasterModel><intMasterId>28</intMasterId><strProductColor>Pink</strProductColor><strProductSize>M</strProductSize><fltProductHeight>0.000000</fltProductHeight><fltProductLength>0.000000</fltProductLength><fltProductWidth>0.000000</fltProductWidth><fltProductWeight>0.000000</fltProductWeight><intTaxStatusId>0</intTaxStatusId><fltSell>6.250000</fltSell><fltSellTaxInclusive>6.580000</fltSellTaxInclusive><fltSellWeb>3.790000</fltSellWeb><strUpc></strUpc><blnOnWeb>1</blnOnWeb><strWebKeyword1>Sale</strWebKeyword1><strWebKeyword2></strWebKeyword2><strWebKeyword3></strWebKeyword3><blnFeatured>0</blnFeatured><strCategoryPath>Snacks	Cupcakes</strCategoryPath></ns1:save_product></SOAP-ENV:Body></SOAP-ENV:Envelope>';
					$soapaction = "save_product";
					$expected = '<save_productResult xsi:type="xsd:string">OK</save_productResult>';
					break;

				case 3: $soap = '<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/save_product"><ns1:save_product><passkey>webstore</passkey><intRowid>120</intRowid><strCode>Sunkist-Diet-Medium</strCode><strName>Sunkist Soda, Diet, Medium</strName><blbImage></blbImage><strClassName>Beverages</strClassName><blnCurrent>1</blnCurrent><strDescription>This is a diet version of the regular Sunkist soda.</strDescription><strDescriptionShort xsi:nil="1"></strDescriptionShort><strFamily xsi:nil="1"></strFamily><blnGiftCard>0</blnGiftCard><blnInventoried>1</blnInventoried><fltInventory>1.000000</fltInventory><fltInventoryTotal>1.000000</fltInventoryTotal><blnMasterModel>0</blnMasterModel><intMasterId>116</intMasterId><strProductColor>Diet</strProductColor><strProductSize>Medium</strProductSize><fltProductHeight>0.000000</fltProductHeight><fltProductLength>0.000000</fltProductLength><fltProductWidth>0.000000</fltProductWidth><fltProductWeight>0.000000</fltProductWeight><intTaxStatusId>0</intTaxStatusId><fltSell>1.990000</fltSell><fltSellTaxInclusive>2.090000</fltSellTaxInclusive><fltSellWeb>0.000000</fltSellWeb><strUpc xsi:nil="1"></strUpc><blnOnWeb>1</blnOnWeb><strWebKeyword1 xsi:nil="1"></strWebKeyword1><strWebKeyword2 xsi:nil="1"></strWebKeyword2><strWebKeyword3 xsi:nil="1"></strWebKeyword3><blnFeatured>0</blnFeatured><strCategoryPath>Default</strCategoryPath></ns1:save_product></SOAP-ENV:Body></SOAP-ENV:Envelope>';
					$soapaction = "save_product";
					$expected = '<save_productResult xsi:type="xsd:string">OK</save_productResult>';
					break;

				case 4: $soap = '<SOAP-ENV:Envelope xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ZSI="http://www.zolera.com/schemas/ZSI/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Header></SOAP-ENV:Header><SOAP-ENV:Body xmlns:ns1="http://10.80.0.169/add_related_product"><ns1:add_related_product><passkey>webstore</passkey><intProductId>76</intProductId><intRelatedId>77</intRelatedId><intAutoadd>1</intAutoadd><fltQty>1.000000</fltQty></ns1:add_related_product></SOAP-ENV:Body></SOAP-ENV:Envelope>';
				$soapaction = "add_related_product";
					$expected = '<add_related_productResult xsi:type="xsd:string">OK</add_related_productResult>';
					break;

			}


			$ch = curl_init();
			error_log("******************************************".date("H:i:s"));
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST,           true );
			curl_setopt($ch, CURLOPT_POSTFIELDS,    $soap);
			curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml; charset=utf-8', 'Content-Length: '.strlen($soap),'SOAPAction: '.$soapaction ));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//execute post
			$response = curl_exec($ch);
			error_log($response);
			$this->assertContains($expected,$response);



		}

	}


}