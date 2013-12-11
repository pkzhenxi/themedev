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

      <div id="column2" class="row">
        <div id="sidenav" class="col-sm-3">

        <div id="shopby">
            <h3>Shop By</h3>
            <div class="shopcategories">
                <h4>Category</h4>
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'id'=>'myMenu',
                    'items'=>Category::GetTree(),
                    'htmlOptions'=>array('class'=>'unstyled')
                )); ?>
            </div>
            <?php if(_xls_get_conf('ENABLE_FAMILIES') > 0): ?>
                <div class="shopfamilies ">
                    <h4>Brand</h4>
                    <?php
                    $this->widget('zii.widgets.CMenu', array(
                        'items'=>Family::GetTree(),
                        'htmlOptions'=>array('class'=>'unstyled')
                    ));
                    ?>
                </div>
            <?php endif; ?>
            <?php
//              print_r($this->MenuTree);
//                echo Category::model()->findByPk(33);
//                $subTree = Category::model()->findAllByAttributes(array('parent'=>33));
//                Category::parseTree($subTree);
//                print_r($subTree);
//                foreach($subTree as $subItem) {
//                    print_r($subItem);
//                    echo '<br><br>';
//                }


//    print_r($subTree);
//                Category::parseTree($subTree);
//                $items = Category::GetTree();
//                $items1 = $items['footwearcat'];
//                foreach ($items1 as $subCat){
//                        print_r($subCat);
//                }
//                $items1 = $items['footwearcat'];
//
//                print_r($items);
//                 print_r($items['footwearcat']);
//            $this->widget('zii.widgets.CMenu', array(
//                'items'=>$items1,
//                'htmlOptions'=>array('class'=>'unstyled')
//            ));
//                Category::parseTree($items,33);
//              $this->widget( 'zii.widgets.CMenu', array( 'items' => $this->MenuTree, 'id'=>'menutree' ));

            ?>


        </div>
    </div><!--/span-->



        
        <!-- Include content pages -->
    <div id="product-list" class="col-sm-9">
          <?php echo $content; ?>
    </div>
  </div><!--/row-->
</div>
</div>


<?php $this->endContent(); ?>