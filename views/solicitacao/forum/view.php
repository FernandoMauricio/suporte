<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

?>
<div class="historico-forum-view">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Histórico de Mensagens</h3>
        </div>
        <?php foreach ($modelsForums as $forum): ?>
        <div class="panel-body">
            <div class="row">
                <div class="well">
                    <b>Atualizado Por: </b> <?= $forum->solicitacao->usuario->usu_nomeusuario ?> - <b>Feita em:</b> <?= date('d/m/Y à\s H:i', strtotime($forum->for_data)); ?><hr style="border-top: 1px solid #c1c1c1; margin-top: 5px">

                    <ul>
                        <?= isset($forum->tecnicoForum->usu_nomeusuario) ? '<li><b><span style="color: #d35400">Atribuído para: </b></span>' .ucwords(strtolower($forum->tecnicoForum->usu_nomeusuario)) : ''; ?></li>
                        <?= isset($forum->for_data_prevista) ? '<li><b><span style="color: #d35400">Data prevista: </b></span>' .$forum->for_data_prevista : ''; ?></li>
                        <?= isset($forum->situacao->sit_descricao) ? '<li><b><span style="color: #d35400">Alterado para: </b></span>' .$forum->situacao->sit_descricao : ''; ?></li>
                    </ul>

                    <h5><?= $forum->for_mensagem ?></h5>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
