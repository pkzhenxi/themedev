  <head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">

	<?php
	  $baseUrl = Yii::app()->theme->baseUrl; 
	  $cs = Yii::app()->getClientScript();
	  Yii::app()->clientScript->registerCoreScript('jquery');
	?>
	
    <!-- the styles -->
<!--    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Pontano+Sans'>-->
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo $baseUrl;?><!--/js/nivo-slider/themes/default/default.css" media="screen" />-->
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo $baseUrl;?><!--/js/nivo-slider/nivo-slider.css" >-->
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo $baseUrl;?><!--/js/lightbox/css/lightbox.css" />-->
      <?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/'.Yii::app()->theme->config->CHILD_THEME.'.css'); ?>

      <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/template.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/style.css" />

      <script type="text/javascript" src="<?php echo $baseUrl;?>/js/swfobject/swfobject.js"></script>
	<script type="text/javascript" src="<?php echo $baseUrl;?>/js/lightbox/js/lightbox.js"></script>
    <!-- style switcher -->
    <script type="text/javascript" src="<?php echo $baseUrl;?>/js/styleswitcher.js"></script>
    

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    

    <!-- The fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo $baseUrl;?>/img/ico/bat-icon.jpeg">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $baseUrl;?>/img/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $baseUrl;?>/img/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $baseUrl;?>/img/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo $baseUrl;?>/img/ico/apple-touch-icon-57-precomposed.png">
  </head>
