<?php

class CopperFunctionalTest extends WebTestCase
{

	public $fixtures=array(
		//'posts'=>'Post',
	);

//	public function testShow()
//	{
//		$this->open('post/1');
//		// verify the sample post title exists
//		$this->assertTextPresent($this->posts['sample1']['title']);
//		// verify comment form exists
//		$this->assertTextPresent('Leave a Comment');
//	}


	public function testIndex()
	{
		$this->open('');
		$this->assertTextPresent('Copyright 2013');
	}

	public function testProductDisplay()
	{
		$this->open('7up-soda-12-ounce-can/dp/88');
		$this->assertTextPresent('Contact Us');
		$this->assertElementPresent('id=addToCart');
		$this->assertElementPresent('id=addToWishList');


//		$this->type('name=ContactForm[name]','tester');
//		$this->type('name=ContactForm[email]','tester@example.com');
//		$this->type('name=ContactForm[subject]','test subject');
		$this->click("//div[@id='addToCart']");
		$this->waitForTextPresent('Qty: 1');
	}

	public function testLoginLogout()
	{
		$this->open('');
		// ensure the user is logged out
		if($this->isTextPresent('Logout'))
			$this->clickAndWait('link=Logout (demo)');

		// test login process, including validation
		$this->clickAndWait('link=Login');
		$this->assertElementPresent('name=LoginForm[username]');
		$this->type('name=LoginForm[username]','demo');
		$this->click("//input[@value='Login']");
		$this->waitForTextPresent('Password cannot be blank.');
		$this->type('name=LoginForm[password]','demo');
		$this->clickAndWait("//input[@value='Login']");
		$this->assertTextNotPresent('Password cannot be blank.');
		$this->assertTextPresent('Logout');

		// test logout process
		$this->assertTextNotPresent('Login');
		$this->clickAndWait('link=Logout (demo)');
		$this->assertTextPresent('Login');
	}
}
