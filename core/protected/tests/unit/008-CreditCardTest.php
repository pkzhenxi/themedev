<?php

	require_once "../bootstrap.php";
	require_once "PHPUnit/Autoload.php";

class CreditCardTest extends PHPUnit_Framework_TestCase
{

	public function testCreditCard() {

		$objCredit = CreditCard::model()->findByAttributes(array('label'=>'Visa'));
		$this->assertEquals(1,$objCredit->enabled);


	}


}


