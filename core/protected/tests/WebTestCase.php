<?php

/**
 * Change the following URL based on your server configuration
 * Make sure the URL ends with a slash so that we can use relative URLs in test cases
 */
define('TEST_BASE_URL','http://www.copper.site/index-test.php/');



function shutdown() {
	Yii::app()->end();
}

register_shutdown_function('shutdown');

function logit($string)
{
	error_log($string,3,"/Users/kris/sites/errorlog");
	return $string;
}


//function used here
function sendSoap($action,$soap)
{

	$ch = curl_init();
	//error_log("******************************************".date("H:i:s"));
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL,'http://www.copper.site/xls_soap.php');
	curl_setopt($ch, CURLOPT_POST,           true );
	curl_setopt($ch, CURLOPT_POSTFIELDS,    $soap);
	curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml; charset=utf-8',
		'Content-Length: '.strlen($soap),'Testdb: true','SOAPAction: '.$action ));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	//execute post
	$response = curl_exec($ch);


}

/**
 * The base class for functional test cases.
 * In this class, we set the base URL for the test application.
 * We also provide some common methods to be used by concrete test classes.
 */
class WebTestCase extends CWebTestCase
{

	protected $captureScreenshotOnFailure= TRUE;
	protected $screenshotPath = '/volumes/dev/screenshots';
	protected $screenshotUrl = 'http://localhost/screenshots';

	/**
	 * Sets up before each test method runs.
	 * This mainly sets the base URL for the test application.
	 */
	protected function setUp()
	{
		$this->setBrowser('*firefox');
		$this->setBrowserUrl(TEST_BASE_URL);
		parent::setUp();
	}


}
