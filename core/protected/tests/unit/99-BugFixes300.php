<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kevin
 * Date: 2013-10-01
 * Time: 12:03 PM
 * To change this template use File | Settings | File Templates.
 */

require_once "../bootstrap.php";
require_once "PHPUnit/Autoload.php";

class BugFix300Test extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        Yii::app()->db->schema->getTables();
        Yii::app()->db->schema->refresh();

    }




	//Bug fix tests

	/**
     * WS-343 - Installer configuration does not check for valid formatting of email addresses
     *
     * @group taxout
     */

    public function testWS343()
    {
        $model = new InstallForm();

        $model->page = 3;
        $model->iagree = 1;
        $model->LSKEY = 'password';
        $model->encryptionKey = '37381d41c4d9a233fe3527ed49995efe';
        $model->encryptionSalt = '47659430722e99198d9c7b2aa2236be3';
        $model->TIMEZONE = 'America/Montreal';
        $model->STORE_NAME = 'LightSpeed Web Store';
        $model->STORE_ADDRESS1 = '123 Fake Street';
        $model->STORE_ADDRESS2 = 'Montreal, QC, H2S 3C4';
        $model->STORE_HOURS = 'Mon-Fri 9-5pm';
        $model->STORE_PHONE = '555-555-1234';

        $model->EMAIL_FROM = 'nonsense';
        $model->scenario = 'page3';
        $this->assertEquals(false,$model->validate());
        $errors = $model->getErrors();
        $this->assertContains('not a valid email address',print_r($errors,true));

	    $model->EMAIL_FROM = 'nonsense@example';
	    $model->scenario = 'page3';
	    $this->assertEquals(false,$model->validate());
	    $errors = $model->getErrors();
	    $this->assertContains('not a valid email address',print_r($errors,true));

        $model->EMAIL_FROM = 'nonsense@example.ca';
        $this->assertEquals(true,$model->validate());

    }

}