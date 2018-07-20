<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */
?>

<div class="solicitacao-view">
    <div class="panel-body">
        <div class="row">
            <h4><?= $model->solic_prioridade == 'Normal' ? 
                'Suporte: '. $model->solic_id . ' <small><span class="label-solicitacao-view">'.$model->solic_prioridade.'</span><span class="label-solicitacao-view">'.$model->solic_tipo.'</span></small>'  : 
                'Suporte: '. $model->solic_id . ' <small><span class="label label-danger">'.$model->solic_prioridade.'</span><span class="label-solicitacao-view">'.$model->solic_tipo.'</span></small>' 
                ?>
            </h4>

            <h5>
                <span class="pull-right"><b>Situação: </b><small><span class="label label-warning" style="font-size: 100%;font-weight:normal"><?= $model->situacao->sit_descricao; ?></span></small></span>
            </h5><br>

            <h5>
                <span class="pull-right"><b>Técnico Responsável: </b><small><span class="label label-primary" style="font-size: 100%;font-weight:normal"><?= !empty($model->tecnico->usu_nomeusuario) ? ucwords(mb_strtolower($model->tecnico->usu_nomeusuario)) : ''; ?></span></small></span>
            </h5>
        </div>
    </div>
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
            'solic_titulo',
            [
                'attribute' => 'solic_patrimonio',
                'visible' => (!empty($model->solic_patrimonio)),
            ],

            [
                'attribute' => 'solic_desc_equip',
                'visible' => (!empty($model->solic_desc_equip)),
            ],

            'solic_desc_serv:ntext',

            [
                'label' => 'Unidade Solicitante',
                'attribute' => 'unidade.uni_nomeabreviado',
            ],

            [
                'label' => 'Solicitante',
                'attribute' => 'usuario.usu_nomeusuario',
            ],

            'solic_data_solicitacao',
            'solic_data_prevista',

            [
                'attribute' => 'solic_data_finalizacao',
                'visible' => (!empty($model->solic_data_finalizacao)),
            ],

            [
                'label' => 'Categoria',
                'attribute' => 'categoriaSistema.sist_descricao',
            ],
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
