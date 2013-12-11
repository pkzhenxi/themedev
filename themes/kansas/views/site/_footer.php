<div id="footer">
    <div id="footer-content" class="container">
    	<div class="row">
            <div class="col-sm-4">
                <?php
                    $content = CustomPage::model()->LoadByKey('about');
                    echo "<h5>" . $content->title .  "</h5>";
                    echo $content->page;
                ?>
            </div><!-- /span3-->
            <div class="col-sm-2">
                <div id="order-lookup" class="row">
                    <a data-toggle="modal" href="#orderModal" class="btn btn-link">Lookup Order</a>
                    <div class="modal fade" id="orderModal">
                        <div id="order-dialog" class="modal-dialog">
                            <div class="modal-content">
                                <?php $this->widget("application.extensions.wsborderlookup.wsborderlookup",array()); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="wishlist-link" class="row">
<!--                    <a data-toggle="modal" href="#wishlistModal" class="btn btn-link">Lookup Order</a>-->
<!--                    <div class="modal fade" id="wishlistModal">-->
<!--                        <div id="wishlist-dialog" class="modal-dialog">-->
<!--                            <div class="modal-content">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <?php if(_xls_get_conf('ENABLE_WISH_LIST')):
                        //if(Yii::app()->user->isGuest):
                        echo CHtml::link(Yii::t('global','Find a Wish List'),Yii::app()->createUrl('wishlist/search'),array('class'=>'btn btn-link'));
                    endif;
                    ?>
                </div>
            </div>

            <div id="bottomtabs" class="col-sm-offset-2 col-sm-4">
                <?php
                echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'),array('class'=>'btn btn-link'));
                foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab)
                    if ($arrTab->page_key != 'about')
                        echo '<br>'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link,array('class'=>'btn btn-link'));
                ?>
            </div>
        </div>
    </div><!-- /row -->
</div><!-- /container-->
