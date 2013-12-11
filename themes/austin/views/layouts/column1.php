<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="main-body">
    <div class="container">
        <div class="row">
            <?php if(isset($this->breadcrumbs)):?>
                <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
                'homeLink'=>CHtml::link('Home'),
                'htmlOptions'=>array('class'=>'breadcrumb'),
                'separator'=>' // '
                )); ?><!-- breadcrumbs -->
            <?php endif?>
        </div>
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
	</div>
</div>
<?php $this->endContent(); ?>