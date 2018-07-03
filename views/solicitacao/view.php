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
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Detalhes do Suporte</h3>
        </div>
        <table class="table table-condensed table-hover">
            <thead>
                <tr class="info"><th colspan="12" style="text-align: center"><i class="glyphicon glyphicon-file"></i> Informações</th></tr>
            </thead>
        </table>
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

            [
                'attribute' => 'solic_prioridade',
                'format' => 'raw',
                'value' => $model->solic_prioridade == 'Normal' ? '<span class="label label-success" style="font-size:90%">'.$model->solic_prioridade.'</span>' : '<span class="label label-danger" style="font-size:90%">'.$model->solic_prioridade.'</span>',
            ],

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

        <table class="table table-condensed table-hover">
            <thead>
                <tr class="info"><th colspan="12" style="text-align: center"><i class="glyphicon glyphicon-folder-close"></i> Histórico de Mensagens</th></tr>
            </thead>
        </table>
        <?php foreach ($modelsForums as $forum): ?>
        <div class="panel-body">
            <div class="row">
                <div class="well">
                    <b>Atualizado Por: </b> <?= $forum->solicitacao->usuario->usu_nomeusuario ?> - <b>Feita em:</b> <?= date('d/m/Y à\s H:i', strtotime($forum->for_data)); ?><hr style="border-top: 1px solid #c1c1c1; margin-top: 5px">

                    <ul>
                        <?= !empty($forum->tecnicoForum->usu_nomeusuario) ? '<li><b>Técnico Responsável <span style="color: #d35400">Atribuído para: </b></span>' .ucwords(strtolower($forum->tecnicoForum->usu_nomeusuario)) : ''; ?></li>
                        <?= !empty($forum->for_data_prevista) ? '<li><b> Data Prevista <span style="color: #d35400">Alterada para: </b></span>' .date('d/m/Y', strtotime($forum->for_data_prevista)) : ''; ?></li>
                        <?= !empty($forum->situacao->sit_descricao) ? '<li><b>Situação <span style="color: #d35400">Alterado para: </b></span>' .$forum->situacao->sit_descricao : ''; ?></li>
                        <?= !empty($forum->for_prioridade) ? '<li><b>Prioridade <span style="color: #d35400">Alterado para: </b></span>' .$forum->for_prioridade : ''; ?></li>
                    </ul><br />

                    <h5><?= $forum->for_mensagem ?></h5>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</div>
