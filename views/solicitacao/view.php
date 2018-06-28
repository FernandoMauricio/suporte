<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */

$this->title = $model->solic_id;
$this->params['breadcrumbs'][] = ['label' => 'Listagem de Suportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'solic_id',
            'solic_titulo',
            'solic_patrimonio',
            'solic_desc_equip',
            'solic_desc_serv:ntext',
            'unidade.uni_nomeabreviado',
            'usuario.usu_nomeusuario',
            'solic_data_solicitacao',
            'solic_data_prevista',
            'solic_data_finalizacao',
            'solic_prioridade',
            'tecnico.usu_nomeusuario',
            'categoriaSistema.sist_descricao',
            'solic_tipo',
            'situacao.sit_descricao',
        ],
    ]) ?>

    <?= $this->render('forum/view', [
        'forum' => $forum,
        'modelsForums' => $modelsForums,
    ]) ?>

    <?= $this->render('forum/_form', [
        'forum' => $forum,
        'situacao' => $situacao,
    ]) ?>

</div>
