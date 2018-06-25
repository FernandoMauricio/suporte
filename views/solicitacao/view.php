<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */

$this->title = $model->solic_id;
$this->params['breadcrumbs'][] = ['label' => 'Listagem dos Suportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->solic_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->solic_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'solic_id',
            'solic_titulo',
            'solic_patrimonio',
            'solic_desc_equip',
            'solic_desc_serv:ntext',
            'solic_unidade_solicitante',
            'solic_usuario_solicitante',
            'solic_data_solicitacao',
            'solic_data_prevista',
            'solic_data_finalizacao',
            'solic_prioridade',
            'solic_usuario_suporte',
            'solic_sistema_id',
            'solic_tipo',
            'situacao_id',
        ],
    ]) ?>

</div>
