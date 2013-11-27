<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $menuwidget;

	protected $_canonicalUrl;

	public $LoadSharing=0;

	/* These are public variables that are used in our layout, so we have to define them.
	*/
	public $pageDescription;
	public $pageCanonicalUrl;
	public $pageImageUrl;
	public $pageHeader;
	public $pageHeaderImage;
	public $pageGoogleVerify;
	public $pageGoogleFonts;
	public $lnkNameLogout;
	public $sharingHeader;
	public $sharingFooter;
	public $logoutUrl;

	public $arrSidebars;

	/* These are partial renders of pieces of the web page to display */
	public $searchPnl;

	public $gridProductsPerRow = 3;
	public $gridProductsRows;
    public $custom_page_content;


	public function beforeAction($action)
	{

		//For all other actions, we're not supposed to be using shared ssl
		if( Yii::app()->controller->id != "site" &&
			Yii::app()->controller->id != "cart" &&
			Yii::app()->controller->id != "myaccount" &&
			$action->id != "login" &&
			_xls_get_conf('LIGHTSPEED_HOSTING','0') == '1' &&
			_xls_get_conf('LIGHTSPEED_HOSTING_SHARED_SSL','0') == '1'
		)
			$this->verifyNoSharedSSL();

		return parent::beforeAction($action);

	}

	/**
	 * Dynamically load the configuration settings for the client and
	 * establish Params to make everything faster
	 */
	public static function initParams()
	{
		defined('DEFAULT_THEME') or define('DEFAULT_THEME','brooklyn');

		$Params = CHtml::listData(Configuration::model()->findAll(),'key_name','key_value');

		foreach($Params as $key=>$value)
			Yii::app()->params->add($key, $value);

		if(isset(Yii::app()->params['THEME']))
			Yii::app()->theme=Yii::app()->params['THEME'];
		else Yii::app()->theme=DEFAULT_THEME;
		if(isset(Yii::app()->params['LANG_CODE']))
			Yii::app()->language=Yii::app()->params['LANG_CODE'];
		else Yii::app()->language = "en";
		Yii::app()->params->add('listPerPage',Yii::app()->params['PRODUCTS_PER_PAGE']);

		//Based on logging setting, set log level dynamically and possibly turn on debug mode
		switch (Yii::app()->params['DEBUG_LOGGING'])
		{

			case 'info':   $logLevel = "error,warning,info";break;
			case 'trace':  $logLevel = "error,warning,info,trace";
				defined('YII_DEBUG') or define('YII_DEBUG',true);
				defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
				break;
			case 'error':  default: $logLevel = "error,warning"; break;

		}

		foreach( Yii::app()->getComponent('log')->routes as $route)
			$route->levels = $logLevel;

		Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-cities");


	}
	/**
	 * Load anything we need globally, such as items we're going to use in our main.php template.
	 * If you create init() in any other controller, you need to run parent::init() too or this
	 * will be skipped. If you run your own init() and don't call this, you must call Controller::initParams();
	 * or nothing will work.
	 */
	public function init()
	{
		self::initParams();

		$filename = Yii::getPathOfAlias('webroot.themes').DIRECTORY_SEPARATOR.DEFAULT_THEME;
		if(!file_exists($filename) && _xls_get_conf('LIGHTSPEED_MT',0)=='0')
		{
			if(!downloadTheme(DEFAULT_THEME))
				die("missing ".DEFAULT_THEME);
			else
				$this->redirect("/");
		}
		if(!Yii::app()->theme)
		{
			if(_xls_get_conf('theme'))
			{
				//We can't find our theme for some reason, switch back to default
				_xls_set_conf('theme',DEFAULT_THEME);
				_xls_set_conf('CHILD_THEME','light');
				Yii::log("Couldn't find our theme, switched back to ".DEFAULT_THEME." for emergency",
					'error', 'application.'.__CLASS__.".".__FUNCTION__);
				$this->redirect("/");

			} else
				die("you have no theme set");
		}

		$this->buildBootstrap();

		if (Yii::app()->params['STORE_OFFLINE']>0 || Yii::app()->params['INSTALLED'] != '1')
		{
			if (isset($_GET['offline']))
				Yii::app()->session['STORE_OFFLINE'] = _xls_number_only($_GET['offline']);

			if (Yii::app()->session['STORE_OFFLINE'] != Yii::app()->params['STORE_OFFLINE'] || Yii::app()->params['INSTALLED'] != '1')
			{
				$this->render('/site/offline');
				Yii::app()->end();
			}
		}

		$this->logoutUrl = $this->createUrl("site/logout");

		$strViewset = Yii::app()->theme->info->viewset;
		if(!empty($strViewset)) Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-".$strViewset);



		if ( Yii::app()->theme && file_exists('webroot.themes.'.Yii::app()->theme->name.'.layouts.column2'))
			$this->layout='webroot.themes.'.Yii::app()->theme->name.'.layouts.column2';



		// filter out garbage requests
		$uri = Yii::app()->request->requestUri;
		if (strpos($uri, 'favicon') || strpos($uri, 'robot'))
			Yii::app()->end();

		//Set defaults
		$this->getUserLanguage();

		$this->pageTitle =
			Yii::app()->name =  _xls_get_conf('STORE_NAME', 'LightSpeed Web Store')." : ".
			_xls_get_conf('STORE_TAGLINE');
		$this->pageCanonicalUrl = $this->getCanonicalUrl();
		$this->pageDescription = _xls_get_conf('STORE_TAGLINE');
		$this->pageImageUrl ='';

		$this->pageHeaderImage = CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), array('site/index'));



		try {
			$this->lnkNameLogout = CHtml::link(CHtml::image(Yii::app()->baseUrl."css/images/loginhead.png").
				Yii::app()->user->name, array('myaccount/pg'));
		}
		catch(Exception $e) {
			Yii::log("Site failure, has Web Store been set up? Error: " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			echo ("Site failure, has Web Store been set up?<P>");
			Yii::app()->end();
		}

		Yii::app()->shoppingcart->UpdateMissingProducts();
		Yii::app()->shoppingcart->RevalidatePromoCode();

		//Run other functions to create some data we always need
		$this->buildGoogle();
		$this->buildSidebars();
		if (_xls_get_conf('SHOW_SHARING',0))
			$this->buildSharing();

		$this->gridProductsPerRow = _xls_get_conf('PRODUCTS_PER_ROW',3);

		if(_xls_facebook_login())
			$this->getFacebookLogin();

		Yii::app()->clientScript->registerMetaTag(
			"LightSpeed Web Store ".XLSWS_VERSION,'generator',null,array(),'generator');
	}

	/**
	 * Default canonical url generator, will remove all get params beside 'id' and generates an absolute url.
	 * If the canonical url was already set in a child controller, it will be taken instead.
	 */
	public function getCanonicalUrl() {
		if ($this->_canonicalUrl === null) {
			$params = array();
			if (isset($_GET['id'])) {
				//just keep the id, because it identifies our model pages
				$params = array('id' => $_GET['id']);
			}
			$this->_canonicalUrl = Yii::app()->createAbsoluteUrl($this->route, $params);
		}
		return $this->_canonicalUrl;
	}


	/**
	 * Override URL if needed
	 */
	public function setCanonicalUrl($strUrl) {
		$this->_canonicalUrl = $strUrl;
	}


	protected function getUserLanguage()
	{

		$app = Yii::app();

		if (isset($_POST['_lang']))
		{
			$app->language = $_POST['_lang'];
			$app->session['_lang'] = $app->language;
		}
		elseif (isset($_GET['_lang']))
		{
			$app->language = $_GET['_lang'];
			$app->session['_lang'] = $app->language;
		}
		else if (isset($app->session['_lang']))
		{
			$app->language = $app->session['_lang'];
		}
		else
		{
			// 'fr_FR' becomes 'fr'
			$app->language = substr(Yii::app()->getRequest()->getPreferredLanguage(),0,2);
			$app->session['_lang'] = substr(Yii::app()->getRequest()->getPreferredLanguage(),0,2);
		}
	}


	/**
	 * buildGoogle - Reads data needed for various Google services
	 * @param none
	 * @return none
	 */
	protected function buildGoogle() {

		$this->pageGoogleVerify = _xls_get_conf('GOOGLE_VERIFY');
		$this->pageGoogleFonts = _xls_get_conf('GOOGLE_FONTS_LINK');
		if (Yii::app()->theme->info->GoogleFonts)
			$this->pageGoogleFonts .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.
				Yii::app()->theme->info->GoogleFonts.'">';

		$this->pageGoogleFonts = str_replace("http://","//",$this->pageGoogleFonts);
	}

	/**
	 * Read the sidebars from our modules table into an array for use in our main.php template
	 * @param none
	 * @return none
	 */
	protected function buildSidebars() {

		$this->arrSidebars = Modules::getSidebars();

	}

	protected function buildSharing() {
		$this->sharingHeader = $this->renderPartial('/site/_sharing_header',null,true);
		$this->sharingFooter = $this->renderPartial('/site/_sharing_footer',null,true);


	}

	protected function buildBootstrap()
	{

		Yii::setPathOfAlias('bootstrap',null);
		$strBootstrap = Yii::app()->theme->info->bootstrap;

		if(!isset($strBootstrap)) {
			Yii::app()->setComponent('bootstrap',array(
				'class'=>'ext.bootstrap.components.Bootstrap',
				'responsiveCss'=>true,
			));
			Yii::setPathOfAlias('bootstrap', dirname(__FILE__).DIRECTORY_SEPARATOR.'../extensions/bootstrap');
			Yii::app()->bootstrap->init();
		}
		elseif(!empty($strBootstrap)) {
			Yii::setPathOfAlias('bootstrap',
				dirname(__FILE__).DIRECTORY_SEPARATOR.'../extensions/'.Yii::app()->theme->info->bootstrap);
			Yii::app()->setComponent('bootstrap',array(
				'class'=>'ext.'.Yii::app()->theme->info->bootstrap.'.components.Bootstrap'
			));
			Yii::app()->bootstrap->init();
		}


	}


	protected function getloginDialog() {
		/* This is our modal login dialog box */
		if (Yii::app()->user->isGuest)
		{
			$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
				'id'=>'LoginForm',
				'options'=>array(
					'title'=>Yii::t('global','Login'),
					'autoOpen'=>false,
					'modal'=>'true',
					'width'=>'350',
					'height'=>'365',
					'resizable'=>false,
					'position'=>'center',
					'draggable'=>false,
				),
			));

			$this->renderPartial('/site/_login',array('model'=>new LoginForm()));
			$this->endWidget('zii.widgets.jui.CJuiDialog');
		}

	}

	protected function getFacebookLogin()
	{

		//Facebook integration
		$fbArray = require(YiiBase::getPathOfAlias('application.config').'/_wsfacebook.php');
		$fbArray['appId']=Yii::app()->params['FACEBOOK_APPID'];
		$fbArray['secret']=Yii::app()->params['FACEBOOK_SECRET'];
		Yii::app()->setComponent('facebook',$fbArray);


		if (Yii::app()->user->isGuest)
		{
			$userid = Yii::app()->facebook->getUser();

			if ($userid>0)
			{
				$results = Yii::app()->facebook->api('/'.$userid);
				if(!isset($results['email']))
				{
					//we've lost our authentication, user may have revoked
					Yii::app()->facebook->destroySession();
					$this->redirect(array("/site"));
				}
				$identity=new FBIdentity($results['email'],$userid); //we user userid in the password field
				$identity->authenticate();
				if($identity->errorCode===UserIdentity::ERROR_NONE)
				{
					Yii::app()->user->login($identity,0);
					$this->redirect(array("/site"));
				}
			}
		}

		if(isset(Yii::app()->user->facebook))
			if(Yii::app()->user->facebook)
				$this->logoutUrl =  Yii::app()->facebook->getLogoutUrl();

	}

	public function setReturnUrl()
	{
		Yii::app()->session['returnUrl'] = $this->CanonicalUrl;
	}
	public function getReturnUrl()
	{
		return Yii::app()->session['returnUrl'];
	}


	/**
	 * Cycle through Product model for page and mark beginning and end of each row. Used for <div row> formatting in
	 * the view layer.
	 * @param $model Product
	 * @return $model
	 */
	protected function createBookends($model)
	{
		if(count($model)==0 || Yii::app()->theme->config->disableGridRowDivs) return $model;

		$ct=-1;
		$next = 0;
		foreach ($model as $item)
		{
			$ct++;
			if ($ct==0) $model[$ct]->rowBookendFront=true;
			if ($next==1) { $model[$ct]->rowBookendFront=true; $next=0; }
			if ((1+$ct) % $this->gridProductsPerRow == 0) { $model[$ct]->rowBookendBack=true; $next=1; }
		}
		$model[count($model)-1]->rowBookendBack=true; //Last item must always close div
		return $model;
	}


	protected function afterRender($view, &$output) {
		parent::afterRender($view,$output);
		//Yii::app()->facebook->addJsCallback($js); // use this if you are registering any $js code you want to run asyc


		if (_xls_facebook_login())
			Yii::app()->facebook->initJs($output); // this initializes the Facebook JS SDK on all pages
		//Yii::app()->facebook->renderOGMetaTags(); //we don't need this because it was already in our _main.php
		return true;
	}


	public function getCategories()
	{
		return array(
			array('label'=>'Home', 'url'=>array('/site/index')),
			array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
			array('label'=>'Contact', 'url'=>array('/site/contact')),
			array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
			array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
		);

	}


	public function getMenuTree()
	{
		$objTree = Category::GetTree() + CustomPage::GetTree();
		ksort($objTree);

		if(_xls_get_conf('ENABLE_FAMILIES', 0)>0)
		{

			$families = Family::GetTree();
			$familyMenu[0] = array(
				'text'=>Yii::app()->params['ENABLE_FAMILIES_MENU_LABEL'],
				'label'=>Yii::app()->params['ENABLE_FAMILIES_MENU_LABEL'],
				'link'=>$this->createUrl("search/browse",array('brand'=>'*')),
				'url'=>$this->createUrl("search/browse",array('brand'=>'*')),
				'id'=>0,
				'child_count'=>count($families),
				'hasChildren'=>1,
				'children'=>$families);
			switch(_xls_get_conf('ENABLE_FAMILIES', 0))
			{


				case 3: $objFullTree = $families  + $objTree; ksort($objFullTree); break; //blended
				case 2: $objFullTree = $familyMenu + $objTree; break; //on top
				case 1: $objFullTree = $objTree + $familyMenu; break; //onbottom

			}

		} else $objFullTree = $objTree;

		//if(_xls_get_conf('ENABLE_FAMILIES', 0)==2) $objTree .= Family::GetTree();

		return $objFullTree;
	}


	/*
	 * Shared SSL Functionality
	 */
	protected function verifySharedSSL()
	{
		if(_xls_get_conf('LIGHTSPEED_HOSTING_SHARED_SSL') != '1')
			throw new CHttpException(404,'The requested page does not exist.');

		if($_SERVER['HTTP_HOST'] != _xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL'))
		{
			$userID = Yii::app()->user->id;
			$cartID = Yii::app()->shoppingcart->id;
			$controller = Yii::app()->controller->id;
			$action = Yii::app()->controller->action->id;

			if(empty($userID)) $userID=0;
			$strIdentity = $userID.",".$cartID.",".$controller.",".$action;

			$redirString = _xls_encrypt($strIdentity);
			$strFullUrl = "https://"._xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL').
				$this->createUrl("cart/sharedsslreceive",array('link'=>$redirString));

			$this->render('/site/redirect',array('url'=>$strFullUrl));
			Yii::app()->end();
		}

	}

	protected function verifyNoSharedSSL()
	{
		if(_xls_get_conf('LIGHTSPEED_HOSTING_SHARED_SSL') != '1')
			throw new CHttpException(404,'The requested page does not exist.');

		//If we're here, it means we're using a shared SSL and we should go back to our own domain
		if($_SERVER['HTTP_HOST'] == _xls_get_conf('LIGHTSPEED_HOSTING_SSL_URL'))
		{
			$userID = Yii::app()->user->id;
			$cartID = Yii::app()->shoppingcart->id;
			$uri = $_SERVER['REQUEST_URI'];


			if(empty($userID)) $userID=0;
			$strIdentity = $userID.",".$cartID.",".$uri;

			$redirString = _xls_encrypt($strIdentity);
			$strFullUrl = "http://"._xls_get_conf('LIGHTSPEED_HOSTING_ORIGINAL_URL').
				$this->createUrl("cart/sharednosslreceive",array('link'=>$redirString));

			$this->redirect($strFullUrl);

			//$this->render('/site/redirect',array('url'=>$strFullUrl));
			Yii::app()->end();
		}

	}

	public function actionSharedSSLReceive()
	{

		if(_xls_get_conf('LIGHTSPEED_HOSTING','0') != '1' || _xls_get_conf('LIGHTSPEED_HOSTING_SHARED_SSL') != '1')
			throw new CHttpException(404,'The requested page does not exist.');

		$strLink = Yii::app()->getRequest()->getQuery('link');

		$link = _xls_decrypt($strLink);
		$linka = explode(",",$link);
		if($linka[0]>0)
		{
			//we were logged in on the other URL so re-login here
			$objCustomer = Customer::model()->findByPk($linka[0]);
			$identity=new UserIdentity($objCustomer->email,_xls_decrypt($objCustomer->password));
			$identity->authenticate();
			if($identity->errorCode==UserIdentity::ERROR_NONE)
				Yii::app()->user->login($identity,3600*24*30);
			else
				Yii::log("Error attempting to switch to shared SSL and logging in, error ".
					$identity->errorCode, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		Yii::app()->user->setState('cartid',$linka[1]);
		Yii::app()->user->setState('sharedssl','1');
		$this->redirect($this->createUrl($linka[2]."/".$linka[3]));

	}

	public function actionSharedNoSSLReceive()
	{

		if(_xls_get_conf('LIGHTSPEED_HOSTING','0') != '1' || _xls_get_conf('LIGHTSPEED_HOSTING_SHARED_SSL') != '1')
			throw new CHttpException(404,'The requested page does not exist.');

		$strLink = Yii::app()->getRequest()->getQuery('link');

		$link = _xls_decrypt($strLink);
		$linka = explode(",",$link);
		if($linka[0]>0)
		{
			//we were logged in on the other URL so re-login here
			$objCustomer = Customer::model()->findByPk($linka[0]);
			$identity=new UserIdentity($objCustomer->email,_xls_decrypt($objCustomer->password));
			$identity->authenticate();
			if($identity->errorCode==UserIdentity::ERROR_NONE)
				Yii::app()->user->login($identity,3600*24*30);
			else
				Yii::log("Error attempting to switch to from shared SSL, error ".
					$identity->errorCode, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		Yii::app()->user->setState('cartid',$linka[1]);
		Yii::app()->user->setState('sharedssl','0');
		if($linka[2]=="/")
			$this->redirect("http://"._xls_get_conf('LIGHTSPEED_HOSTING_ORIGINAL_URL'));
		else
			$this->redirect($this->createUrl($linka[2]));

	}

}
