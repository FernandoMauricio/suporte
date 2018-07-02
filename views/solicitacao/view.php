<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */

$this->title = $model->solic_id;
$this->params['breadcrumbs'][] = ['label' => 'Listagem de Suportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-view">

    <h2><?= 'Suporte: '. Html::encode($this->title) ?></h2>

    <p>
        <?= Html::button('Atualizar', ['value'=> Url::to(['inserir-mensagem', 'id' => $model->solic_id]), 'class' => 'btn btn-primary', 'id'=>'modalButton']) ?>
    </p>

 <?php
    Modal::begin([
        'options' => ['tabindex' => false ], // important for Select2 to work properly
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => true],
        'header' => '<h4>Atualizar</h4>',
        'id' => 'modal',
        'size' => 'modal-lg',
        ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'solic_id',
            'solic_titulo',
            'solic_patrimonio',
            'solic_desc_equip',
            'solic_desc_serv:ntext',

            'unidade.uni_nomeabreviado',

            [
                'label' => 'Solicitante',
                'attribute' => 'usuario.usu_nomeusuario',
            ],

            'solic_data_solicitacao',
            'solic_data_prevista',
            'solic_data_finalizacao',
            'solic_prioridade',

            [
                'label' => 'Técnico Responsável',
                'attribute' => 'tecnico.usu_nomeusuario',
            ],

            [
                'label' => 'Categoria',
                'attribute' => 'categoriaSistema.sist_descricao',
            ], 

            'solic_tipo',
            'situacao.sit_descricao',
        ],
    ]) ?>

    <?= $this->render('forum/view', [
        'modelsForums' => $modelsForums,
    ]) ?>

</div>
