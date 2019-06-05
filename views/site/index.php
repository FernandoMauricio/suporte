<?php
$session = Yii::$app->session;
/* @var $this yii\web\View */

$this->title = 'Suporte GTI';
?>

<div class="site-index">
    <h1 class="text-center"> Módulo de Suporte GTI</h1>
        <div class="body-content">
            <div class="container">
                <h3>Bem vindo(a), <?php echo $session['sess_nomeusuario'] = utf8_encode(ucwords(strtolower($session['sess_nomeusuario'])))?>!</h3>
            </div>
        </div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
          <div class="card-counter default">
            <i class="glyphicon glyphicon-inbox"></i>
            <span class="count-numbers"><?= $aguardAtendimento ?></span>
            <span class="count-numbers-porcent">(<?= number_format($aguardAtendimentoPorcent['countAtendimento'], 2, ',', ' ')  . '%'; ?>)</span>
            <span class="count-name">Aguardando Atend.</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card-counter primary">
            <i class="glyphicon glyphicon-tasks"></i>
            <span class="count-numbers"><?= $emProcesso ?></span>
            <span class="count-numbers-porcent">(<?= number_format($emProcessoPorcent['countAtendimento'], 2, ',', ' ')  . '%'; ?>)</span>
            <span class="count-name">Em Processo</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card-counter danger">
            <i class="glyphicon glyphicon-warning-sign"></i>
            <span class="count-numbers"><?= $atrasados ?></span>
            <span class="count-numbers-porcent">(<?= number_format($atrasadosPorcent['countAtendimento'], 2, ',', ' ')  . '%'; ?>)</span>
            <span class="count-name">Atrasados</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card-counter success">
            <i class="glyphicon glyphicon-ok"></i>
            <span class="count-numbers"><?= $finalizadosTecnico ?></span>
            <span class="count-numbers-porcent">(<?= number_format($finalizadosTecnicoPorcent['countAtendimento'], 2, ',', ' ')  . '%'; ?>)</span>
            <span class="count-name">Finaliz. Técnico</span>
          </div>
        </div>
    </div>
<br />
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-star-empty"></i> O que há de novo? - Versão 1.1 - Publicado em 30/05/2019</div>
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

                        <p><a href="index.php?r=site/versao" class="btn btn-warning" role="button">Histórico de Versões</a></p>
            </div>
        </div>
    </div>
</div>
</div>