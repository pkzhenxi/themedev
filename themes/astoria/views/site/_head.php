<head>
<meta charset="utf-8">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>

<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

<link rel="canonical" href="<?= $this->CanonicalUrl; ?>"/>
<meta name="description" content="<?= $this->pageDescription; ?>">
<meta property="og:title" content="<?= $this->pageTitle; ?>"/>
<meta property="og:description" content="<?= $this->pageDescription; ?>"/>
<meta property="og:image" content="<?= $this->pageImageUrl; ?>"/>
<meta property="og:url" content="<?= $this->CanonicalUrl; ?>"/>
<meta name="google-site-verification" content="<?= $this->pageGoogleVerify; ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="Shortcut Icon" href="<?=Yii::app()->baseUrl."/images/favicon.ico" ?>" type="image/x-icon"/>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
    <![endif]-->

<!-- Le fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?=Yii::app()->baseUrl."/ico/apple-touch-icon-144-precomposed.png" ?>">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=Yii::app()->baseUrl."/ico/apple-touch-icon-114-precomposed.png" ?>">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=Yii::app()->baseUrl."/ico/apple-touch-icon-72-precomposed.png" ?>">
<link rel="apple-touch-icon-precomposed" href="<?=Yii::app()->baseUrl."/ico/apple-touch-icon-57-precomposed.png" ?>">
<link rel="shortcut icon" href="<?=Yii::app()->baseUrl."/ico/favicon.png" ?>">
<link href='http://fonts.googleapis.com/css?family=Raleway:400,100,300,600,700' rel='stylesheet' type='text/css'>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->cssUrl('base')); ?>
<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->cssUrl('style')); ?>
<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->cssUrl(Yii::app()->theme->config->CHILD_THEME)); ?>
<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->cssUrl(Yii::app()->theme->config->CHILD_THEME.'-x')); ?>
<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->cssUrl('custom')); ?>
<?php echo $this->renderPartial("/site/_google",null,true); ?>
</head>
