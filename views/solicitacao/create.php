<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */

$this->title = 'Nova Solicitação';
$this->params['breadcrumbs'][] = ['label' => 'Listagem de Suportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sistemas' => $sistemas,
        'unidades' => $unidades,
        'usuarios' => $usuarios,
    ]) ?>

</div>
