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

class ProductTest extends PHPUnit_Framework_TestCase
{

	public function testStuff()
	{

		print_r($_SERVER);

	}


	public function testCurl()
	{
		$url = "http://www.copper.site/test/test";

		$imageString = "";

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,           true );
		curl_setopt($ch, CURLOPT_POSTFIELDS,    $imageString);
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/html; charset=utf-8', 'Content-Length: '.strlen($imageString),'PassKey: '.'webstore' ));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$response = curl_exec($ch);

		echo $response;


//		$expected = "Image saved for product ".$pinfo['filename'];
//		$this->assertEquals($expected,$response);




	}


}