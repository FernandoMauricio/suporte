<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\bootstrap\Collapse;

use app\models\solicitacao\Solicitacao;
use app\models\base\Sistemas;
use app\models\base\Situacao;
use app\models\base\Unidade;
use app\models\base\Usuario;

/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacao\SolicitacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listagem de Suportes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-index-adm">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        echo Collapse::widget([
            'items' => [
                // equivalent to the above
                [
                    'label' => 'Pesquisa Avançada',
                    'content' => $this->render('_search', ['model' => $searchModel]),
                    // open its content by default
                    //'options' => ['class' => 'panel panel-primary']
                ],
            ]
        ]);
    ?>

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
        'width'=>'2%',
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

    [
        'attribute'=>'solic_id', 
        'width'=>'3%',
    ],

    [
        'attribute' => 'solic_unidade_solicitante',
        'label' => 'Unidade',
        'width' => '8%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->solic_unidade_solicitante != NULL ? $model->unidade->uni_nomeabreviado : '';
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Unidade::find()->select(['uni_codunidade', 'uni_nomeabreviado'])->asArray()->all(), 'uni_codunidade', 'uni_nomeabreviado'),
        'filterInputOptions'=>['placeholder'=>'Unidade...'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
    ],

    [
        'attribute'=>'solic_usuario_solicitante', 
        'label' => 'Solicitante',
        'width'=>'8%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->solic_usuario_solicitante != NULL ? $model->usuario->usu_nomeusuario : '';
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Usuario::find()->select(['usu_codusuario', 'usu_nomeusuario'])->where(['usu_codsituacao' => 1, 'usu_codtipo' => 2])->asArray()->all(), 'usu_codusuario', 'usu_nomeusuario'),
        'filterInputOptions'=>['placeholder'=>'Unidade...'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
    ],

    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute'=>'solic_tipo', 
        'width'=>'5%',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $widget) { 
            return $model->solic_tipo != NULL ? $model->solic_tipo : '';
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>['Sistemas' => 'Sistemas', 'Equipamentos' => 'Equipamentos'],
        'filterInputOptions'=>['placeholder'=>'Tipo...'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'editableOptions' => [
            'header' => 'Tipo',
            'data'=>['Sistemas' => 'Sistemas', 'Equipamentos' => 'Equipamentos'],
            'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
        ],
    ],

    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute'=>'solic_sistema_id', 
        'width'=>'5%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->solic_sistema_id != NULL ? $model->categoriaSistema->sist_descricao : NULL;
        },
        'readonly' => function($model, $key, $index, $widget) {
            return ($model->solic_tipo == 'Equipamentos'); // do not allow editing of inactive records
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Sistemas::find()->select(['id', 'sist_descricao'])->asArray()->all(), 'id', 'sist_descricao'),
        'filterInputOptions'=>['placeholder'=>'Categoria...'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'editableOptions' => [
            'header' => 'Categoria',
            'data'=>ArrayHelper::map(Sistemas::find()->select(['id', 'sist_descricao'])->asArray()->all(), 'id', 'sist_descricao'),
            'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
        ],
    ],

    [
        'attribute'=>'solic_titulo', 
        'width'=>'20%',
    ],
    // 'solic_patrimonio',
    //'solic_desc_equip',
    //'solic_desc_serv:ntext',
    //'solic_data_solicitacao',
    //'solic_data_finalizacao',

    [
        'class'=>'kartik\grid\EditableColumn',
        'attribute'=>'solic_data_prevista',
        'width' => '8%', 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'format' => ['date', 'php:d/m/Y'],
        'filter'=> DatePicker::widget([
        'model' => $searchModel, 
        'attribute' => 'solic_data_prevista',
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd',
            ]
        ]),
        'editableOptions'=>[
            'inputType'=>\kartik\editable\Editable::INPUT_WIDGET,
            'widgetClass'=> 'kartik\datecontrol\DateControl',
            'options'=>[
                'type'=>\kartik\datecontrol\DateControl::FORMAT_DATE,
                'displayFormat'=>'dd/MM/yyyy',
                'saveFormat'=>'php:Y-m-d',
                'options'=>[
                    'pluginOptions'=>[
                        'autoclose'=>true
                    ]
                ]
            ]
        ],
    ],

    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute'=>'solic_prioridade', 
        'width'=>'5%',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $widget) { 
            return $model->solic_prioridade == 'Normal' ? '<span class="label label-default">'.$model->solic_prioridade.'</span>' : '<span class="label label-danger">'.$model->solic_prioridade.'</span>'; 
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>['Normal' => 'Normal', 'Priorizada' => 'Priorizada'],
        'filterInputOptions'=>['placeholder'=>'Tipo...'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'editableOptions' => [
            'header' => 'Prioridade',
            'data'=>['Normal' => 'Normal', 'Priorizada' => 'Priorizada'],
            'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
        ],
    ],

    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'solic_usuario_suporte',
        'width'=>'10%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->solic_usuario_suporte != NULL ? ucwords(mb_strtolower($model->tecnico->usu_nomeusuario)) : '' ;
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>[219 => 'Endrio Medeiros', 129 => 'Fernando Mauricio',  94 => 'Laércio Varela', 205 => 'Mackson Silva', 369 => 'Rafael Cunha'], 
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
            'filterInputOptions'=>['placeholder'=>'Técnico...'],
        //CAIXA DE ALTERAÇÕES
        'editableOptions' => [
            'header' => 'Técnico',
            'data'=>[219 => 'Endrio Medeiros', 129 => 'Fernando Mauricio',  94 => 'Laércio Varela', 205 => 'Mackson Silva', 369 => 'Rafael Cunha'],
            'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
        ],          
    ],

    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute'=>'situacao_id', 
        'width'=>'10%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->situacao_id != NULL ? $model->situacao->sit_descricao : '' ;
        },
        'readonly' => function($model, $key, $index, $widget) {
            return ($model->situacao_id == 7); //Finalizado pelo técnico
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Situacao::find()->select(['id', 'sit_descricao'])->asArray()->all(), 'id', 'sit_descricao'),
        'filterInputOptions'=>['placeholder'=>'Situação...'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        //CAIXA DE ALTERAÇÕES
        'editableOptions' => [
            'header' => 'Situação',
            'data'=>ArrayHelper::map(Situacao::find()->select(['id', 'sit_descricao'])->where(['not in', 'id', [6,7]])->asArray()->all(), 'id', 'sit_descricao'),
            'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
        ], 
    ],

    [
        'attribute' => 'countDias',
        'width'=>'2%',
        'value'=>function ($model, $key, $index, $widget) { 
                $data_inicio = new DateTime(date('Y-m-d'));
                $data_fim = new DateTime($model->solic_data_prevista);
                $dateInterval = $data_inicio->diff($data_fim);
            if($model->situacao_id != 6 && $model->situacao_id != 7) { // QUANDO FOR DIFERENTE DA SOLICIAÇÃO ATENDIDA
                return $dateInterval->format("%r%a");
            }else{
                return '-';
            }
        },
    ],

    ['class' => 'yii\grid\ActionColumn',
        'template' => '{view} {finalizar-suporte-pelo-tecnico}',
        'contentOptions' => ['style' => 'width: 10%'],
        'buttons' => [
        //VISUALIZAR
        'view' => function ($url, $model) {
            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                'title' => Yii::t('app', 'Detalhes do suporte'),
                'class'=>'btn btn-primary btn-xs',
            ]);
        },
        //FINALIZAR SUPORTE
        'finalizar-suporte-pelo-tecnico' => function ($url, $model) {
            if($model->situacao_id != 6 && $model->situacao_id != 7) {
            return Html::a('<span class="glyphicon glyphicon-ok"></span> Finalizar' , $url, [
                    'title' => Yii::t('app', 'Finalizar'),
                    'class'=>'btn btn-success btn-xs',
                    'data' => [
                                'confirm' => 'Você tem certeza que deseja <b>finalizar</b> esse suporte?',
                                'method' => 'post',
                            ],
            ]); }else{
                '';
            } 
        },
        ],
    ],

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
    'rowOptions' =>function($model) {
        $data_inicio = new DateTime(date('Y-m-d'));
        $data_fim = new DateTime($model->solic_data_prevista);
        $dateInterval = $data_inicio->diff($data_fim);
        if ($model->situacao_id == 6) {
            return['class'=>'success'];                       
        }else if (isset($model->solic_data_prevista) && $dateInterval->format("%r%a") == 0) {
            return['class'=>'warning'];
        }else if ($model->situacao_id == 7) {
            return['class'=>'info'];
        }else if(isset($model->solic_data_prevista) && $dateInterval->format("%r%a") < '-0') {
            return['class'=>'danger']; 
        }
    },
    'beforeHeader'=>[
        [
            'columns'=>[
                ['content'=>'Detalhes da Listagem dos Suportes', 'options'=>['colspan'=>12, 'class'=>'text-center warning']], 
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

