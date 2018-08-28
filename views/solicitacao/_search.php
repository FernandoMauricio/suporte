<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\SolicitacaoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="solicitacao-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'solic_id') ?>

    <?= $form->field($model, 'solic_titulo') ?>

    <?= $form->field($model, 'solic_patrimonio') ?>

    <?= $form->field($model, 'solic_desc_equip') ?>

    <?= $form->field($model, 'solic_desc_serv') ?>

    <?php // echo $form->field($model, 'solic_unidade_solicitante') ?>

    <?php // echo $form->field($model, 'solic_usuario_solicitante') ?>

    <?php // echo $form->field($model, 'solic_data_solicitacao') ?>

    <?php // echo $form->field($model, 'solic_data_prevista') ?>

    <?php // echo $form->field($model, 'solic_data_finalizacao') ?>

    <?php // echo $form->field($model, 'solic_prioridade') ?>

    <?php // echo $form->field($model, 'solic_usuario_suporte') ?>

    <?php // echo $form->field($model, 'solic_sistema_id') ?>

    <?php // echo $form->field($model, 'solic_tipo') ?>

    <?php // echo $form->field($model, 'situacao_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
