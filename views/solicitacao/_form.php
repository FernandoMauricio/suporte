<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="solicitacao-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'solic_titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'solic_patrimonio')->textInput() ?>

    <?= $form->field($model, 'solic_desc_equip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'solic_desc_serv')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'solic_unidade_solicitante')->textInput() ?>

    <?= $form->field($model, 'solic_usuario_solicitante')->textInput() ?>

    <?= $form->field($model, 'solic_data_solicitacao')->textInput() ?>

    <?= $form->field($model, 'solic_data_prevista')->textInput() ?>

    <?= $form->field($model, 'solic_data_finalizacao')->textInput() ?>

    <?= $form->field($model, 'solic_usuario_finalizacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'solic_prioridade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'solic_usuario_suporte')->textInput() ?>

    <?= $form->field($model, 'solic_sistema_id')->textInput() ?>

    <?= $form->field($model, 'solic_tipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'situacao_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
