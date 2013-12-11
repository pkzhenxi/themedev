<div id="bottom">
    <div class="container bottom"> 
    	<div class="row">

            <div class="col-sm-4">
                <?php
                    $content = CustomPage::model()->LoadByKey('about');
                    echo "<h5>" . $content->title .  "</h5>";
                    echo $content->page;
                ?>
            </div><!-- /span3-->
            <div class="col-sm-8">
                <div class="row">
                    <div class="bottomtabs col-sm-4">
                        <?php
                        echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'));
                        foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab)
                            if ($arrTab->page_key != 'about')
                                echo '<br>'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link);
                        ?>
                    </div>

                    <div id="addresshours" class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-6 indentl">
                                <?php
                                echo _xls_get_conf('STORE_NAME')."<br>";
                                echo _xls_get_conf('STORE_ADDRESS1')."<br>";
                                echo _xls_get_conf('STORE_ADDRESS2');
                                ?>
                            </div>
                            <div class="col-sm-6 right indentr">
                                <?php
                                echo _xls_get_conf('STORE_HOURS')."<br>";
                                echo _xls_get_conf('STORE_PHONE')."<br>";
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 right">
                                <?= _xls_get_conf('EMAIL_FROM'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div><!-- /row -->
        </div><!-- /container-->
</section><!-- /bottom-->
