<?php

class glencoeAdminForm extends ThemeForm
{

	/*
	 * Information keys that are used for display in Admin Panel
	 * and other functionality.
	 *
	 * These can all be accessed by Yii::app()->theme->info->keyname
	 *
	 * for example: echo Yii::app()->theme->info->version
	 */
	protected $name = "Glencoe";
	protected $thumbnail = "glencoe.png";
	protected $version = 1;
	protected $description = "Simple yet Sleek.";
	protected $credit = "Designed by LightSpeed";
	protected $parent = "albany"; //Used when a theme is a copy of another theme to control inheritance
	protected $bootstrap = "bootstrap3";


	/*
	 * Define any keys here that should be available for the theme
	 * These can be accessed via Yii::app()->theme->config->keyname
	 *
	 * for example: echo Yii::app()->theme->config->CHILD_THEME
	 *
	 * The values specified here are defaults for your theme
	 *
	 * keys that are in ALL CAPS are written as xlsws_configuration keys as well for
	 * backwards compatibility.
	 *
	 * If you wish to have values that are part of config, but not available to the user (i.e. hardcoded values),
	 * you can add them to this as well. Anything "public" will be saved as part of config, but only
	 * items that are listed in the getAdminForm() function below are available to the user to change
	 *
	 */
	public $CHILD_THEME = "light"; //Required, to be backwards compatible with CHILD_THEME key

	public $CATEGORY_IMAGE_HEIGHT = 180;
	public $CATEGORY_IMAGE_WIDTH = 180;
	public $DETAIL_IMAGE_HEIGHT = 256;
	public $DETAIL_IMAGE_WIDTH = 256;
	public $LISTING_IMAGE_HEIGHT = 190;
	public $LISTING_IMAGE_WIDTH = 180;
	public $MINI_IMAGE_HEIGHT = 30;
	public $MINI_IMAGE_WIDTH = 30;
	public $PREVIEW_IMAGE_HEIGHT = 30;
	public $PREVIEW_IMAGE_WIDTH = 30;
	public $SLIDER_IMAGE_HEIGHT = 90;
	public $SLIDER_IMAGE_WIDTH = 90;
	public $PRODUCTS_PER_PAGE = 12;

	public $disableGridRowDivs = true;
	//public $testvar;

	public $menuposition = "left";


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('CHILD_THEME','required'),
			array('menuposition','safe'),
			//array('testvar','required'), //you can also stack items i.e. array('CHILD_THEME,testvar','required'),
		);
	}


	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'CHILD_THEME'=>ucfirst(_xls_regionalize('color')).' set',
			'menuposition'=>'Products menu position',
		);
	}

	/*
	 * Form definition here
	 *
	 * See http://www.yiiframework.com/doc/guide/1.1/en/form.builder#creating-a-simple-form
	 * for additional information
	 */
	public function getAdminForm()
	{

		return array(
			//'title' => 'Set your funky options for this theme!',

			'elements'=>array(
				'CHILD_THEME'=>array(
					'type'=>'dropdownlist',
					'items'=>array('light'=>'Light'),
				),

//			'menuposition'=>array(
//					'type'=>'dropdownlist',
//					'items'=>array('left'=>'Left side','dropdown'=>'Dropdown menu','menubar'=>'Along menubar'),
//				),

//				'testvar'=>array(
//					'type'=>'text',
//					'maxlength'=>64,
//				),

			),
		);
	}




}