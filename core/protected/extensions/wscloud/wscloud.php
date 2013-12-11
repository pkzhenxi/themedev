<?php


class wscloud extends ApplicationComponent {


	public $category = "CEventOrder,CEventPhoto";
	public $name = "Cloud";
	public $version = 1;

	protected $api;
	protected $objModule;

	//Event map
	//onCreateOrder()
	//onUploadPhoto()
	//onFlushTable()


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
	public function onAddCustomer($event)
	{
		$this->init();
		$topicArn = $this->objModule->getConfig('topic_arn');

		//don't run this unless we actually have a cloud acct
		if(_xls_get_conf('LIGHTSPEED_CLOUD')=='0' || empty($topicArn)) return true;

		$objCustomer = $event->objCustomer;
		$strSignal = $this->buildCustomerSignal($objCustomer);

		$this->sendSignal($strSignal,$topicArn);
	}

	/**
	 * Update a customer
	 * @param $event
	 * @return bool
	 */
	public function onUpdateCustomer($event)
	{
		//The signal building takes care of add or update, so just save code
		$this->onAddCustomer($event);

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
		$strSignal = $this->buildOrderSignal($objCart);

		$this->sendSignal($strSignal,$topicArn);

		return true;
	}

	public function onFlushTable($event)
	{
		if(!isset($_SERVER['amazon_key'])) return true;

		$this->init();
		$s3 = new S3($_SERVER['amazon_key'], $_SERVER['amazon_secret']);
		$s3->deleteObject('lightspeedwebstore',_xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL').'/product');
	}


	/**
	 * Attached event for anytime a product photo is uploaded
	 * @param $event
	 * @return bool
	 */
	public function onUploadPhoto($event)
	{

		if(!isset($_SERVER['amazon_key']))
		{
			Yii::log("Attempted Cloud transaction but amazon_key not set", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return true;
		}

		$this->init();

		//We were passed these by the CEventPhoto class
		$blbImage = $event->blbImage; //$image resource
		$objProduct = $event->objProduct;
		$intSequence = $event->intSequence;

		//Check to see if we have an Image record already
		$criteria = new CDbCriteria();
		$criteria->AddCondition("`product_id`=:product_id");
		$criteria->AddCondition("`index`=:index");
		$criteria->AddCondition("`parent`=`id`");
		$criteria->params = array (':index'=>$intSequence,':product_id'=>$objProduct->id);
		$objImage = Images::model()->find($criteria);

		if (!($objImage instanceof Images))
			$objImage = new Images();
		else
			$this->RemoveImageFromS3($objImage);

		//Assign width and height of original
		$objImage->width = imagesx($blbImage);
		$objImage->height = imagesy($blbImage);

		//Assign filename this image, actually write the binary file
		$objImage->strImageName = Images::AssignImageName($objProduct,$intSequence);


		$objImage->product_id=$objProduct->id;
		$objImage->index=$intSequence;


		//Save image record
		Yii::trace("saving ".$objImage->strImageName,'application.'.__CLASS__.".".__FUNCTION__);
		if (!$objImage->save()) {
			Yii::log("Error saving image " .
				print_r($objImage->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		$objImage->parent = $objImage->id; //Assign parent to self
		$objImage->save();

		//Update product record with imageid if this is a primary
		if ($intSequence==0)
		{
			$objProduct->image_id = $objImage->id;
			if (!$objProduct->save()) {
				Yii::log("Error updating product " .
					print_r($objProduct->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}
		}


		//Find or create a Cloud ID record if we have it
		if(isset($event->cloud_image_id))
		{
			$objImageCloud = ImagesCloud::model()->findByAttributes(array('image_id'=>$objImage->id));
			if(!($objImageCloud instanceof ImagesCloud))
			{
				$objImageCloud = new ImagesCloud();
				$objImageCloud->image_id = $objImage->id;
			}

			$objImageCloud->cloud_image_id = $event->cloud_image_id;
			$objImageCloud->cloudinary_public_id = $event->cloudinary_public_id;
			$objImageCloud->cloudinary_cloud_name = $event->cloudinary_cloud_name;
			$objImageCloud->cloudinary_version = $event->cloudinary_version;

			if (!$objImageCloud->save()) {
				Yii::log("Error updating ImageCloud " .
					print_r($objImageCloud->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}

		}


		//Save as temporary file
		$d = YiiBase::getPathOfAlias('webroot')."/runtime/cloudimages/"._xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL');
		@mkdir($d,0777,true);
		$tmpOriginal = tempnam($d,"img");
		@unlink($tmpOriginal);
		$tmpOriginal .= ".png";
		$retVal = Images::check_transparent($blbImage);
		if($retVal)
		{
			imagealphablending($blbImage, false);
			imagesavealpha($blbImage, true);
		}
		imagepng($blbImage,$tmpOriginal);
		$url = $this->SaveToS3($objImage->strImageName,$tmpOriginal);

		if($url != false)
		{
			$objImage->image_path = $url;
			$objImage->save();

			foreach(ImagesType::$NameArray as $intType=>$value)
				if ($intType>0) //exclude original size
				{
					list($intWidth, $intHeight) = ImagesType::GetSize($intType);
					$this->createThumb($objImage,$intWidth,$intHeight,$tmpOriginal);
				}
		}

		@unlink($tmpOriginal);

		return true;


	}

	/**
	 * Attached event for anytime a product photo is deleted
	 * @param $event
	 * @return bool
	 */
	public function onDeletePhoto($event)
	{

		if(!isset($_SERVER['amazon_key']))
		{
			Yii::log("Attempted Cloud transaction but amazon_key not set", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return true;
		}

		//We've either called this accidentally or with a local path we don't want to process, so bail
		if (empty($event->s3_path) || substr($event->s3_path,0,2) != '//')
			return true;

		$this->RemoveImageFromS3(null,$event->s3_path);


	}
	public function Resynccloud()
	{
		$this->init();
		$topicArn = $this->objModule->getConfig('topic_arn');

		//don't run this unless we actually have a cloud acct
		if(_xls_get_conf('LIGHTSPEED_CLOUD')=='0' || empty($topicArn)) return true;

		$strSignal = $this->buildResyncSignal();

		$this->sendSignal($strSignal,$topicArn);
	}

	protected function buildOrderSignal($objCart)
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
	protected function buildResyncSignal()
	{

		$response = array();
		$response['message_type']='re_sync';
		$response['accountID']=_xls_get_conf('LIGHTSPEED_CLOUD');
		$response['action']='all';

		return json_encode($response);

	}

	protected function buildCustomerSignal($objCustomer)
	{

		$response = array();
		$response['message_type']='ws_event';
		$response['accountID']=_xls_get_conf('LIGHTSPEED_CLOUD');
		$response['object']='Customer';
		$response['objectID']=$objCustomer->id;
		if (!is_null($objCustomer->lightspeed_id))
			$response['action']='Update';
			else $response['action']='Create';
		$response['url']=Yii::app()->createAbsoluteUrl('/');

		return json_encode($response);

	}


	public function SaveToS3($keyPath,$pathToFile)
	{
		$this->init();

		if(!isset($_SERVER['amazon_key']) || !isset($_SERVER['amazon_secret'])) return false;

		Yii::log("Uploading /"._xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL').'/'.$keyPath,
			'info', 'application.'.__CLASS__.".".__FUNCTION__);

		$mimeType="text/html";
		if(substr($keyPath,-4)==".css")
			$mimeType="text/css";
		if(substr($keyPath,-4)==".jpg")
			$mimeType="image/jpeg";
		if(substr($keyPath,-4)==".png")
			$mimeType="image/png";
		$s3 = new S3($_SERVER['amazon_key'], $_SERVER['amazon_secret']);
		$result = $s3->putObjectFile($pathToFile,
			"lightspeedwebstore",
			_xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL').'/'.$keyPath,
			S3::ACL_PUBLIC_READ,
			array(),
			$mimeType
		);

		if($result)
			return '//lightspeedwebstore.s3.amazonaws.com/'._xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL').'/'.$keyPath;
		else
		{
			Yii::log("Error saving to cloud "._xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL').'/'.$keyPath,
				'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

	}

	public function RemoveImageFromS3($objImage,$image_path = null)
	{
		$this->init();
		$s3 = new S3($_SERVER['amazon_key'], $_SERVER['amazon_secret']);

		if(is_null($image_path))
		{
			$criteria = new CDbCriteria();
			$criteria->AddCondition("`product_id`=:product_id");
			$criteria->AddCondition("`index`=:index");
			$criteria->params = array (':index'=>$objImage->index,':product_id'=>$objImage->product_id);
			$objImages = Images::model()->findAll($criteria);

			foreach($objImages as $image)
			{
				Yii::log("Attempting to delete  ".$image->image_path,
					'info', 'application.'.__CLASS__.".".__FUNCTION__);

				$key = str_replace("//lightspeedwebstore.s3.amazonaws.com/","",$image->image_path);
				if (!empty($image->image_path))
					$s3->deleteObject('lightspeedwebstore',$key);
			}
		} else {
			$image_path = str_replace("http:","",$image_path);
			$image_path = str_replace("//lightspeedwebstore.s3.amazonaws.com/","",$image_path);

			$s3->deleteObject('lightspeedwebstore',$image_path);
		}


	}


	/**
	 * Create thumbnail for image in specified size
	 * @param $objImage
	 * @param $intNewWidth
	 * @param $intNewHeight
	 */
	protected function createThumb($objImage,$intNewWidth,$intNewHeight,$tmpOriginal)
	{
		//Get our original file from LightSpeed
		$strOriginalFile=$objImage->image_path;
		$strNewThumbnail = Images::GetImageName($strOriginalFile, $intNewWidth, $intNewHeight);

		$d = YiiBase::getPathOfAlias('webroot')."/runtime/cloudimages/"._xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL');
		@mkdir($d,0777,true);
		$strNewThumbnailWithPath = $d.'/'.$strNewThumbnail;

		$image = Yii::app()->image->load($tmpOriginal);

		if(_xls_get_conf('IMAGE_SHARPEN', '20') != 0){
			$image->resize($intNewWidth,$intNewHeight)
				->quality(_xls_get_conf('IMAGE_QUALITY', '75'))
				->sharpen(_xls_get_conf('IMAGE_SHARPEN', '20'));
		} else {
			$image->resize($intNewWidth,$intNewHeight)
				->quality(_xls_get_conf('IMAGE_QUALITY', '75'));
		}

		$arrPath = mb_pathinfo($strNewThumbnailWithPath);
		if (!file_exists($arrPath['dirname']))
			if (!mkdir($arrPath['dirname'],0777,true)) {
				Yii::log("Error attempting to create ".$arrPath['dirname'], 'error', 'Images');
				return false;
			}

		$image->save($strNewThumbnailWithPath); //just save normally with no special effects
		$S3path = $this->SaveToS3($strNewThumbnail,$strNewThumbnailWithPath);
		if ($S3path != false)
		{
			//See if we have a thumbnail record in our Images table, create or update
			$objThumbImage = Images::model()->findByAttributes(
				array(
					'width' => $intNewWidth,
					'height' => $intNewHeight,
					'index' => $objImage->index,
					'parent' => $objImage->id,
					'product_id' => $objImage->product_id
				)
			);

			if (!($objThumbImage instanceof Images))
			{
				$objThumbImage = new Images();
				Images::model()->deleteAllByAttributes(array(
					'width'=>$intNewWidth,
					'height'=>$intNewHeight,
					'parent'=>$objImage->id)
				); //sanity check to prevent SQL UNIQUE errors
			}

			$objThumbImage->image_path = $S3path;
			$objThumbImage->width = $intNewWidth;
			$objThumbImage->height = $intNewHeight;
			$objThumbImage->parent = $objImage->id;
			$objThumbImage->index = $objImage->index;
			$objThumbImage->product_id = $objImage->product_id;
			$objThumbImage->save();

		}
		@unlink($strNewThumbnailWithPath);
	}

	protected function sendSignal($strSignal,$topicArn)
	{

		Yii::log("Attempting SNS Cloud signal ".$strSignal, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		$sns = new A2Sns();
		$msgId = $sns->publish(array(
			'TopicArn'=>$topicArn,
			'TargetArn'=>$topicArn,
			'Message'=>$strSignal,

		));

		Yii::log("Returned message ID ".$msgId['MessageId'], 'info', 'application.'.__CLASS__.".".__FUNCTION__);

	}



}
