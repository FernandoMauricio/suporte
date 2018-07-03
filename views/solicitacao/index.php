<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\solicitacao\Solicitacao;
use app\models\base\Sistemas;
use app\models\base\Situacao;

/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacao\SolicitacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listagem de Suportes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Nova Solicitação de Suporte', ['value'=> Url::to(['gerar-suporte']), 'class' => 'btn btn-success', 'id'=>'modalButton']) ?>
    </p>

 <?php
    Modal::begin([
        'options' => ['tabindex' => false ], // important for Select2 to work properly
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => true],
        'header' => '<h4>Nova Solicitação de Suporte</h4>',
        'id' => 'modal',
        'size' => 'modal-lg',
        ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
?>

<?php

$gridColumns = [
    [
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'50px',
        'format' => 'raw',
        'value'=>function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('view-expand', ['model'=>$model, 'modelsForums' => $model->forums]);
        },
        'headerOptions'=>['class'=>'kartik-sheet-style'], 
        'expandOneOnly'=>true
    ],

    'solic_id',
    'solic_tipo',
    [
        'attribute'=>'solic_sistema_id', 
        'width'=>'5%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->solic_sistema_id != NULL ? $model->categoriaSistema->sist_descricao : '' ;
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Sistemas::find()->select(['id', 'sist_descricao'])->asArray()->all(), 'id', 'sist_descricao'),
        'filterInputOptions'=>['placeholder'=>'Selecione a Categoria...'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
    ],
    'solic_titulo',
    // 'solic_patrimonio',
    // 'solic_desc_equip',
    // 'solic_desc_serv:ntext',
    //'solic_unidade_solicitante',
    //'solic_usuario_solicitante',
    //'solic_data_solicitacao',
    //'solic_data_finalizacao',
    [
        'attribute'=>'solic_usuario_suporte', 
        'width'=>'5%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->solic_usuario_suporte != NULL ? $model->tecnico->usu_nomeusuario : '' ;
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Solicitacao::find()->select(['solic_usuario_suporte', 'usu_nomeusuario'])->distinct()->innerJoin('db_base.usuario_usu', 'solic_usuario_suporte = usu_codusuario')->asArray()->all(), 'solic_usuario_suporte', 'usu_nomeusuario'),
        'filterInputOptions'=>['placeholder'=>'Selecione o Técnico...'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
    ],
    'solic_data_prevista',

    'solic_prioridade',

    [
        'attribute'=>'situacao_id', 
        'width'=>'5%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->situacao_id != NULL ? $model->situacao->sit_descricao : '' ;
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Situacao::find()->select(['id', 'sit_descricao'])->asArray()->all(), 'id', 'sit_descricao'),
        'filterInputOptions'=>['placeholder'=>'Selecione a Situação...'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
    ],

    ['class' => 'yii\grid\ActionColumn'],
]; ?>

<?php Pjax::begin(); ?>

<?php 
    echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'columns'=>$gridColumns,
    'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
    'pjax'=>false, // pjax is set to always true for this demo
    // 'rowOptions' =>function($model){
    //     if($model->etapa_situacao == 'Em Homologação'){
    //         return['class'=>'warning'];                        
    //     }else if($model->etapa_situacao == 'Em Processo'){
    //         return['class'=>'info'];    
    //     }else if($model->etapa_situacao == 'Encerrado' || $model->etapa_situacao == 'Encerrado sem Classificados'){
    //         return['class'=>'success'];    
    //     }
    // },
    'beforeHeader'=>[
        [
            'columns'=>[
                ['content'=>'Detalhes da Listagem dos Suportes', 'options'=>['colspan'=>9, 'class'=>'text-center warning']], 
                ['content'=>'Área de Ações', 'options'=>['colspan'=>1, 'class'=>'text-center warning']], 
            ],
        ]
    ],
        'hover' => true,
        'panel' => [
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=> '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Listagem - Suportes</h3>',
    ],
]);
    ?>
<?php Pjax::end(); ?>

</div>

