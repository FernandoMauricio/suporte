<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;


/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\SolicitacaoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="solicitacao-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index-adm'],
        'method' => 'get',
    ]); ?>

<div class="panel-body">
    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'solic_patrimonio') ?></div>

        <div class="col-md-2">
            <?php
                echo '<label class="control-label">Período da Solicitação</label>';
                echo DateRangePicker::widget([
                'model'=>$model,
                'attribute'=>'solic_data_solicitacao',
                'convertFormat'=>true,
                'startAttribute'=>'date_min',
                'endAttribute'=>'date_max',
                'pluginOptions'=>[
                    'locale'=>[
                        'format'=>'Y-m-d'
                    ]
                ]
                ]);
            ?>
        </div>
    </div>
</div>


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
        <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Limpar', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
