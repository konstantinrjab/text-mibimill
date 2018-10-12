<?php

/* @var $this yii\web\View */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\ActiveForm;

$this->title = 'My Yii Application';
?>
<?php
$js = <<<JS
$('[name=hybrids]').on('change', function() {
    $("select[name=statistics] option").each(function() {
        $(this).remove();
    });
    $.ajax({
            url: '/web/statistics',
            type: 'POST',
            data: {hybridID: $(this).val()},
            success: function(res){
                
                $.each(res.response, function(key, value) {
                 $('[name=statistics]')
                     .append($("<option></option>")
                                .attr("value",key)
                                .text(value)); 
                });
            },
            error: function(){
                // alert('Error!');
            }
        });
});
JS;

$this->registerJs($js);
?>

<div class="row">
    <div class="col">
        <h1 class="text-center">Рябченко тестовое junior PHP developer</h1>
        <h2>Как было у вас в приложении</h2>
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group" style="padding-top: 40px;">
            <?php
            echo Html::label('Выберите гибрид кукурузы', 'hybrids');

            echo Html::dropDownList(
                'hybrids',
                $select,
                ArrayHelper::map($hybrids, 'id', 'Name'),
                [
                    'prompt' => '',
                    'class' => 'form-control',
                ]); ?>

        </div>
        <div class="form-group">
            <?php
            echo Html::label('Количество случаев произростания вместе с другими гибридами', 'hybrids');

            echo Html::dropDownList('statistics', $select, [], ['class' => 'form-control']);

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>