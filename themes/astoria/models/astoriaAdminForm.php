<?php

class astoriaAdminForm extends ThemeForm
{

        /*
         * Information keys that are used for display in Admin Panel
         * and other functionality.
         *
         * These can all be accessed by Yii::app()->theme->info->keyname
         *
         * for example: echo Yii::app()->theme->info->version
         */
        protected $name = "Astoria";
        protected $thumbnail = "astoria.png";
        protected $version = 1;
        protected $description = "Astoria Theme. Justt stting here.";
        protected $credit = "Designed by LightSpeed";
        protected $parent; //Used when a theme is a copy of another theme to control inheritance

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
        public $SLIDER_IMAGE_HEIGHT = 200;
        public $SLIDER_IMAGE_WIDTH = 200;
        public $PRODUCTS_PER_PAGE = 12;

        public $disableGridRowDivs = true;
        public $twitter, $facebook, $googleplus, $youtube, $vimeo, $instagram, $pinterest, $rss_url, $about_text;

        public $menuposition = "left";


        /**
         * Declares the validation rules.
         */
        public function rules()
        {
                return array(
                        array('CHILD_THEME','required'),
                        array('menuposition','safe'),
                        array('twitter','safe'),
						array('facebook','safe'),
						array('googleplus','safe'),
                        array('youtube','safe'),
						array('vimeo','safe'),
						array('instagram','safe'),
                        array('pinterest','safe'),
						array('rss_url','safe'),
						array('about_text','safe'),
						 //you can also stack items i.e. array('CHILD_THEME,testvar','required'),
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
						'twitter'=>'Twitter URL',
						'facebook'=>'Facebook URL',
						'googleplus'=>'Google+ URL',
						'youtube'=>'Youtube URL',
						'vimeo'=>'Vimeo URL',
						'instagram'=>'Instagram URL',
						'pinterest'=>'Youtube URL',
						'rss_url'=>'RSS Feed',
						'about_text'=>'Footer About',
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
                        'title' => 'Set your funky options for this theme!',

                        'elements'=>array(
                           


							   

                         'twitter'=>array(
										'type'=>'text',
										'maxlength'=>64,
                              ),
                         'facebook'=>array(
										'type'=>'text',
										'maxlength'=>64,
                              ),
                         'googleplus'=>array(
										'type'=>'text',
										'maxlength'=>64,
                              ),
                         'youtube'=>array(
										'type'=>'text',
										'maxlength'=>64,
                              ),
                         'vimeo'=>array(
										'type'=>'text',
										'maxlength'=>64,
                              ),
                         'instagram'=>array(
										'type'=>'text',
										'maxlength'=>64,
                              ),
                         'pinterest'=>array(
										'type'=>'text',
										'maxlength'=>64,
                              ),
                         'rss_url'=>array(
										'type'=>'text',
										'maxlength'=>64,
                              ),
                         'about_text'=>array(
										'type'=>'textarea',
										'maxlength'=>250,
                              ),

                        ),
                );
        }




}