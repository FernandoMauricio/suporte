<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */
/* @var $form yii\widgets\ActiveForm */
$session = Yii::$app->session;
?>

<div class="solicitacao-form">

<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Detalhes da Solicitação</h3>
    </div>

    <div class="panel-body">
        <div class="row">
            <!-- Técnicos da GTI poderão abrir chamados para outros usuários-->
            <?php $data_unidades = ArrayHelper::map($unidades, 'uni_codunidade', 'uni_nomeabreviado'); ?>
            <?= $session['sess_codunidade'] == 1 ? 
                '<div class="col-md-6">'
                    .$form->field($model, 'solic_unidade_solicitante')->widget(Select2::classname(), [
                        'data' =>  $data_unidades,
                        'options' => ['placeholder' => 'Selecione a Unidade...'],
                        'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]). '</div>' 
                : '' ?>
            <!-- Técnicos da GTI poderão abrir chamados para outros usuários-->
            <?php $data_usuarios = ArrayHelper::map($usuarios, 'usu_codusuario', 'usu_nomeusuario'); ?>
            <?= $session['sess_codunidade'] == 1 ? 
                '<div class="col-md-6">'
                    .$form->field($model, 'solic_usuario_solicitante')->widget(Select2::classname(), [
                        'data' =>  $data_usuarios,
                        'options' => ['placeholder' => 'Selecione o Usuário...'],
                        'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]). '</div>' 
                : '' ?>

            <div class="col-md-6"><?= $form->field($model, 'solic_titulo')->textInput(['maxlength' => true]) ?></div>

            <?= $model->solic_tipo == 'Equipamentos' ? '<div class="col-md-2">' .$form->field($model, 'solic_patrimonio')->textInput(['maxlength' => true]). '</div>' : '' ?>

            <?= $model->solic_tipo == 'Equipamentos' ? '<div class="col-md-4">' .$form->field($model, 'solic_desc_equip')->textInput(['maxlength' => true]). '</div>' : '' ?>

            <?php $data_sistemas = ArrayHelper::map($sistemas, 'id', 'sist_descricao'); ?>
            <?= $model->solic_tipo == 'Sistemas' ?
                '<div class="col-md-6">'
                    .$form->field($model, 'solic_sistema_id')->widget(Select2::classname(), [
                        'data' =>  $data_sistemas,
                        'options' => ['placeholder' => 'Selecione o Sistema...'],
                        'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]). '</div>' 
                :  '';
                ?>

        </div>

        <div class="row">
            <div class="col-md-12"><?= $form->field($model, 'solic_desc_serv')->textarea(['rows' => 6]) ?></div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'file[]')->widget(FileInput::classname(), [
                        'options' => ['multiple' => true],
                        'pluginOptions' => [
                            'language' => 'pt-BR',
                            'showRemove'=> false,
                            'showUpload'=> false,
                            'dropZoneEnabled' => false,
                        ],
                    ]);
                ?>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-12">
            </div>
        </div>

    </div>
</div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
