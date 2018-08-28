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

    <h2><?= $model->solic_prioridade == 'Normal' ? 
        'Suporte: '. Html::encode($this->title) . ' <small><span class="label-solicitacao-view">'.$model->solic_prioridade.'</span><span class="label-solicitacao-view">'.$model->solic_tipo.'</span></small>'  : 
        'Suporte: '. Html::encode($this->title) . ' <small><span class="label label-danger">'.$model->solic_prioridade.'</span><span class="label-solicitacao-view">'.$model->solic_tipo.'</span></small>' 
        ?>
    </h2>

    <p>
        <?= Html::button('Atualizar', ['value'=> Url::to(['inserir-mensagem', 'id' => $model->solic_id]), 'class' => 'btn btn-primary', 'id'=>'modalButton']) ?>
        
    </p>
    <p>
        <span class="pull-left"><b>Data da solicitação: </b><small><span class="label label-primary" style="font-size: 100%;font-weight:normal"><?= date('d/m/Y', strtotime($model->solic_data_solicitacao)); ?></span></small></span>
        <span class="pull-right"><b>Situação: </b><small><span class="label label-warning" style="font-size: 100%;font-weight:normal"><?= $model->situacao->sit_descricao; ?></span></small></span>
    </p><br /><br />

    <p>
        <span class="pull-left"><b>Data Prevista: </b><small><span class="label label-primary" style="font-size: 100%;font-weight:normal"><?= !empty($model->solic_data_prevista) ?  date('d/m/Y', strtotime($model->solic_data_prevista)) : ''; ?></span></small></span>
        <span class="pull-right"><b>Técnico Responsável: </b><small><span class="label label-primary" style="font-size: 100%;font-weight:normal"><?= !empty($model->tecnico->usu_nomeusuario) ? ucwords(mb_strtolower($model->tecnico->usu_nomeusuario)) : ''; ?></span></small></span>
    </p>

<br><br>

<!-- Mensagem informando a finalizacão  -->
<?php if($model->solic_usuario_finalizacao != NULL || $model->solic_data_finalizacao != NULL): ?> 
    <div class='alert alert-success' align='center' role='alert'>
        <span class='glyphicon glyphicon-alert' aria-hidden='true'></span> Suporte <b>encerrado</b> por: <b><?= ucwords(mb_strtolower($model->solic_usuario_finalizacao)) ?></b> em <?= date('d/m/Y à\s H:i', strtotime($model->solic_data_finalizacao)) ?>
        </div>
<?php endif; ?>

<?php
    Modal::begin([
        'options' => ['tabindex' => false], // important for Select2 to work properly
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

            [
                'attribute' => 'solic_data_solicitacao',
                'visible' => (!empty($model->solic_data_solicitacao)),
                'format'    => ['date', 'php:d/m/Y'],
            ],
            
            [
                'attribute' => 'solic_data_prevista',
                'visible' => (!empty($model->solic_data_prevista)),
                'format'    => ['date', 'php:d/m/Y'],
            ],

            [
                'attribute' => 'solic_data_finalizacao',
                'visible' => (!empty($model->solic_data_finalizacao)),
            ],

            [
                'label' => 'Categoria',
                'attribute' => 'categoriaSistema.sist_descricao',
                'visible' => (!empty($model->solic_sistema_id)),
            ],

            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => function($model) { 
                       if($files=\yii\helpers\FileHelper::findFiles('uploads/solicitacoes/' . $model->solic_id,['recursive'=>FALSE])) {
                            if (isset($files[0])) {
                                $result = "";
                                foreach ($files as $index => $file) {
                                $nameFicheiro = substr($file, strrpos($file, '/') + 1); 
                                    $result .= Html::a($nameFicheiro, Url::base().'/uploads/solicitacoes/'. $model->solic_id. '/' . $nameFicheiro, ['target'=>'_blank', 'data-pjax'=>"0"]) . "<br />"; 
                                }
                                return $result;
                            }
                    }
                },
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
                        <?= !empty($forum->for_tipo) ? '<li><b>Tipo <span style="color: #d35400">Alterado para: </b></span>' .$forum->for_tipo : ''; ?></li>
                    </ul><br />

                    <h5><?= $forum->for_mensagem ?></h5>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</div>
