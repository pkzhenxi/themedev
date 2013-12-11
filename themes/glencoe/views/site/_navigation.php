<!--Left menu-->
<div class="col-xs-6 col-sm-3 sidebar-offcanvas">
<?php
        echo '<ul class="nav list-group">';
        foreach ($this->MenuTree as $cat) {
            if ($cat['hasChildren']) {
                echo '<li class="list-group-item-heading">';
                echo '<a href="'.$cat['link'].'">'.$cat['label'].'</a>';
                echo '<ul class="nav nav-list cat1" ';
                if (strpos($this->CanonicalUrl,$cat['link']))
                    echo '>';
                else if (strpos(Yii::app()->getRequest()->getUrl(),'brand') && ($cat['label']==_xls_get_conf('ENABLE_FAMILIES_MENU_LABEL')))
                        echo '>';
                     else echo 'style="display:none">';
                foreach ($cat['children'] as $cat1) {
                    if ($cat1['hasChildren']) {
                        echo '<li class="list-group-item-heading">';
                        echo '<a href="'.$cat1['link'].'">'.$cat1['label'].'</a>';
                        echo '<ul class="nav nav-list cat2"';
                        if (strpos($this->CanonicalUrl,$cat1['link']))
                            echo '>';
                        else echo 'style="display:none">';
                        foreach ($cat1['children'] as $cat2)
                        {
                            echo '<li class="list-group-item">';
                            echo '<a href="'.$cat2['link'].'">'.$cat2['label'].'</a>'.'</li>';
                        }
                        echo '</ul>';
                    }
                    else {
                        echo '<li class="list-group-item">';
                        echo $cat1['text'].'</li>';
                    }
                }
                echo '</ul>';
            }
            else {
                echo '<li class="list-group-item-heading">';
                echo $cat['text'].'</li>';
            }
        }
        echo '</ul>';?>
	<ul class="nav list-group">

		<?php if(_xls_get_conf('ENABLE_WISH_LIST'))
			echo $this->renderPartial('/site/_wishlists',array(),true); ?>

<!--		--><?php //echo $this->renderPartial('/site/_orderlookup',array(),true); ?>

	</ul>
</div>

<div class="col-xs-12 col-sm-9">
    <p class="pull-left visible-xs">
        <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
    </p>

    <!-- Custom pages -->
    <div class="row">
        <ul class="nav-pills nav">
            <?php
            foreach (CustomPage::model()->toptabs()->findAll() as $arrTab)
                echo '<li>'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link).'</li>'; ?>
        </ul>
    </div>

    <!-- Breadcrumbs -->
    <div class="row">
        <div class="breadcrumb">
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
            'homeLink'=>CHtml::link(Yii::t('global','Home'), array('/site/index')),
            'separator'=>' / ',
        ));	?>
        </div>
    </div>

    <!-- flash messages -->
    <div class="row">
        <?= $this->renderPartial('/site/_flashmessages',null, true); ?>
    </div>

