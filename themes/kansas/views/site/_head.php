<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="canonical" href="<?= $this->CanonicalUrl; ?>"/>

    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Allerta' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Josefin+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>

    <link rel="shortcut icon" href="<?= Yii::app()->theme->baseUrl;?>/css/images/purple-cow.png">


    <meta name="description" content="<?= $this->pageDescription; ?>">
    <meta property="og:title" content="<?= $this->pageTitle; ?>"/>
    <meta property="og:description" content="<?= $this->pageDescription; ?>"/>
    <meta property="og:image" content="<?= $this->pageImageUrl; ?>"/>
    <meta property="og:url" content="<?= $this->CanonicalUrl; ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="google-site-verification" content="<?= $this->pageGoogleVerify; ?>"/>
    <?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/style.css'); ?>
    <style type="text/css">

        #footer, .btn-primary:hover, .nav-pills > li.active > a, .nav-pills > li.active > a:hover {
            background-color:<?= Yii::app()->theme->config->FOOTER_COLOUR;?>;
        }

        .carousel-caption a, h1,h2,h3,h4,h5, .h1, .h2,
        .product_cell_label > a, .product_cell_price,
        #pagination > li > a, .btn-link:hover {
            color:<?= Yii::app()->theme->config->FOOTER_COLOUR;?>;
        }

        .btn-primary, .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus, .nav-pills > li.active > a {
            background-color:<?= Yii::app()->theme->config->LINK_COLOUR;?>;
            border-color:<?= Yii::app()->theme->config->LINK_COLOUR;?>
        }

        a, .btn-link, .btn-default, #footer a:hover, .product_cell_price_slash {
            color: <?= Yii::app()->theme->config->LINK_COLOUR;?>
        }

    </style>
    <?php
    /**
     * Created by Shannon Curnew
     * User: lightspeed
     * Date: 9/21/13
     * Time: 12:44 PM
     */

    //    Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/base.css');
//        Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/style.css');
//


    ?>
</head>

