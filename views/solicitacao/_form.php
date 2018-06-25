<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="solicitacao-form">

    <?php $form = ActiveForm::begin(); ?>

<div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Detalhes da Solicitação</h3>
      </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'solic_titulo')->textInput(['maxlength' => true]) ?></div>

            <div class="col-md-2"><?= $form->field($model, 'solic_patrimonio')->textInput(['maxlength' => true]) ?></div>

            <div class="col-md-4"><?= $form->field($model, 'solic_desc_equip')->textInput(['maxlength' => true]) ?></div>
        </div>

        <div class="row">
            <div class="col-md-12"><?= $form->field($model, 'solic_desc_serv')->textarea(['rows' => 6]) ?></div>
        </div>



        


    </div>
</div>

            <?php
                $data_sistemas = ArrayHelper::map($sistemas, 'id', 'sist_descricao');
                echo $form->field($model, 'solic_sistema_id')->widget(Select2::classname(), [
                    'data' =>  $data_sistemas,
                    'options' => ['placeholder' => 'Selecione o Sistema...'],
                    'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); 
            ?>


    <?= $form->field($model, 'solic_unidade_solicitante')->textInput() ?>

    <?= $form->field($model, 'solic_usuario_solicitante')->textInput() ?>

    <?= $form->field($model, 'solic_data_solicitacao')->textInput() ?>

    <?= $form->field($model, 'solic_data_prevista')->textInput() ?>

    <?= $form->field($model, 'solic_data_finalizacao')->textInput() ?>

    <?= $form->field($model, 'solic_prioridade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'solic_usuario_suporte')->textInput() ?>

    <?= $form->field($model, 'solic_tipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'situacao_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
