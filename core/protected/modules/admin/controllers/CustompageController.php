<?php

class CustompageController extends AdminBaseController
{

	public $controllerName = "Custom Pages";

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','add','edit'),
				'roles'=>array('admin'),
			),
		);
	}


	public function beforeAction($action)
	{


		$arrModules =  CustomPage::model()->findAll(array('order'=>'title')); //Get active and inactive

		$menuSidebar = array();

		foreach ($arrModules as $module)
			$menuSidebar[] = array('label'=>$module->title, 'url'=>array('custompage/edit', 'id'=>$module->id));


		$this->menuItems = array_merge(
			array(
				array('label'=>'Edit Pages', 'linkOptions'=>array('class'=>'nav-header'))
			),
			$menuSidebar,
			array(
				array('label'=>'Custom Pages', 'linkOptions'=>array('class'=>'nav-header')),
				array('label'=>'Create new page', 'url'=>array('custompage/add')),
				//array('label'=>'Set active tabs', 'url'=>array('payments/promotasks')),

			)
		);

		//run parent init() after setting menu so highlighting works
		Yii::import('ext.imperavi-redactor-widget.ImperaviRedactorWidget');
		return parent::beforeAction($action);
	}
	public function actionIndex()
	{


		$model = new CustomPage();

		$this->render('index',array('model'=>$model));

	}

	public function actionAdd()
	{


		$model = new CustomPage();
		$this->editSectionName = "Add a new custom page";

		if(isset($_POST['CustomPage']))
		{
			$model->attributes = $_POST['CustomPage'];
			if ($model->validate())
			{
				if (!$model->save())
					Yii::app()->user->setFlash('error',print_r($model->getErrors(),true));
				else
				{
					Yii::app()->user->setFlash('success',Yii::t('admin','Custom page added on {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));
					$this->redirect($this->createUrl("custompage/edit",array('id'=>$model->id)));

				}
			}

		}
		$this->render('edit',array('model'=>$model));

	}
	public function actionEdit()
	{

		$id = Yii::app()->getRequest()->getQuery('id');

		$model = CustomPage::model()->findByPk($id);
		if (!($model instanceof CustomPage))
		{
			Yii::app()->user->setFlash('error',"Invalid Custom Page");
			$this->redirect($this->createUrl("custompage/index"));

		}

		if (_xls_get_conf('LANG_MENU'))
		{
			$langs = _xls_comma_to_array(_xls_get_conf('LANGUAGES'));
			$def = _xls_get_conf('LANG_CODE');

			Yii::log('POST: ' . print_r($_POST,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

			if(isset($_POST['content-'.$def]))
				$_POST['CustomPage']['page']='<'.$def.'>'.$_POST['content-'.$def].'</'.$def.'>';

			foreach ($langs as $i=>$v)
				if(isset($_POST['content-'.substr($v,0,2)]))
				{
					$_POST['CustomPage']['page'].='<'.substr($v,0,2).'>'.$_POST['content-'.substr($v,0,2)].'</'.substr($v,0,2).'>';
				}


		}

		if(isset($_POST['CustomPage']))
		{

			$model->attributes = $_POST['CustomPage'];
			if ($model->validate())
			{
				if ($model->deleteMe)
				{
					$model->delete();
					Yii::app()->user->setFlash('info',"Custom page has been deleted");
					$this->redirect($this->createUrl("custompage/index"));
				}
				else {
					$model->request_url =  _xls_seo_url($model->title);
					if (!$model->save())
						Yii::app()->user->setFlash('error',print_r($model->getErrors(),true));
					else
					{
						Yii::app()->user->setFlash('success',
							Yii::t('admin','Custom page updated on {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));
						$this->beforeAction('edit'); //In case we renamed one and we want to update menu

					}
				}
			}

		}
		$this->render('edit',array('model'=>$model));

	}



}
