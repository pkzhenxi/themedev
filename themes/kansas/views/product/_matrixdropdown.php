
<div class="col-sm-3">

    <div class="matrix-menu">
    <?php
        echo CHTML::activeDropDownList($model,'product_size',$model->Sizes,array(
            'id'=>'SelectSize',
            'class'=>'btn btn-default dropdown-toggle col-sm-12',
            'prompt'=>Yii::t('global','Sizes ...'),
            'ajax' => array(
                'type'=>'POST',
                'dataType'=>'json',
                'data' => 'js:{"'.'product_size'.'": $("#SelectSize option:selected").val(),"'.'id'.'": '.$model->id.'}',
                'url'=>CController::createUrl('product/getcolors'),
                'success'=>'js:function(data) {
                    data.product_colors = "<option value=\'\'>'.Yii::t('global','Select {label}...',array('{label}'=>$model->ColorLabel)) .'</option>" + data.product_colors;
                    $("#SelectColor").empty();
                    $("#SelectColor").html(data.product_colors);
                    $("#WishlistAddForm_size").val($("#SelectSize option:selected").val());
                }',
            )));
    ?>
    </div>
</div>
<div class="col-sm-7">
    <div class="matrix-menu">
    <?php
        echo CHTML::activeDropDownList($model,'product_color',array(),array(
            'id'=>'SelectColor',
            'class'=>'btn btn-default dropdown-toggle col-sm-12',
            'prompt'=>Yii::t('global','Colors ...'),
            'ajax' => array(
                'type'=>'POST',
                'dataType'=>'json',
                'data' => 'js:{"'.'product_size'.'": $("#SelectSize option:selected").val(),"'.'product_color'.'": $("#SelectColor option:selected").val(),"'.'id'.'": '.$model->id.'}',
                'url'=>CController::createUrl('product/getmatrixproduct'),
                'success'=>'js:function(data) {
                    $("#' . CHtml::activeId($model,'FormattedPrice') . '").html(data.FormattedPrice);
                    $("#' . CHtml::activeId($model,'FormattedRegularPrice') . '").html(data.FormattedRegularPrice);
                    if (data.FormattedRegularPrice != null) $("#' . CHtml::activeId($model,'FormattedRegularPrice') . '_wrap").show();
                        else $("#' . CHtml::activeId($model,'FormattedRegularPrice') . '_wrap").hide();
                    $("#' . CHtml::activeId($model,'description_long') . '").html(data.description_long);
                    $("#' . CHtml::activeId($model,'image_id') . '").html(data.image_id);
                    $("#' . CHtml::activeId($model,'InventoryDisplay') . '").html(data.InventoryDisplay);
                    $("#' . CHtml::activeId($model,'title') . '").html(data.title);
                    $("#' . CHtml::activeId($model,'code') . '").html(data.code);
                    $("#photos").html(data.photos);
                    if($.isFunction(bindZoom)) bindZoom();
                    $("#WishlistAddForm_color").val($("#SelectColor option:selected").val());
                }',
            )));
    ?>
    </div>
</div>