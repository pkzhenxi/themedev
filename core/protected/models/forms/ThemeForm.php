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
	protected $bootstrap = null; // use this value to load Google Fonts for your design, i.e. $GoogleFonts = "Tangerine|Inconsolata|Droid+Sans"

	//Public options (config keys)
	public $CHILD_THEME = "light";
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
	public $IMAGE_BACKGROUND = "#FFFFFF";

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