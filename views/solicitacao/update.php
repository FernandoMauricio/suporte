<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */

$this->title = 'Atualizar Solicitação: ' . $model->solic_id;
$this->params['breadcrumbs'][] = ['label' => 'Listagem dos Suportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->solic_id, 'url' => ['view', 'id' => $model->solic_id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="solicitacao-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
