<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Forum */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="forum-form">

<?php $form = ActiveForm::begin(); ?>

<?php echo $form->errorSummary($forum); ?> 

    <div class="row">
        <div class="col-md-4">
        	<?php $data_situacao = ArrayHelper::map($situacao, 'id', 'sit_descricao');
        		echo $form->field($forum, 'situacao_id')->widget(Select2::classname(), [
	                'data' =>  $data_situacao,
	                'options' => ['placeholder' => 'Alterar situação... '],
	                'pluginOptions' => [
	                    'allowClear' => true
	                ],
	            ]); 
            ?>
        </div>

        <div class="col-md-4">
        	<?= $form->field($forum, 'for_usuario_suporte')->widget(Select2::classname(), [
	                'data' =>  [219 => 'Endrio Medeiros', 129 => 'Fernando Mauricio',  94 => 'Laércio Varela', 205 => 'Mackson Silva', 369 => 'Rafael Cunha'],
	                'options' => ['placeholder' => 'Alterar técnico... '],
	                'pluginOptions' => [
	                    'allowClear' => true
	                ],
	            ]); 
            ?>
        </div>

        <div class="col-md-2">
        	<?= $form->field($forum, 'for_data_prevista')->widget(DateControl::classname(), [
                    'type'=>DateControl::FORMAT_DATE,
                    'ajaxConversion'=>false,
                    'widgetOptions' => [
                        'removeButton' => false,
                        'pluginOptions' => [
                            'autoclose' => true
                        ],
                    ]
                ]); 
            ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12"><?= $form->field($forum, 'for_mensagem')->textarea(['rows' => 6]) ?></div>
    </div>

    <div class="form-group">
    	<?= Html::submitButton($forum->isNewRecord ? 'Enviar Mensagem' : 'Enviar Mensagem', ['class' => $forum->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

<?php ActiveForm::end(); ?>

</div>
