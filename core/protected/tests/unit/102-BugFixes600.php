<?php

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFix600Test extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();

	}

	/**
	 * WS-649 - Non-inventoried products do not display when "Make product disappear" option is selected
	 * @group taxout
	 */
	public function testWS649()
	{

		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',1); //allow backorders

		//http://www.copper.site/search/results?q=gift
		$_GET['q']="gift";
		Yii::app()->controller = new SearchController('search');
		ob_clean();
		ob_start();
		Yii::app()->controller->actionResults();
		$retVal = ob_get_contents();
		ob_end_clean();


		$this->assertContains('/gift-card-for-web-site/dp/82',$retVal);

		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',0); //Make product disappaer
		//Should still show gift card because it's non-inventoried

		ob_clean();
		ob_start();
		Yii::app()->controller->actionResults();
		$retVal = ob_get_contents();
		ob_end_clean();
		$this->assertContains('/gift-card-for-web-site/dp/82',$retVal);



	}



}


