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
$('[name=hybrids_2]').on('change', function() {
    $("select[name=statistics] option").each(function() {
        $(this).remove();
    });
    $.ajax({
            url: '/web/statistics-example',
            type: 'POST',
            data: {hybridID: $(this).val()},
            success: function(res){
                
                $.each(res.response, function(key, value) {
                 $('[name=statistics_2]')
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

let selectFirstTask = $('[name=hybrids_task_1]');
let selectSecondTask = $('[name=hybrids_task_2]');
let responseTask = $('#result_task');
$('[name=hybrids_task_1], [name=hybrids_task_2]').on('change', function() {
    if(selectFirstTask.val() === selectSecondTask.val()) {
         $(responseTask).html('Выберете разные виды');
         return;
    }
    if(selectFirstTask.val().length !== 0
    && selectSecondTask.val().length !== 0) {
        $.ajax({
            url: '/web/statistics-task',
            type: 'POST',
            data: {
                first: selectFirstTask.val(),
                second: selectSecondTask.val(),
                },
            success: function(res){
                $(responseTask).html(res.response)
            },
            error: function(){
                // alert('Error!');
            }
        });
    }
});
JS;

$this->registerJs($js);
?>
<div class="row">
    <div class="col-xs-12">
        <h1 class="text-center">Рябченко тестовое junior PHP developer</h1>
        <h2>Как описано в задании</h2>

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group" style="padding-top: 20px;">
            <?php
            echo Html::label('Выберите первый гибрид кукурузы', 'hybrids');

            echo Html::dropDownList(
                'hybrids_task_1',
                $select,
                ArrayHelper::map($hybrids, 'id', 'Name'),
                [
                    'prompt' => '',
                    'class' => 'form-control',
                ]); ?>

        </div>
        <div class="form-group">
            <?php
            echo Html::label('Выберите второй гибрид кукурузы', 'hybrids');

            echo Html::dropDownList(
                'hybrids_task_2',
                $select,
                ArrayHelper::map($hybrids, 'id', 'Name'),
                [
                    'prompt' => '',
                    'class' => 'form-control',
                ]);
            ActiveForm::end(); ?>
        </div>

        <div>
            <p>Найдено случаев: <span id="result_task" style="font-weight: bold;"></span></p>
        </div>

        <h2>Как было у вас в приложении</h2>
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group" style="padding-top: 20px;">
            <?php
            echo Html::label('Выберите гибрид кукурузы', 'hybrids');

            echo Html::dropDownList(
                'hybrids_2',
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

            echo Html::dropDownList('statistics_2', $select, [], ['class' => 'form-control']);

            ActiveForm::end(); ?>
        </div>
    </div>
</div>