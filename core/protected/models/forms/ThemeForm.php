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

	//Public options (config keys)
	public $CHILD_THEME = "light";
	/*
	 * ATTENTION THEME DESIGNERS: These values below are NOT live, they are defaults. If you are experimenting
	 * and wish to change these values to see the effect, after changing them here, go into Admin Panel, under
	 * the Configuration panel for your theme, and click Save. This will write these values to the
	 * xlsws_module table for your themes, which is where Web Store looks for them at runtime.
	 */
	public $CATEGORY_IMAGE_HEIGHT = 180;
	public $CATEGORY_IMAGE_WIDTH = 180;
	public $DETAIL_IMAGE_HEIGHT = 256;
	public $DETAIL_IMAGE_WIDTH = 256;
	public $LISTING_IMAGE_HEIGHT = 0;
	public $LISTING_IMAGE_WIDTH = 0;
	public $MINI_IMAGE_HEIGHT = 30;
	public $MINI_IMAGE_WIDTH = 30;
	public $PREVIEW_IMAGE_HEIGHT = 30;
	public $PREVIEW_IMAGE_WIDTH = 30;
	public $SLIDER_IMAGE_HEIGHT = 90;
	public $SLIDER_IMAGE_WIDTH = 90;
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