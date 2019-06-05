<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use kartik\nav\NavX;

$session = Yii::$app->session;

NavBar::begin([
    'brandLabel' => '<img src="css/img/logo_senac_topo.png"/>',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);


if($session['sess_codunidade'] == 1) { //ACESSOS GTI

    echo Navx::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => '<span class="glyphicon glyphicon-list-alt"></span> Listagem de Suportes', 'url' => ['/solicitacao/index']],

            ['label' => 'Administração', 'items' => [
                '<li class="dropdown-header">Administração dos Suportes</li>',
                    ['label' => '<span class="glyphicon glyphicon-list-alt"></span> Listagem de Suportes', 'url' => ['/solicitacao/index-adm']],
                    ['label' => '<span class="glyphicon glyphicon-floppy-save"></span> Suportes Finalizados', 'url' => ['/solicitacao/index-finalizados']],
                ]
            ],

            ['label' => '<span class="glyphicon glyphicon-user"></span>  Usuário (' . utf8_encode(ucwords(strtolower($session['sess_nomeusuario']))) . ')',
            'items' => [
                    '<li class="dropdown-header">Área do Usuário</li>',
                    ['label' => 'Versões Anteriores', 'url' => ['/site/versao']],
                    ['label' => 'Sair', 'url' => 'https://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
                ],
            ],
        ],
    ]);
}else{

    echo Navx::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => '<span class="glyphicon glyphicon-list-alt"></span> Listagem de Suportes', 'url' => ['/solicitacao/index']],

            ['label' => '<span class="glyphicon glyphicon-user"></span>  Usuário (' . utf8_encode(ucwords(strtolower($session['sess_nomeusuario']))) . ')',
            'items' => [
                    '<li class="dropdown-header">Área do Usuário</li>',
                    ['label' => 'Versões Anteriores', 'url' => ['/site/versao']],
                    ['label' => 'Sair', 'url' => 'https://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
                ],
            ],
        ],
    ]);

}
NavBar::end();
?>