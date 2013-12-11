<?php

class austinAdminForm extends ThemeForm
{

	/*
	 * Information keys that are used for display in Admin Panel
	 * and other functionality.
	 *
	 * These can all be accessed by Yii::app()->theme->info->keyname
	 *
	 * for example: echo Yii::app()->theme->info->version
	 */
	protected $name = "Austin";
	protected $thumbnail = "austin.png";
	protected $version = 3;
	protected $description = "Keep Austin weird.";
	protected $credit = "Designed by LightSpeed";
	protected $parent; //Used when a theme is a copy of another theme to control inheritance
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
	public $CHILD_THEME = "pink"; //Required, to be backwards compatible with CHILD_THEME key

	public $CATEGORY_IMAGE_HEIGHT = 180;
	public $CATEGORY_IMAGE_WIDTH = 180;
	public $DETAIL_IMAGE_HEIGHT = 320;
	public $DETAIL_IMAGE_WIDTH = 320;
	public $LISTING_IMAGE_HEIGHT = 190;
	public $LISTING_IMAGE_WIDTH = 180;
	public $MINI_IMAGE_HEIGHT = 100;
	public $MINI_IMAGE_WIDTH = 100;
	public $PREVIEW_IMAGE_HEIGHT = 100;
	public $PREVIEW_IMAGE_WIDTH = 100;
	public $SLIDER_IMAGE_HEIGHT = 90;
	public $SLIDER_IMAGE_WIDTH = 90;
	public $PRODUCTS_PER_PAGE = 12;

	public $disableGridRowDivs = true;
	public $NUM_TEASER_SENTENCES = 2;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('CHILD_THEME','required'),
            array('NUM_TEASER_SENTENCES','numerical',
                    'integerOnly'=>true,
                    'min'=>0,
                    'max'=>25,
                    'tooSmall'=>'You cannot enter a negative number.',
                    'tooBig'=>'Maximum 25 sentences.'),
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
            'NUM_TEASER_SENTENCES'=>'Number of sentences from Web Long Description to display in each product grid entry.'
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
					'items'=>array('pink'=>'Pink', 'green'=>'Green', 'chestnut'=>'Chestnut'),
				),
                'NUM_TEASER_SENTENCES'=>array(
                    'type'=>'text',
                    'maxlength'=>'2',
                    'value'=>'2',
                )

			),
		);
	}




}