<?php
/**
 * 
 * Starplugins Cloudzoom
 * http://www.starplugins.com
 *
 * Package license purchased by LightSpeed Retail for distribution purposes
 * Fancybox requires additional license purchase by customer from http://www.fancyapps.com/store/
 * 
 */
class cloudzoom extends CWidget
{

	public $images=array();
	public $instructions = "Hover over image to zoom";
	public $fancyboxLicense;
	public $imageFolder='images';
	public $zoomClass = "cloudzoom";
	public $zoomSizeMode = "lens";
	public $zoomPosition = 3;
	public $zoomFlyOut=true;
	public $zoomOffsetX=0;

	public $css_target='targetarea';
	public $css_thumbs = "thumbs";

    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
		//creating clientScript instance 
	    $clientScript = Yii::app()->clientScript;
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $baseurl = Yii::app()->getAssetManager()->publish($dir . 'assets');
        $js_options = array();
        $assets=$dir.'assets';

        if(is_dir($assets))
        {
            $clientScript->registerCssFile($baseurl.'/cloudzoom.css');
	        $clientScript->registerScriptFile($baseurl.'/cloudzoom.js',CClientScript::POS_HEAD);

	        if(!empty(Yii::app()->params['IMAGE_FANCYBOX']))
	        {
		        $clientScript->registerScriptFile($baseurl.'/jquery.fancybox.pack.js?v=2.1.5',CClientScript::POS_HEAD);
		        $clientScript->registerCssFile($baseurl.'/jquery.fancybox.css?v=2.1.5','screen');
	        }

        }
        else
            throw new Exception(get_class($this).' error: Couldn\'t publish assets.');

	    echo $this->buildInstructions();
	    echo $this->buildImages();

	    $jsCode = <<<SETUP
function bindZoom() {
        CloudZoom.quickStart();
        }

SETUP;



	    $jsCode .= <<<BINDING
bindZoom();
BINDING;

	    if(!empty(Yii::app()->params['IMAGE_FANCYBOX']))
		    $jsCode .= <<<FANCYBOX
        $('#zoomPrimary').bind('click',function(){       // Bind a click event to a Cloud Zoom instance.
            var cloudZoom = $(this).data('CloudZoom');   // On click, get the Cloud Zoom object,
            cloudZoom.closeZoom();                       // Close the zoom window (from 2.1 rev 1211291557)
            $.fancybox.open(cloudZoom.getGalleryList()); // and pass Cloud Zoom's image list to Fancy Box.
            return false;
        });
FANCYBOX;

	    //> register jsCode
	    $clientScript->registerScript(get_class($this), $jsCode, CClientScript::POS_READY);



    }

	/*
	 * If the original image is bigger than our detail size, show the instruction
	 */
	public function buildInstructions()
	{

		echo $this->instructions;
	}

	
    public function buildImages()
	{


		$html='<div class="'.$this->css_target.'">';
		$html.='<img id="zoomPrimary" class="'.$this->zoomClass.'" src="'.$this->images[0]['image'].'"
				data-cloudzoom="zoomImage: \''.$this->images[0]['image_large'].'\',
				zoomSizeMode:\''.$this->zoomSizeMode.'\',
				zoomOffsetX: '.$this->zoomOffsetX.',
				zoomPosition: \''.$this->zoomPosition.'\',
				zoomFlyOut: '.$this->zoomFlyOut.'
				"/>';

		$html .= "</div>";

		if(count($this->images)>1)
		{
			$html .= $this->buildAdditionalImages();
		}



		return $html;


	}

	public function buildAdditionalImages()
	{
		$html='<div class="'.$this->css_thumbs.'">';
		foreach($this->images as $image)
		{
			$html .= '<a href="#" class="cloudzoom-gallery"
    data-cloudzoom =
	    "useZoom: \'#zoomPrimary\', image: \''.$image['image'].'\', zoomImage: \''.$image['image_large'].'\'"
				>'.CHtml::image($image['image_thumb'],$image['image_alt']).'</a>';
		}
		$html .= "</div>";
		return $html;

	}

}
