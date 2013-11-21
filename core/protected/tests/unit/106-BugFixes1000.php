<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kevin
 * Date: 2013-11-13
 * Time: 12:21 PM
 * To change this template use File | Settings | File Templates.
 */
require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFix1000Test extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		Yii::app()->db->schema->getTables();
		Yii::app()->db->schema->refresh();

	}

	/**
	 * WS-1003 - "Enable Wish List" option does not affect all Wish List functionality
	 * @group taxout
	 */

	public function testWS1003()
	{
		$orig = _xls_get_conf('ENABLE_WISH_LIST',0);

		Yii::app()->controller = $controller = new WishlistController('wishlist');
		$controller->init();

		//We need to login as a customer to ensure main wish list functions are available
		$objCustomer = Customer::model()->findByPk(1);
		$strPassword = _xls_decrypt($objCustomer->password);

		//Perform login procedure
		$identity=new UserIdentity($objCustomer->email,$strPassword);
		$identity->authenticate();
		$this->assertEquals(UserIdentity::ERROR_NONE,$identity->errorCode);
		if($identity->errorCode==UserIdentity::ERROR_NONE)
			Yii::app()->user->login($identity,3600*24*30);


		//turn off enable wish lists and test
		_xls_set_conf('ENABLE_WISH_LIST',0);

		$exception = 'Wish lists are not enabled on this store.';

		$arrInd = array(0=>'index',1=>'create',2=>'search');

		foreach ($arrInd as  $ind) {
			$value = new CInlineAction($controller,$ind);

			$e = null;

			try { $controller->beforeAction($value); }
			catch (CHttpException $e){};

			$this->assertInstanceOf('CHttpException',$e);
			$this->assertEquals($exception,$e->getMessage());

			unset($value);
		}

		//turn on wish lists and retest
		_xls_set_conf('ENABLE_WISH_LIST',1);

		foreach ($arrInd as $ind) {
			$value = new CInlineAction($controller,$ind);

			$e = null;

			try { $controller->beforeAction($value); }
			catch (CHttpException $e){};

			$this->assertNotInstanceOf('CHttpException',$e);

			unset($value);
		}

		//undo db change
		_xls_set_conf('ENABLE_WISH_LIST',$orig);
	}

}