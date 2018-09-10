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
</div>
<!--         <div class="body-content">
            <div class="panel panel-primary">
                <div class="panel-heading"><i class="glyphicon glyphicon-star-empty"></i> O que há de novo? - Versão 1.0 - Publicado em 03/09/2018</div>
                <div class="panel-body">
                  <h4><b style="color: #337ab7;">Implementações</b></h4>
                    <h5><i class="glyphicon glyphicon-tag"></i><b> Contratos</b></h5>
                            <h5>- Incluído o campo "Origem" no cadastro de Contratos para que seja informado se o contrato é oriundo do Senac ou externo.</h5>
                            <h5>- Incluído o campo "Tipos de Aditivos" no cadastro de aditivo para que seja informado se é por valor, prazo ou cláusula.</h5><br />

                  <h4><b style="color: #337ab7;">Correções</b></h4>
                    <h5><i class="glyphicon glyphicon-tag"></i><b> Prestadores</b></h5>
                            <h5>- Corrigido o problema no cadastro do e-mail.</h5><br />

                    <h5><i class="glyphicon glyphicon-tag"></i><b> Notificações</b></h5>
                            <h5>- Corrigido o layout do e-mail para ser enviado aos Gestores de Contrato.</h5><br />

                            <p><a href="index.php?r=site/versao" class="btn btn-warning" role="button">Histórico de Versões</a></p>
                </div>
            </div>
    </div> -->
</div>