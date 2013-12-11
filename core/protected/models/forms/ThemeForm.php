<?php
class ThemeForm extends CFormModel
{
	protected $viewset = "cities2";
	protected $name = "Default";
	protected $thumbnail = "";
	protected $version = "0.0.0";
	protected $description = "";
	protected $noupdate = false;
	protected $credit = "Designed by LightSpeed";
	protected $parent; //Used when a theme is a copy of another theme to control inheritance
	protected $versionCheckUrl = ""; //for future use
	protected $GoogleFonts; // use this value to load Google Fonts for your design, i.e. $GoogleFonts = "Tangerine|Inconsolata|Droid+Sans"
	protected $bootstrap = null; // use this value to load new bootstrap i.e. $bootstrap = "bootstrap3";
	protected $cssfiles = "base,style";

	/*
	 * IMAGE SIZES
	 */
	protected $CATEGORY_IMAGE_HEIGHT = 180;
	protected $CATEGORY_IMAGE_WIDTH = 180;
	protected $DETAIL_IMAGE_WIDTH = 256; //Image size used on product detail page
	protected $DETAIL_IMAGE_HEIGHT = 256;
	protected $LISTING_IMAGE_WIDTH = 180; //Image size used on grid view
	protected $LISTING_IMAGE_HEIGHT = 190;
	protected $MINI_IMAGE_WIDTH = 30; //Image size used in shopping cart
	protected $MINI_IMAGE_HEIGHT = 30;
	protected $PREVIEW_IMAGE_WIDTH = 30;
	protected $PREVIEW_IMAGE_HEIGHT = 30;
	protected $SLIDER_IMAGE_WIDTH = 90; //Image used on a slider appearing on a custom page
	protected $SLIDER_IMAGE_HEIGHT = 90;

	protected $GRID_IMAGE_WIDTH = 180; //Deprecated, here for backwards compatibility
	protected $GRID_IMAGE_HEIGHT = 190;

	//Public options (config keys)
	public $CHILD_THEME = "light";
	public $PRODUCTS_PER_PAGE = 12;

	public $customcss = array();


	public $menuposition = "left";

	//Public options (additional framework settings)
	public $disableGridRowDivs = false;

	public function __get($name)
	{
		$vars = get_class_vars(get_class($this));
		if(array_key_exists($name,$vars))
			return $this->$name;
		else
			try {
				return parent::__get($name);
			}
			catch(Exception $e) {
				return null;
			}

	}
}