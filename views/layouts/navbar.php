<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
$session = Yii::$app->session;

NavBar::begin([
    'brandLabel' => '<img src="css/img/logo_senac_topo.png"/>',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => 'Suportes', 'url' => ['/solicitacao/index']],
        ['label' => 'Usuário (' . utf8_encode(ucwords(strtolower($session['sess_nomeusuario']))) . ')',
        'items' => [
                '<li class="dropdown-header">Área Usuário</li>',
                ['label' => 'Versões Anteriores', 'url' => ['/site/versao']],
                ['label' => 'Sair', 'url' => 'http://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
            ],
        ],
    ],
]);
NavBar::end();
?>