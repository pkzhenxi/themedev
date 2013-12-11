<div id="navigation-main">
    <nav id="top-navbar"  class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-form navbar-right navbar-brand">
                <?php echo $this->renderPartial("/site/_search",array(),true); ?>
            </div>

            <div class="navbar-header">
                <button type="button" class="navbar-toggle btn navbar-btn" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
<!--            <a class="navbar-brand visible-xs" href="#"></a>-->
            <div class="navbar-left collapse navbar-collapse navbar-ex1-collapse">

                <?php $this->widget('zii.widgets.CMenu',array(
                        'htmlOptions'=>array('class'=>'col-sm-offset1 nav navbar-nav navbar-right'),
                        'itemCssClass'=>'',
                        'encodeLabel'=>false,
                        'items'=>CustomPage::model()->toptabs()->findAll()
                    )
                );?>

            </div>
        </div>

    </nav>
</div><!-- /#navigation-main -->