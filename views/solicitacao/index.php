<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacao\SolicitacaoAdmSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Solicitacaos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Solicitacao', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'solic_id',
            'solic_titulo',
            'solic_patrimonio',
            'solic_desc_equip',
            'solic_desc_serv:ntext',
            //'solic_unidade_solicitante',
            //'solic_usuario_solicitante',
            //'solic_data_solicitacao',
            //'solic_data_prevista',
            //'solic_data_finalizacao',
            //'solic_usuario_finalizacao',
            //'solic_prioridade',
            //'solic_usuario_suporte',
            //'solic_sistema_id',
            //'solic_tipo',
            //'situacao_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
