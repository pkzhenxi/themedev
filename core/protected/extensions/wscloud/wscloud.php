<?php


class wscloud extends ApplicationComponent {


	public $category = "CEventOrder";
	public $name = "Cloud";
	public $version = 1;

	protected $api;
	protected $objModule;

	//Event map
	//onCreateOrder()


	public function init()
	{
		Yii::import('ext.yii-aws.components.*'); //Required to set our include path so the required_once's everywhere work
		$this->objModule = Modules::LoadByName(get_class($this)); //Load our module entry so we can access settings

	}

	/**
	 * Attached event for anytime a new customer is created
	 * @param $event
	 * @return bool
	 */
	public function onCreateOrder($event)
	{
		$this->init();
		$topicArn = $this->objModule->getConfig('topic_arn');

		//don't run this unless we actually have a cloud acct
		if(_xls_get_conf('LIGHTSPEED_CLOUD')=='0' || empty($topicArn)) return true;

		$objCart = Cart::LoadByIdStr($event->order_id);
		$strSignal = $this->buildSignal($objCart);

		Yii::log("Attempting SNS Cloud signal ".$strSignal, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		$sns = new A2Sns();
		$msgId = $sns->publish(array(
			'TopicArn'=>$topicArn,
			'TargetArn'=>$topicArn,
			'Message'=>$strSignal,

		));

		Yii::log("Returned message ID ".$msgId['MessageId'], 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		return true;
	}


	protected function buildSignal($objCart)
	{

		$response = array();
		$response['message_type']='ws_event';
		$response['accountID']=_xls_get_conf('LIGHTSPEED_CLOUD');
		$response['object']='Order';
		$response['objectID']=$objCart->id_str;
		$response['action']='Create';
		$response['url']=Yii::app()->createAbsoluteUrl('/');

		return json_encode($response);

	}



}
