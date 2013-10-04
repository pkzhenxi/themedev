<?php

class ThemeController extends AdminBaseController
{
	public $controllerName = "Themes";
	public $currentTheme;
	const THEME_PHOTOS = 29;

	public function actions()
	{
		return array(
			'edit'=>'admin.edit',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','edit','gallery','image','header',
					'customcss','favicon','manage','upload','upgrade','module'),
				'roles'=>array('admin'),
			),
		);
	}

	public function beforeAction($action)
	{

		$this->scanModules('theme');

		if(Yii::app()->theme)
		{
			$this->currentTheme = Yii::app()->theme->name;
			if(Theme::hasAdminForm($this->currentTheme))
			{
				$model = Yii::app()->getComponent('wstheme')->getAdminModel($this->currentTheme);
				$this->currentTheme = $model->name;
			}

		}
		else
			$this->currentTheme = "unknown";

		$this->menuItems =
			array(
				array('label'=>'Manage My Themes', 'url'=>array('theme/manage')),
				array('label'=>'Configure '.ucfirst($this->currentTheme), 'url'=>array('theme/module')),
				array('label'=>'Edit custom.css for '.ucfirst($this->currentTheme), 'url'=>array('theme/customcss')),
				array('label'=>'View Theme Gallery', 'url'=>array('theme/gallery')),
				array('label'=>'Upload Theme .Zip', 'url'=>array('theme/upload')),
				array('label'=>'View My Image Gallery', 'url'=>array('theme/image','id'=>1)),
				array('label'=>'Set Header Image', 'url'=>array('theme/header')),
				array('label'=>'Upload FavIcon', 'url'=>array('theme/favicon'))


			);

		//run parent beforeAction() after setting menu so highlighting works
		return parent::beforeAction($action);

	}


	public function getInstructions($id)
	{
		switch($id)
		{

			case self::THEME_PHOTOS:
				return "Note that these settings are used as photos are uploaded from LightSpeed. These sizes are saved for each theme.";
		}
	}


	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionManage()
	{
		//Get list
		$arrThemes = $this->getInstalledThemes();

		if (isset($_POST['theme']))
		{
			if (isset($_POST['yt2']) && $_POST['yt2']=="btnClean")
			{
				$arrThemes = $this->changeTheme($_POST);
				$arrThemes = $this->cleanTheme($_POST);

			}

			if (isset($_POST['yt1']) && $_POST['yt1']=="btnCopy")
			{
				$arrThemes = $this->changeTheme($_POST);
				$arrThemes = $this->copyTheme($_POST);

			}

			if (isset($_POST['yt0']) && $_POST['yt0']=="btnSet")
			{
				$arrThemes = $this->changeTheme($_POST);
			}

			if (isset($_POST['task']) && $_POST['task']=="btnTrash")
			{

				if($_POST['theme']==Yii::app()->theme->name)
				{
					$strTheme =Yii::app()->theme->name;
					Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! You cannot trash your currently active theme.'));
				}
				else
				{
					$this->trashTheme($_POST['theme']);
					$objModule = Modules::model()->findByAttributes(array('module'=>$_POST['theme'],'category'=>'theme'));
					if($objModule) $objModule->delete();
					$arrThemes = $this->getInstalledThemes();
					Yii::app()->user->setFlash('info',Yii::t('admin','Theme {theme} has been moved to /trash on server.',array('{theme}'=>$_POST['theme'])));
				}

			}

		}

		if(isset(Yii::app()->theme))
			$strTheme = Yii::app()->theme->name;
		else $strTheme='';

		Yii::app()->clientScript->registerScript('picking', '
			var picked = "'.$strTheme.'";
		',CClientScript::POS_BEGIN);

		$this->render('manage',array('arrThemes'=>$arrThemes,'currentTheme'=>$strTheme));
	}


	public function actionCustomcss()
	{
		$this->editSectionInstructions = "The <strong>custom.css</strong> file acts as an override for CSS formatting for your theme. Because it is loaded last, elements here will take priority. Custom.css also survives upgrades, so as the theme is updated in Admin Panel, your customizations here will be carried along.";

		if(isset(Yii::app()->theme))
			$strTheme = Yii::app()->theme->name;
		else $this->redirect($this->createUrl("themes/manage"));

		$model = new CustomPage();
		$page =YiiBase::getPathOfAlias('webroot')."/themes/".$strTheme."/css/custom.css";
		if(isset($_POST['CustomPage']))
		{
			$model->attributes = $_POST['CustomPage'];

			$d = YiiBase::getPathOfAlias('webroot')."/themes";
			@mkdir($d."/trash");
			$strTrash = $d."/trash/".date("YmdHis")."custom.css";

			$css = file_get_contents($page);
			if(!$css)
			{}
			else
				file_put_contents($strTrash,$css);

			file_put_contents($page,$model->page);

		}
		 else {
			 $css = file_get_contents($page);
			 if($css === false || empty($css)) $css="";


			 $model->page = $css;
		 }

		$this->render('customcss',array('model'=>$model));

	}

	public function actionGallery()
	{
		//Get list
		$arrThemes = $this->GalleryThemes;

		if (isset($_POST) && !empty($_POST))
		{
			foreach($_POST as $key=>$value)
			{
				$strTheme = $key;
				if ($value=="update")
					$this->actionUpgrade($strTheme);
				if ($value=="install")
				{
					$blnExtract = $this->downloadTheme($arrThemes,$strTheme);
					if($blnExtract)
					{
						Yii::app()->user->setFlash('success',Yii::t('admin','The {file} theme was downloaded and installed at {time}.',
							array('{file}'=>"<strong>".$strTheme."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
						unlink(YiiBase::getPathOfAlias('webroot')."/themes/".$strTheme.".zip");
						$this->redirect($this->createUrl("theme/manage"));
					}
					else Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! Theme {file} installation failed. {time}.',
						array('{file}'=>$strTheme,'{time}'=>date("d F, Y  h:i:sa"))));
				}
			}
		}

		Yii::app()->clientScript->registerScript('picking', '
			var picked = "'.$this->currentTheme.'";
		',CClientScript::POS_BEGIN);

		$this->render('gallery',array('arrThemes'=>$arrThemes,'currentTheme'=>$this->currentTheme));
	}

	protected function downloadTheme($arrThemes,$strTheme)
	{
		$d = YiiBase::getPathOfAlias('webroot')."/themes";

		$path = $arrThemes[$strTheme]['installfile'];
		$data = $this->getFile($path);
		$f=file_put_contents($d."/".$strTheme.".zip", $data);

		if ($f)
		{
			$blnExtract = $this->unzipFile($d,$strTheme.".zip");
			return $blnExtract;

		}
		else return false;
	}

	/**
	 * Download a new version of a template and trash the old one
	 */
	protected function actionUpgrade($strTheme)
	{

		/*
		 * Steps for updating template

			create trash folder if doesn't exist
			rename old folder to trashtimestamp i.e. portland becomes 201307150916-trash-portland
			move to /themes/trash
			download new template and unzip

			if we have modified old custom.css
				copy /themes/trash/201307150916-trash-portland/css/custom.css to new /themes/portland/css/custom.css (since it's always blank)
		 */

		$arrThemes = $this->GalleryThemes;
		$realName=$arrThemes[$strTheme]['title'];

		$strTrash = $this->trashTheme($realName);

		//Now that the old version is in the trash, we can grab the new version normally
		$arrThemes = $this->GalleryThemes;
		$blnExtract = $this->downloadTheme($arrThemes,$strTheme);
		if($blnExtract)
		{
			//New copy downloaded and extracted. Copy any custom.css and site/index.php
			$d = YiiBase::getPathOfAlias('webroot')."/themes";
			@copy ($strTrash."/css/custom.css", $d."/".$realName."/css/custom.css");
			@copy ($strTrash."/views/site/index.php", $d."/".$realName."/views/site/index.php");
			Yii::app()->user->setFlash('success',
					Yii::t('admin','The {file} theme was updated to the latest version at {time}. Any custom.css and site/index.php file changes were preserved. The old template was moved to a /themes/trash folder on the server in case of a severe problem.',
					array('{file}'=>"<strong>".$arrThemes[$strTheme]['name']."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
			unlink(YiiBase::getPathOfAlias('webroot')."/themes/".$strTheme.".zip");

			$objCurrentSettings = Modules::model()->findByAttributes(array(
				'module'=>$realName,
				'category'=>'theme'));

			if ($objCurrentSettings) {
				$objCurrentSettings->version =$arrThemes[$strTheme]['version'];
				$objCurrentSettings->save();
			}

			$this->redirect($this->createUrl("theme/gallery"));
		}
	}


	protected function trashTheme($strName)
	{
		$symbolic_link=false;

		$d = YiiBase::getPathOfAlias('webroot')."/themes";
		@mkdir($d."/trash");
		$strTrash = $d."/trash/".date("YmdHis").$strName;

		//If this is a symbolic link, we have to handle this differently
		if(is_link($d."/".$strName))
			$symbolic_link=true;

		if($symbolic_link)
		{
			$oldpath = readlink($d."/".$strName);
			symlink($oldpath,$strTrash);
			unlink($d."/".$strName);

		} else {
			rcopy($d."/".$strName,$strTrash);
			rrmdir($d."/".$strName);
		}
		return $strTrash;
	}

	public function actionUpload()
	{
		if (isset($_POST['yt0']))
		{
			$file = CUploadedFile::getInstanceByName('theme_file');
			if ($file->type == "application/zip")
			{
				$path = str_replace("/core/protected","",Yii::app()->basePath); //Since we're inside admin panel, bump up one folder
				$retVal = $file->saveAs($path.'/themes/'.$file->name);
				if ($retVal)
				{
					$blnExtract = $this->unzipFile($path.'/themes',$file->name);
					Yii::app()->user->setFlash('success',Yii::t('admin','File {file} uploaded at {time}.',
						array('{file}'=>"<strong>".$file->name."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
				}
				else
					Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! File {file} was not saved. {time}.',
						array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
			}
			else Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! Only .zip files can be uploaded through this method. {time}.',
				array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
		}
		$this->render('upload');
	}


	/**
	 * Manage user uploaded images
	 */
	public function actionImage()
	{

		$id = Yii::app()->getRequest()->getQuery('id');

		$this->render('image',array('gallery'=>Gallery::LoadGallery($id)));
	}

	public function actionHeader()
	{

		//Get list
		$arrHeaderImages = $this->getImageFiles('header');

		if (isset($_POST['yt0']))
		{


			$file = CUploadedFile::getInstanceByName('header_image');
			if ($file)
			{

					if ($file->type == "image/jpg" || $file->type == "image/png" || $file->type == "image/jpeg")
					{
						$path = str_replace("/core/protected","/images/header/",Yii::app()->basePath);
						$retVal = $file->saveAs($path.$file->name);
						if ($retVal)
						{
							_xls_set_conf('HEADER_IMAGE',"/images/header/".$file->name);
							Yii::app()->user->setFlash('success',Yii::t('admin','File {file} uploaded and chosen at {time}.',
								array('{file}'=>"<strong>".$file->name."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
						}
						else
							Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! File {file} was not saved. {time}.',
								array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
					}
					else Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! Only png or jpg files can be uploaded through this method. {time}.',
						array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
				$arrHeaderImages = $this->getImageFiles('header');
			} elseif (isset($_POST['headerimage']))
			{
				_xls_set_conf('HEADER_IMAGE',$_POST['headerimage']);
				Yii::app()->user->setFlash('success',Yii::t('admin','Header image updated at {time}.',
					array('{time}'=>date("d F, Y  h:i:sa"))));

			}
		}
		$this->render('header',array('arrHeaderImages'=>$arrHeaderImages));
	}

	public function actionFavicon()
	{

		if (isset($_POST['yt0']))
		{


			$file = CUploadedFile::getInstanceByName('icon_image');
			if ($file)
			{

					if ($file->type == "image/jpg" || $file->type == "image/png" || $file->type == "image/jpeg" || $file->type == "image/gif" ||
						$file->type == 'image/vnd.microsoft.icon' || $file->type == "image/x-icon")
					{
						$path = str_replace("/core/protected","/images/",Yii::app()->basePath);
						$retVal = $file->saveAs($path."favicon.ico");
						$path2 = str_replace("/images/","/",$path);

						if ($retVal)
						{
							copy($path."favicon.ico",$path2."favicon.ico");//save in root too, just because of stupid crawlers
							Yii::app()->user->setFlash('success',Yii::t('admin','File {file} uploaded and chosen at {time}.',
								array('{file}'=>"<strong>favicon.ico</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
						}
						else
							Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! File {file} was not saved. {time}.',
								array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
					}
					else Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! Only icon files can be uploaded through this method. {time}.',
						array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));

			}
		}
		$this->render('favicon');
	}

	protected function getImageFiles($type = 'header')
	{
		$arrImages = array();
		$d = dir(YiiBase::getPathOfAlias('webroot')."/images/".$type);
		while (false!== ($filename = $d->read()))
			if ($filename[0] != ".") $arrImages["/images/".$type."/".$filename] = CHtml::image(Yii::app()->request->baseUrl."/images/".$type."/".$filename);
		$d->close();
		return $arrImages;
	}

	protected function unzipFile($path,$file)
	{
		$path = str_replace("/core/protected","/themes",Yii::app()->basePath);
		require_once( YiiBase::getPathOfAlias('application.components'). '/zip.php');

		extractZip($file,'',$path);

		return true;
	}

	protected function changeTheme($post)
	{
		if (_xls_get_conf('THEME') != $post['theme'])
		{
			//we're going to swap out template information

			//Get (or create) Module entry for this theme.
			//If outgoing theme does not have an Admin Form,
			if(!Theme::hasAdminForm(_xls_get_conf('THEME')))
			{
				$objCurrentSettings = Modules::model()->findByAttributes(array(
					'module'=>_xls_get_conf('THEME'),
					'category'=>'theme'));

				if (!$objCurrentSettings) {
					$objCurrentSettings = new Modules;
					$objCurrentSettings->active = 1;
				}

				$objCurrentSettings->module = _xls_get_conf('THEME');
				$objCurrentSettings->category = 'theme';

				$arrDimensions = array();
				$arrItems = Configuration::model()->findAllByAttributes(array('template_specific'=>1));
				foreach ($arrItems as $objConf)
					$arrDimensions[$objConf->key_name] = $objConf->key_value;


				$objCurrentSettings->configuration = serialize($arrDimensions);
				if (!$objCurrentSettings->save())
					Yii::log("Error on switching old theme ".print_r($objCurrentSettings->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

				unset($objCurrentSettings);

			}


			//Now that we've saved the current settings, see if there are new ones to load
			$objCurrentSettings = Modules::model()->findByAttributes(array(
				'module'=>$post['theme'],
				'category'=>'theme'));

			list($themeDefaults,$themeVersion) = $this->loadDefaults($post['theme']);
			$themeVersion = round($themeVersion,PHP_ROUND_HALF_DOWN);

			if ($objCurrentSettings)
			{
				//We found settings, load them
				$arrDimensions = unserialize($objCurrentSettings->configuration);
				if(is_array($arrDimensions))
				{
					foreach($arrDimensions as $key=>$val)
						_xls_set_conf($key,$val);
				}

				//Make sure our version number is up to date
				$objCurrentSettings->version = $themeVersion;
				if (!$objCurrentSettings->save())
					Yii::log("Error on switching themes ".print_r($objCurrentSettings->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
			else
			{
				//Create entry in our modules table
				$objCurrentSettings = new Modules;
				$objCurrentSettings->module = $post['theme'];
				$objCurrentSettings->category = 'theme';
				$objCurrentSettings->configuration = serialize($themeDefaults);
				$objCurrentSettings->version = $themeVersion;
				$objCurrentSettings->active = 1; //we use this for autochecking
				if (!$objCurrentSettings->save())
					Yii::log("Error on new module entry when switching themes ".
						print_r($objCurrentSettings->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

			}


		}


		_xls_set_conf('THEME',$post['theme']);
		Yii::app()->theme = $post['theme'];


		if (isset($post['subtheme-'.$post['theme']]))
			$child = $post['subtheme-'.$post['theme']];
		else
		{
			$child = "";
			$arrOptions = $this->buildSubThemes($post['theme']);
			if ($arrOptions)
			{
				$keys = array_keys($arrOptions);
				$child = array_shift($keys);
			}


		}
		_xls_set_conf('CHILD_THEME',$child);


		Yii::app()->user->setFlash('success',Yii::t('admin','Theme set as "{theme}" at {time}.',
			array('{theme}'=>ucfirst(Yii::app()->theme->name),'{time}'=>date("d F, Y  h:i:sa"))));
		$arrThemes = $this->getInstalledThemes();
		$this->beforeAction('manage');

		return $arrThemes;
	}

	protected function loadDefaults($strTheme)
	{
		$arrKeys = array();

		$objComponent = Yii::app()->getComponent('wstheme');
		$model = $objComponent->getAdminModel($strTheme);
		if($model) {
			$formname = $strTheme."AdminForm";
			$arrKeys = get_class_vars($formname);
			$form = new $formname;
			$themeVersion = $form->version;
		}
		else
		{

			//If we don't have a CForm definition, we have to go old school
			//(that means look for config.xml for backwards compatibility)
			$fnOptions = self::getConfigFile($strTheme);
			if (file_exists($fnOptions))
			{
				$strXml = file_get_contents($fnOptions);

				// Parse xml for response values
				$oXML = new SimpleXMLElement($strXml);

				if($oXML->defaults) {
					foreach ($oXML->defaults->{'configuration'} as $item)
					{
						$keyname = (string)$item->key_name;
						$keyvalue = (string)$item->key_value;

						$arrKeys[$keyname] = $keyvalue;
					}
				}
				$themeVersion = $oXML->version;
			}
		}

		//Now we have an array of keys no matter which method
		foreach($arrKeys as $keyname=>$keyvalue)
		{
			$objKey = Configuration::model()->findByAttributes(array('key_name'=>$keyname));
			if ($objKey) {
				_xls_set_conf($keyname,$keyvalue);
				Configuration::model()->updateByPk($objKey->id,array('template_specific'=>'1'));
			}
		}
		return array($arrKeys,$themeVersion);
	}

	protected function copyTheme($post)
	{

		//To create a complete copy, we need to copy our viewset first, and then the theme in use over it so we get it all
		//Later on, the cleanup will strip out anything unused
		$original = Yii::app()->theme->name;
		$tcopy = $original."copy";
		$tcopyname = ucfirst($original)." Copy";

		if(file_exists("themes/$tcopy"))
		{Yii::app()->user->setFlash('error',Yii::t('admin','Theme {theme} already exists, cannot create new copy',
			array('{theme}'=>ucfirst($tcopy),'{time}'=>date("d F, Y  h:i:sa"))));

			return $this->changeTheme($post);
		}


		recurse_copy("themes/$original","themes/$tcopy");
		recurse_copy("core/protected/views","themes/$tcopy/views");
		recurse_copy("themes/$original","themes/$tcopy");

		//Copy Admin Panel information
		if(Theme::hasAdminForm($original))
		{

			$strXml = file_get_contents("themes/$tcopy/models/".$original."AdminForm.php");
			$strXml = preg_replace('/class (.*)AdminForm extends/', 'class '.$tcopy."AdminForm extends", $strXml);
			$strXml = preg_replace('/\$name = \"(.*)\";/', '$name = "'.$tcopyname.'";', $strXml);
			$strXml = preg_replace('/\$parent;/', '$parent = "'.$original.'";', $strXml);

			file_put_contents("themes/$tcopy/models/".$tcopy."AdminForm.php",$strXml);
			unlink("themes/$tcopy/models/".$original."AdminForm.php");

		} else {

			$fnOptions = self::getConfigFile($tcopy);

			if (file_exists($fnOptions)) {
				$strXml = file_get_contents($fnOptions);
				$oXML = new SimpleXMLElement($strXml);
				$strXml = str_replace("<name>".$oXML->name."</name>","<name>".$oXML->name."copy</name>",$strXml);
				file_put_contents($fnOptions,$strXml);
			}

		}


		$this->getInstalledThemes();
		$this->beforeAction('manage');

		$post['theme'] = $tcopy;

		Yii::app()->user->setFlash('warning',Yii::t('admin','{theme} created!',
			array('{theme}'=>$tcopyname,'{time}'=>date("d F, Y  h:i:sa"))));

		return $this->changeTheme($post);

	}


	protected function cleanTheme($post)
	{

		//Compare files in core views with files in our theme, and remove any theme files that match
		//to let the master files bleed through
		$original = Yii::app()->theme->name;
		$arrConfig = $this->loadConfiguration($original);

		if (isset($arrConfig['parent']) && !is_null($arrConfig['parent']))
		{

			$fileArray = $this->getFilesFromDir("core/protected/views");
			$ct=0;
			foreach($fileArray as $filename)
			{
				if ($filename != "core/protected/views/site/index.php")
				{
					$localthemefile = str_replace("core/protected/","themes/$original/",$filename);
					if(file_exists($localthemefile) && md5_file($filename)==md5_file($localthemefile))
					{
						unlink($localthemefile);
						$ct++;
					}
				}
			}
			$path = YiiBase::getPathOfAlias('webroot')."/themes/".$original."/views";
			RemoveEmptySubFolders($path);

			Yii::app()->user->setFlash('warning',Yii::t('admin','{fcount} files were unmodified from the original and have been cleared out of {theme}',
				array('{fcount}'=>$ct,'{theme}'=>ucfirst($original),'{time}'=>date("d F, Y  h:i:sa"))));

			return $this->changeTheme($post);
		}
		else
		{
			{
				Yii::app()->user->setFlash('error',Yii::t('admin','Clean can only be applied to a copy of a theme. {theme} was not modified.',
					array('{theme}'=>ucfirst($original),'{time}'=>date("d F, Y  h:i:sa"))));
				return $this->changeTheme($post);

			}
		}
	}

	protected function getInstalledThemes()
	{
		$arr = array();
		$strThemePath = YiiBase::getPathOfAlias('webroot')."/themes";
		$d = dir($strThemePath);
		while (false !== ($filename = $d->read()))
		{
			if (is_dir($strThemePath."/".$filename) && $filename[0] != "." && $filename != "trash")
				$arr[$filename] = $this->loadConfiguration($filename);

		}
		$d->close();

		if(isset(Yii::app()->theme))
			$strTheme = Yii::app()->theme->name;
		else $strTheme='';

		if (isset($arr[$strTheme]))
		{
			$hold[$strTheme] = $arr[$strTheme];
			unset($arr[$strTheme]);
			$newarray = $hold + $arr;
			$arr = $newarray;

		}

		return $arr;
	}

	protected function loadConfiguration($strThemeName)
	{
		//New style, Admin Form
		if(Theme::hasAdminForm($strThemeName))
		{
			$model = Yii::app()->getComponent('wstheme')->getAdminModel($strThemeName);

			return array('name'=>$model->name,
				'version'=>'v'.$model->version,
				'img'=> CHtml::image(Yii::app()->createUrl("themes/".$strThemeName."/".$model->thumbnail),$model->name),
				'parent'=> $model->parent,
				'options'=>CHtml::link(Yii::t('global','Click to configure'),   "module")
			);
		}
		else
			return $this->loadConfigXML($strThemeName);//Old style, xml


	}






	/*
	 * Backwards compatibility if AdminForm does not exist
	 */
	protected function loadConfigXML($strThemeName)
	{
		$arr = array('name'=>ucfirst($strThemeName),'version'=>'','img'=>CHtml::image(Yii::app()->createUrl('images/no_product.png'),"missing"),'options'=>'');
		$fnOptions = self::getConfigFile($strThemeName);
		if (file_exists($fnOptions)) {

			$strXml = file_get_contents($fnOptions);
			$oXML = new SimpleXMLElement($strXml);
			$imagepath =  CHtml::image(Yii::app()->createUrl("themes/".strtolower($oXML->name)."/".$oXML->thumbnail),$oXML->name);

			$arr['name'] = $oXML->name;
			if(substr( $oXML->name,-4)=="copy")
				$arr['parent'] = "yes";
			else $arr['parent'] = null;
			$arr['version'] = 'v'.$oXML->version;
			$arr['img'] = $imagepath;
			$arr['options'] =
				CHtml::dropDownList(
					"subtheme-".strtolower($oXML->name),
					_xls_get_conf('CHILD_THEME'),
					$this->buildSubThemes($strThemeName)
				);
		}
		return $arr;

	}



	protected function getGalleryThemes()
	{

		$postVar = "";
		$objCurrentSettings = Modules::model()->findAllByAttributes(array(
			'category'=>'theme'));
		foreach($objCurrentSettings as $item)
			$postVar[] = array($item->module,$item->version);


		$arr = array();
		$strXml = $this->getFile("http://updater.lightspeedretail.com/webstore/themes",array('version'=>XLSWS_VERSIONBUILD,'themes'=>$postVar));
		//$strXml = $this->getFile("http://www.lsvercheck.site/webstore/themes",array('version'=>XLSWS_VERSIONBUILD,'themes'=>$postVar));
		if (stripos($strXml,"404 Not Found")>0 || stripos($strXml,"An internal error")>0 || empty($strXml))
			return $arr;

		$oXML = new SimpleXMLElement($strXml);

		foreach ($oXML->themes->theme as $item)
		{

			$filename = mb_pathinfo($item->installfile,PATHINFO_BASENAME);
			$filename = str_replace(".zip","",$filename);
			$arr[$filename]['img'] = CHtml::image($item->thumbnail, $item->name);
			$arr[$filename]['title'] = strtolower($item->name);
			$arr[$filename]['name'] = $item->name;
			$arr[$filename]['version'] = $item->version;
			$arr[$filename]['installfile'] = $item->installfile;
			$arr[$filename]['releasedate'] = strtotime($item->releasedate);
			$arr[$filename]['description'] = $item->description;
			$arr[$filename]['credit'] = $item->credit;
			$arr[$filename]['md5'] = $item->md5;
			$arr[$filename]['options'] = "";
			$arr[$filename]['newver'] = $item->newver;
		}
		return $arr;


	}


	protected function buildThemeChooser($oXML)
	{

		$retVal = CHtml::image(Yii::app()->createUrl("themes/".strtolower($oXML->name)."/".$oXML->thumbnail),
			$oXML->name);

		return $retVal;

	}

	protected function buildSubThemes($filename)
	{
		$fnOptions = self::getConfigFile($filename);
		$arr = array();

		if (file_exists($fnOptions)) {
			$strXml = file_get_contents($fnOptions);

			// Parse xml for response values
			$oXML = new SimpleXMLElement($strXml); //print_r($oXML);
			if($oXML->subthemes) {
				foreach ($oXML->subthemes->subtheme as $item)
					$arr[(string)$item->css] = (string)$item->name;
			} else $arr['webstore']="n/a";
		} else $arr['webstore']="config.xml missing";

		return $arr;

	}

	protected function getFile($url,$postVars = null)
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		if(!is_null($postVars))
		{
			$json = json_encode($postVars);
			curl_setopt($ch, CURLOPT_HTTPHEADER,
				array("Content-type: application/json"));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

		}

		$resp = curl_exec($ch);
		curl_close($ch);
		return $resp;


	}

	public static function getConfig($theme)
	{
		$fnOptions = YiiBase::getPathOfAlias('webroot')."/themes/".$theme."/config.xml";

		if (file_exists($fnOptions))
		{
			$strXml = file_get_contents($fnOptions);
			return new SimpleXMLElement($strXml);
		} else return null;

	}


	protected static function getConfigFile($filename)
	{
		return YiiBase::getPathOfAlias('webroot')."/themes/".$filename."/config.xml";
	}

	protected function getFilesFromDir($dir)
	{

		$files = array();
		if ($dir != "./.git") if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if(is_dir($dir.'/'.$file)) {
						$dir2 = $dir.'/'.$file;
						$files[] = $this->getFilesFromDir($dir2);
					}
					else
						if ($file != ".DS_Store")
							$files[] = $dir.'/'.$file;
				}
			}
			closedir($handle);
		}

		return $this->array_flat($files);
	}

	protected function array_flat($array)
	{
		$tmp=array();
		foreach($array as $a) {
			if(is_array($a)) {
				$tmp = array_merge($tmp, $this->array_flat($a));
			}
			else {
				$tmp[] = $a;
			}
		}

		return $tmp;
	}

}