<?php
/* @var $this yii\web\View */
// namespace yii\bootstrap;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Suporte GTI';
?>
<div class="site-index">
        <h1 class="text-center"> Histórico de Versões</h1>
            <div class="body-content">
                <div class="container">
                    <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-star-empty"></i> Versão 1.1 - Publicado em 30/05/2019
                    </div>
                        <div class="panel-body">
                          <h4><b style="color: #337ab7;">Implementações</b></h4>
                            <h5><i class="glyphicon glyphicon-tag"></i><b> Listagem de Suportes</b></h5>
                                    <h5>- Implementado a formatação das mensagens nas atualizações dos chamados.</h5>
                                    <h5>- Implementado o envio de notificações para usuários e técnicos nas atualizações dos chamados.</h5><br />

                          <h4><b style="color: #337ab7;">Correções</b></h4>
                            <h5><i class="glyphicon glyphicon-tag"></i><b> Listagem de Suportes</b></h5>
                                    <h5>- Corrigido o problema ao buscar por situação na área técnica.</h5>
                                    <h5>- Corrigido o formulário para aparecer alguns campos somente para os técnicos.</h5>
                                    <h5>- Corrigido o campo da categoria no envio do e-mail onde estava aparecendo o código e não a descrição</h5>
                                    <h5>- Corrigido a contagem por solicitações em processo na área técnica</h5><br />
                        </div>
                    </div>
            </div>
        </div>   
</div>