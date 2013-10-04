<?php

	require_once "../bootstrap.php";
	require_once "PHPUnit/Autoload.php";

class CartMessagesTest extends PHPUnit_Framework_TestCase
{

	public function testCartMessages()
	{
		CartMessages::CreateMessage(500, "This is a test Message");

		$objMessage = CartMessages::model()->findByAttributes(array('cart_id'=>500));
		$this->assertContains('test Message',$objMessage->message);

		$objMessage->delete();



	}



}


