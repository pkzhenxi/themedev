<?php
/**
 * Unit tests for all our helper functions
 */

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class HelperTest extends PHPUnit_Framework_TestCase
{

	public function testEncryption()
	{
		//Convert a string there and back

		$strString = "This is my encrypted string.";

		$strEncrypted = _xls_encrypt($strString);
		echo $strEncrypted;

		$strDecrypted = _xls_decrypt($strEncrypted);

		$this->assertEquals($strDecrypted,$strString);



	}

	public function test_xls_recalculate_inventory()
	{


		Product::model()->updateAll(array('inventory_reserved' => 0,'inventory_avail' => 0), 'web=1' );

		$retVal = _xls_recalculate_inventory();
		echo $retVal;

	}


	/** Test getting a config key */
	public function test__keys() {

		//Test a bad key
		$strReturn = _xls_get_conf('AKEYTHATDOESNTEXIST');
		$this->assertEquals('',$strReturn);

		//Test a good key
//		$strReturn = _xls_get_conf('SESSION_HANDLER'); //consistent to test because it never changes
//		$this->assertEquals('DB',$strReturn);

		//Test a bad key
		$intReturn = _xls_set_conf('AKEYTHATDOESNTEXIST','newvalue');
		$this->assertFalse($intReturn);
//		$intReturn = _xls_set_conf('SESSION_HANDLER','XYZ');
//		$this->assertTrue($intReturn);
//		$intReturn = _xls_set_conf('SESSION_HANDLER','DB');
//		$this->assertTrue($intReturn);

		//Create a new key
		_xls_insert_conf('UNIT_TEST_KEY','Unit Test sample key','123','Unit helper text',0,'BOOL',10,1);
		$obj = Configuration::LoadByKey('UNIT_TEST_KEY');
		$this->assertInstanceOf('Configuration',$obj);
		Configuration::model()->deleteAll("key_name='UNIT_TEST_KEY'");
		Yii::app()->db->createCommand("ALTER TABLE xlsws_configuration AUTO_INCREMENT=1")->execute();

	}

	/** Test building a URL */
	public function test__xls_site_url() {

		//Test empty
		$strReturn = _xls_site_url();
		$this->assertContains('www.copper.site', $strReturn);

		//Test path
		$strReturn = _xls_site_url('example');
		$this->assertStringEndsWith('/example', $strReturn);

	}

	//Test a translation
	public function test__sp() {

		Yii::app()->sourceLanguage = 'en';
		Yii::app()->language = 'en';


		$strReturn = _sp('Sample Text');
		$this->assertEquals('Sample Text',$strReturn);

		Yii::app()->language = 'fr';
		$strReturn = _sp('Sample Text','unittest');
		$this->assertEquals('Exemple de texte',$strReturn);

		Yii::app()->language = 'de';
		$strReturn = _sp('Sample Text','unittest');
		$this->assertEquals('Beispieltext',$strReturn);

		Yii::app()->language = 'en'; //Go back to English so the rest of our Unit Tests run in English!

	}


	function test__qalert()
	{
		//ToDo: is qalert still needed? Is using $this
//		$strReturn = _qalert('Sample');
//		$this->assertEquals('Beispieltext',$strReturn);
	}

//	function test__xls_calculate_price_tax_price()
//	{
//
//		//Ensure tax info is as expected for test
//		TaxCode::model()->deleteByPk(99);
//		$objTax = new TaxCode();
//		$objTax->code='UNITTEST';
//		$objTax->list_order=0;
//		$objTax->tax1_rate=9.35;
//		$objTax->id = 99;
//		$objTax->Save();
//
//		$arrResponse = Tax::CalculatePricesWithTax('10.00', 99, 1);
//		$this->assertEquals('10.935',$arrResponse[0]);
//	}

	/* We can test all our stack functions in sequence here */
	function test_stacks()
	{
		//Test putting something on stack
		_xls_stack_add('UnitTest','TestValue1');
		$this->assertEquals('TestValue1',$_SESSION['stack_vars']['UnitTest'][0]);

		//Get the item off the stock (without removing it)
		$strReturn = _xls_stack_get('UnitTest');
		$this->assertEquals('TestValue1',$strReturn);

		//Get item and remove it
		$strReturn = _xls_stack_pop('UnitTest');
		$this->assertEquals('TestValue1',$strReturn);

		//Verify it was removed from stack
		$strReturn = _xls_stack_pop('UnitTest');
		$this->assertFalse($strReturn);

		//Put multiple items on stack, verify we get most recent
		_xls_stack_add('UnitTest2','TestValue2');
		_xls_stack_add('UnitTest2','TestValue3');
		_xls_stack_add('UnitTest2','TestValue4');
		$strReturn = _xls_stack_get('UnitTest2');
		$this->assertEquals('TestValue4',$strReturn); //Should be most recent

		//Clear the key stack
		_xls_stack_remove('UnitTest2');
		$strReturn = _xls_stack_get('UnitTest2');
		$this->assertFalse($strReturn);

		//Erase the entire stack
		_xls_stack_add('UnitTest1','TestValue1');
		_xls_stack_add('UnitTest2','TestValue2');
		$strReturn = _xls_stack_get('UnitTest2');
		$this->assertEquals('TestValue2',$strReturn);
		_xls_stack_removeall();
		$strReturn = _xls_stack_get('UnitTest2');
		$this->assertFalse($strReturn);

	}

	function test__xls_get_sort_order()
	{
		$qryResult= Yii::app()->db->createCommand('select key_value from xlsws_configuration where key_name=:category')->bindValue('category','PRODUCT_SORT_FIELD')->queryRow();

		//Get the key directly
		$strProperty = _xls_get_conf('PRODUCT_SORT_FIELD' , 'Name');
		$this->assertEquals($qryResult['key_value'],$strProperty);

		if ($strProperty[0] == '-') {
			$strProperty = substr($strProperty,1);
			$blnAscend = false;
		}

		$strReturn =  $strProperty . ($blnAscend ? "" : " DESC");

		//Test our function and make sure it returns the same thing
		$strReturn2 = _xls_get_sort_order();
		$this->assertEquals($strReturn,$strReturn2);


	}


	function test__xls_fopen_w()
	{
		$intHandle = _xls_fopen_w('testunitoutput.txt');
		$this->assertGreaterThan(0,$intHandle);
		fclose($intHandle);
		unlink('testunitoutput.txt');
	}


	/* ToDo: This may not be necessary or we need to just make it a legacy call. */

	function test_templateNamed()
	{
//		$strReturn = templateNamed('css');
//		$this->assertContains('www.copper.site', $strReturn);
	}

	function test__xls_convert_camel()
	{
		$strReturn = _xls_convert_camel('none');
		$this->assertEquals('',$strReturn);

		$strReturn = _xls_convert_camel('TestField');
		$this->assertEquals('test_field',$strReturn);

	}

	function test_camelize()
	{
		$strReturn = camelize('test_field');
		$this->assertEquals('TestField',$strReturn);

		$strReturn = camelize('configuration_type_id');
		$this->assertEquals('ConfigurationTypeId',$strReturn);

		$strReturn = camelize('configuration_type_id',false);
		$this->assertEquals('configurationTypeId',$strReturn);

	}
	function test_isValidEmail()
	{
		//These addresses are bad
		$blnReturn = isValidEmail('bademailaddress');
		$this->assertFalse($blnReturn);

		$blnReturn = isValidEmail('test@example');
		$this->assertFalse($blnReturn);

		$blnReturn = isValidEmail('something@something..co.uk');
		$this->assertFalse($blnReturn);

		$blnReturn = isValidEmail('kris.white@lightspeedretailcom');
		$this->assertFalse($blnReturn);

		//These are good
		$blnReturn = isValidEmail('something@something.co.uk');
		$this->assertTrue($blnReturn);

		$blnReturn = isValidEmail('new.account@gmail.com');
		$this->assertTrue($blnReturn);

		$blnReturn = isValidEmail('kris.white@lightspeedretail.com');
		$this->assertTrue($blnReturn);

		$blnReturn = isValidEmail('test@example.com');
		$this->assertTrue($blnReturn);

	}
	function test_toCharArray()
	{
		//ToDo: deprecated due to _xls_string_split
		$strReturn = toCharArray('Te st');
		$this->assertEquals('T',$strReturn[0]);
		$this->assertEquals('e',$strReturn[1]);
		$this->assertEquals(' ',$strReturn[2]);
		$this->assertEquals('s',$strReturn[3]);
		$this->assertEquals('t',$strReturn[4]);

	}
	function test_values_as_keys()
	{

		$arrSample = array('one','two');
		$arrReturn = _xls_values_as_keys($arrSample);
		$this->assertArrayHasKey('one',$arrReturn);
		$this->assertArrayNotHasKey('three',$arrReturn);

	}
	function test__xls_comma_to_array()
	{
		//ToDo: find why we're using this instead of just explode()
		$arrReturn = _xls_delim_to_array("one,two,three");
		$this->assertArrayHasKey('one',$arrReturn);
		$this->assertContains('three',$arrReturn);
	}
	function test__xls_delim_to_array()
	{
		$arrReturn = _xls_delim_to_array("one|two|three",'|');
		$this->assertArrayHasKey('one',$arrReturn);
		$this->assertContains('three',$arrReturn);
	}
	function test__xls_make_hidden()
	{
		//make_hidden appends \n to end of tag, which creates an issue in unit test, hence our trim
		$strReturn = _xls_make_hidden('unittest','unitvalue');
		$this->assertEquals('<input type="hidden" name="unittest" value="unitvalue">',trim($strReturn));

	}

	function test__xls_log()
	{
		$strReturn = _xls_log('Sample Log Entry - 51-HelperTest on xls_log()');
		$this->assertNull($strReturn);
	}


	function test__xls_get_ip()
	{
		$strReturn = _xls_get_ip();
		$this->assertEquals('mail-ie0-f176.google.com ( 209.85.223.176 )',$strReturn);
	}


	function test__xls_zip_fix()
	{
		$strReturn = _xls_zip_fix('12345-7890');
		$this->assertEquals('12345',$strReturn);
		$strReturn = _xls_zip_fix('h2v1z8');
		$this->assertEquals('H2V1Z8',$strReturn);
		$strReturn = _xls_zip_fix('v5t 3e2');
		$this->assertEquals('V5T3E2',$strReturn);

	}
	function test__xls_validate_zip()
	{
		//Bad zip code
		$blnReturn = _xls_validate_zip('123456','/^\d{5}(-\d{4})?$/');
		$this->assertFalse($blnReturn);

		//Good zip code
		$blnReturn = _xls_validate_zip('12345','/^\d{5}(-\d{4})?$/');
		$this->assertTrue($blnReturn);

		//Good zip code
		$blnReturn = _xls_validate_zip('12345-6789','/^\d{5}(-\d{4})?$/');
		$this->assertTrue($blnReturn);

		//Bad zip code
		$blnReturn = _xls_validate_zip('123456','/^[ABCEGHJKLMNPRSTVXY]\d[A-Z]( )?\d[A-Z]\d$/');
		$this->assertFalse($blnReturn);

		//Good zip code
		$blnReturn = _xls_validate_zip('V5t 3e2','/^[ABCEGHJKLMNPRSTVXY]\d[A-Z]( )?\d[A-Z]\d$/');
		$this->assertTrue($blnReturn);

	}
	function test__xls_read_dir()
	{
		$dir = ".";
		$arrReturn = _xls_read_dir($dir);
		$this->assertContains('051-HelperTest.php',$arrReturn); //This file in this folder
		//$this->assertContains('.DS_Store',$arrReturn); //This file in this folder
		$this->assertNotContains('ShouldBeMissing.php',$arrReturn); //This file in this folder

		$arrReturn = _xls_read_dir($dir,'php');
		$this->assertNotContains('.DS_Store',$arrReturn);

	}

	function test__xls_display_msg()
	{
		//ToDo: should be done as flash messages or some other display
	}
	function test__xls_remember_url()
	{
		_xls_remember_url('http://www.example.com/here');
		$this->assertEquals('http://www.example.com/here',Yii::app()->session['last_url']);

		$strReturn = _xls_get_remembered_url();
		$this->assertEquals('http://www.example.com/here',$strReturn);

	}

	function test__xls_require_login()
	{
		//ToDo: integrate with Yii
	}
	function test__xls_mail_name()
	{
		$strReturn = _xls_mail_name('Unity Tester','unity@example.com');
		$this->assertEquals('Unity Tester <unity@example.com>',$strReturn);
	}
	function test__xls_mail()
	{
		//ToDo: integrate with Yii mail
	}
	function test__xls_mail_body_from_template()
	{
		//ToDo: need to make body from template or find better Yii way of doing this
	}
	function test__xls_trim()
	{
		$strReturn = ' spacing ';
		_xls_trim($strReturn);
		$this->assertEquals('spacing',$strReturn);
		$arrReturn = array('one ',' two',' three ');
		array_walk($arrReturn, '_xls_trim');
		$this->assertContains('one',$arrReturn);
		$this->assertContains('two',$arrReturn);
		$this->assertContains('three',$arrReturn);
	}
	function test__xls_array_search()
	{
		$arrSample = array('one','two','three');
		$this->assertTrue(_xls_array_search('one',$arrSample));
		$this->assertFalse(_xls_array_search('notfound',$arrSample));
	}
	function test__xls_array_search_begin()
	{
		$arrSample = array('one','two','three');
		$this->assertTrue(_xls_array_search_begin('oneway', $arrSample));
		$this->assertFalse(_xls_array_search_begin('noway', $arrSample));
		$this->assertTrue(_xls_array_search_begin('two', $arrSample));


	}
	function test__xls_currency()
	{
		$strReturn = _xls_currency('5.00','USD');
		$this->assertEquals('$5.00',$strReturn);

		$strReturn = _xls_currency('5.00','EUR');
		$this->assertEquals('€5.00',$strReturn);


		$strReturn = _xls_currency('5.00','SEK');
		$this->assertEquals('SEK5.00',$strReturn);

		setlocale(LC_ALL, 'ja');
		Yii::app()->setLanguage('ja');
		$strReturn = _xls_currency('500','JPY');
		$this->assertEquals('￥500.00',$strReturn); //ToDo this should be without decimal

		setlocale(LC_ALL, 'en_en');
		Yii::app()->setLanguage('en');


	}

	function test__xls_remove_leading_slash()
	{
		$strReturn = _xls_remove_leading_slash('/example/path');
		$this->assertEquals('example/path',$strReturn);

		$strReturn = _xls_remove_leading_slash('example/noslash');
		$this->assertEquals('example/noslash',$strReturn);
	}
	function test__xls_url_object()
	{
		//Todo: evaluate if we still need this with Yii
	}
	function test__xls_seo_url()
	{

		$strReturn = _xls_seo_url('Coke 355ml/12oz can');
		$this->assertEquals('coke-355ml-12oz-can',$strReturn);

		$strReturn = _xls_seo_url('Big Bad! Web page?');
		$this->assertEquals('big-bad-web-page',$strReturn);

		$strReturn = _xls_seo_url('This really goes on a long time! This is designed to see what happens when we get past a certain length.');
		$this->assertEquals('this-really-goes-on-a-long-time-this-is-designed-to-see-what-happens-when-we-get-past-a-certain-length',$strReturn);

	}
	function test__xls_seo_name()
	{
		$strReturn = _xls_seo_name("McDonald's & Burger King");
		$this->assertEquals('McDonalds-and-Burger-King',$strReturn);

		$strReturn = _xls_seo_name('  spaces before Here ');
		$this->assertEquals('spaces-before-Here',$strReturn);

		$strReturn = _xls_seo_name("Big #1 Burrito");
		$this->assertEquals('Big-No1-Burrito',$strReturn);

	}


	function test__xls_jssafe_name()
	{
		$strReturn = _xls_jssafe_name("McDonald's & Burger King/Fast Food");
		$this->assertEquals('McDonald\\\'s &amp; Burger King/Fast Food',$strReturn); //This assert is strange since we have to escape the return val

	}
	function test__xls_get_current_customer_id_andname()
	{
		//Ensure customer is as expected for test
		Customer::model()->deleteByPk(99);
		$obj = new Customer();
		$obj->record_type = Customer::REGISTERED;
		$obj->first_name='Unity';
		$obj->last_name="Testerer";
		$obj->email='unittest@example.com';
		$obj->password=md5('unittestpassword');
		$obj->id = 99;
		$obj->created = new CDbExpression('NOW()');
		$obj->modified = new CDbExpression('NOW()');
		$obj->allow_login=1;
		if (!$obj->save())
			print_r($obj->getErrors());

		//Perform login procedure
		$identity=new UserIdentity('unittest@example.com','unittestpassword');
		$identity->authenticate();
		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
		if($identity->errorCode==UserIdentity::ERROR_NONE)
		{
			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			$duration=3600*24*30; // 30 days
			Yii::app()->user->login($identity,$duration);
		}

		//Test login ID
		$intReturn = _xls_get_current_customer_id();
		$this->assertEquals(99,$intReturn);

		//Test customer name
		$strReturn = _xls_get_current_customer_name();
		$this->assertEquals('Unity',$strReturn);

		//Log out and test login is null
		Yii::app()->user->logout();
		Yii::app()->user->clearStates();
		$intReturn = _xls_get_current_customer_id();
		$this->assertNull($intReturn);

	}

	function test__xls_get_url_resource()
	{
		//Todo: is this still necessary with Yii?
	}
	function test__xls_301()
	{
		//ToDo: refactor for Yii

	}
	function test__xls_404()
	{
		//Todo: refactor for Yii
	}

	function test__xls_truncate()
	{
		//This in turn calls _xls_string_smart_truncate()
		$strReturn = _xls_truncate('This is a really long string so what will we do?',20);
		$this->assertEquals('This is a really lon...',$strReturn);

	}

	function test__xls_string_split()
	{
		$arrReturn = _xls_string_split('Test String');
		$this->assertEquals('T',$arrReturn[0]);
		$this->assertEquals('e',$arrReturn[1]);
		$this->assertEquals(' ',$arrReturn[4]);
		$this->assertEquals('t',$arrReturn[6]);
		$this->assertEquals('r',$arrReturn[7]);

	}
	function test__xls_number_only()
	{
		$strReturn = _xls_number_only('$1.20 long string');
		$this->assertEquals('120',$strReturn);

		$strReturn = _xls_number_only('4524-4567-4124-1575');
		$this->assertEquals('4524456741241575',$strReturn);
	}
	function test__xls_letters_only()
	{
		$strReturn = _xls_letters_only('Test String 123');
		$this->assertEquals('TestString',$strReturn);
	}
	function test__xls_clean_currency()
	{
		$strReturn = _xls_clean_currency('$14.00');
		$this->assertEquals('14.00',$strReturn);

//		$strReturn = _xls_clean_currency('$14,00');
//		$this->assertEquals('14,00',$strReturn);

	}
	function test__xls_add_meta_redirect()
	{

		_xls_add_meta_redirect('http://www.example.com');
		$strReturn = _xls_stack_get('xls_meta_redirect');
		$this->assertEquals('http://www.example.com',$strReturn['url']);
		$this->assertEquals('60',$strReturn['delay']);


	}
	function test__xls_add_page_title()
	{
		_xls_add_page_title('Sample Title');
		$strReturn = _xls_stack_get('xls_page_title');
		$this->assertEquals('Sample Title',$strReturn);

	}
	function test__xls_add_formatted_page_title()
	{
		_xls_add_formatted_page_title('Sample Title');
		$strReturn = _xls_stack_get('xls_page_title');
		$this->assertEquals('Sample Title : LightSpeed Web Store',$strReturn);
	}
	function test__xls_format_email_subject()
	{
		$strReturn = _xls_format_email_subject('EMAIL_SUBJECT_CUSTOMER','Unity Testerer','12345');
		$this->assertEquals('LightSpeed Web Store Order Notification 12345',$strReturn);
	}
	function test__xls_add_meta_desc()
	{
		_xls_add_meta_desc('Sample Title');
		$strReturn = _xls_stack_get('xls_meta_desc');
		$this->assertEquals('Sample Title',$strReturn);
	}
	function test__xls_set_crumbtrail()
	{
		//Pass array to test
		_xls_set_crumbtrail(array('1','2'));
		$this->assertEquals(array('1','2'),Yii::app()->session['crumbtrail']);

		//Pass nothing to unset
		_xls_set_crumbtrail();
		$this->assertNull(Yii::app()->session['crumbtrail']);


	}
	function test__xls_get_crumbtrail()
	{
		_xls_set_crumbtrail(array(array(
			'link'=>'http://www.example.com',
			'case'=> '',
			'name'=> _sp("Featured Products")
		)));


		$arrReturn = _xls_get_crumbtrail('names');
		$this->assertEquals('Featured Products',$arrReturn[0]);

		$arrReturn = _xls_get_crumbtrail('full');
		$this->assertEquals('http://www.example.com',$arrReturn[0]['link']);
		$this->assertEquals('Featured Products',$arrReturn[0]['name']);


	}
	function test__xls_get_googlecategory()
	{



		$arrGoogle = _xls_get_googlecategory(3); //Beverages which should match
		$this->assertEquals('Food, Beverages &amp; Tobacco &gt; Beverages',$arrGoogle['Category']);


		$arrGoogle = _xls_get_googlecategory(2); //This has no Google Category, so should return nothing
		$this->assertEquals('',$arrGoogle['Category']);

		$arrGoogle = _xls_get_googlecategory(88); //Subcategory of beverages which should pull master
		$this->assertEquals('',$arrGoogle['Category']);

		$arrGoogle = _xls_get_googleparentcategory(88); //Subcategory of beverages which should pull master
		$this->assertEquals('Food, Beverages &amp; Tobacco &gt; Beverages',$arrGoogle['Category']);


		$arrGoogle = _xls_get_googlecategory(108); //Google with apparel information
		$this->assertEquals('Apparel &amp; Accessories &gt; Clothing',$arrGoogle['Category']);
		$this->assertEquals('Unisex',$arrGoogle['Gender']);
		$this->assertEquals('Adult',$arrGoogle['Age']);

		$arrGoogle = _xls_get_googleparentcategory("4"); //Google with apparel information
		$this->assertEquals('Food, Beverages &amp; Tobacco &gt; Beverages',$arrGoogle['Category']);






	}

	function test__xls_version()
	{
		$strReturn = _xls_version();
		$this->assertEquals(XLSWS_VERSION,$strReturn);
	}
	function test__xls_is_idevice()
	{

		$_SERVER['HTTP_USER_AGENT']= "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		$blnReturn = _xls_is_idevice();
		$this->assertFalse($blnReturn);

		$_SERVER['HTTP_USER_AGENT']= "Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10";
		$blnReturn = _xls_is_idevice();
		$this->assertTrue($blnReturn);

		$_SERVER['HTTP_USER_AGENT']="Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1C25 Safari/419.30";
		$blnReturn = _xls_is_idevice();
		$this->assertTrue($blnReturn);

	}
	function test__xls_is_ipad()
	{
		$_SERVER['HTTP_USER_AGENT']= "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		$blnReturn = _xls_is_ipad();
		$this->assertFalse($blnReturn);

		$_SERVER['HTTP_USER_AGENT']= "Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10";
		$blnReturn = _xls_is_ipad();
		$this->assertTrue($blnReturn);

	}

	function test__xls_is_iphone()
	{
		$_SERVER['HTTP_USER_AGENT']= "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		$blnReturn = _xls_is_iphone();
		$this->assertFalse($blnReturn);

		$_SERVER['HTTP_USER_AGENT']= "Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10";
		$blnReturn = _xls_is_iphone();
		$this->assertFalse($blnReturn);

		$_SERVER['HTTP_USER_AGENT']="Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1C25 Safari/419.30";
		$blnReturn = _xls_is_iphone();
		$this->assertTrue($blnReturn);
	}

	function test__xls_lang_init()
	{
		_xls_lang_init('fr');
		$this->assertEquals('fr',Yii::app()->language);

		_xls_lang_init('en');
		$this->assertEquals('en',Yii::app()->language);

	}
	function test__xls_tax_default_taxcode()
	{
		$objReturn = TaxCode::GetDefault();
		$this->assertInstanceOf('TaxCode',$objReturn);

	}

	function test__encryption()
	{
		$strPassword = "webstore";

		$strResult = _xls_key_encrypt($strPassword);
		$strResult = _xls_key_decrypt($strResult);
		$this->assertEquals($strPassword,$strResult);

	}

	function test_timezones()
	{
		$arrReturn = _xls_timezones();
		$this->assertContains('America/Montreal',$arrReturn);
		$this->assertContains('Europe/London',$arrReturn);

	}

//	function test__xls_show_captcha()
//	{
//		//Show for everyone
//		_xls_set_conf('CAPTCHA_CHECKOUT',2);
//		$strReturn = _xls_show_captcha('checkout');
//		$this->assertTrue($strReturn);
//
//		//Show for logged in users, currently logged out
//		_xls_set_conf('CAPTCHA_CHECKOUT',1);
//		$strReturn = _xls_show_captcha('checkout');
//		$this->assertTrue($strReturn);
//
//		//Perform login procedure
//		$identity=new UserIdentity('unittest@example.com','unittestpassword');
//		$identity->authenticate();
//
//		if($identity->errorCode===UserIdentity::ERROR_NONE)
//		{
//			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
//			$duration=3600*24*30; // 30 days
//			Yii::app()->user->login($identity,$duration);
//		}
//		//We should now NOT show for logged in users
//		$strReturn = _xls_show_captcha('checkout');
//		$this->assertFalse($strReturn);
//		Yii::app()->user->logout();
//
//	}







}