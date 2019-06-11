<?php

namespace app\controllers;

use Yii;
use app\models\base\Sistemas;
use app\models\base\Situacao;
use app\models\base\Usuario;
use app\models\base\Email;
use app\models\base\Colaborador;
use app\models\solicitacao\Forum;
use app\models\solicitacao\Solicitacao;
use app\models\solicitacao\SolicitacaoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SolicitacaoController implements the CRUD actions for Solicitacao model.
 */
class EmailController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionEnviarEmailSolicitanteIndex($id)
    {
        $model = $this->findModel($id);

        $posted = current($_POST['Solicitacao']);

        $emailSolicitante = Email::find()
        ->select('emus_email')
        ->joinWith('usuario')
        ->where(['usu_codusuario' => $model->solic_usuario_solicitante])
        ->one();

        $header = '
        <p><b>MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
        Para isso, utilize o módulo de suporte do Portal Senac para responder este e-mail.<br> _<em><i></i></em>__<em>_</em>____________________________________________________________________________________________________</p>
        ';
        $titulo = '<h1>Suporte #'.$model->solic_id.': (<b style="color: #d35400"">'.$model->situacao->sit_descricao.'</b>) - '.$model->solic_titulo.'</h1>';

        $alteracoes = '';
        
        if (!empty($posted['solic_data_prevista'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b> Data Prevista <span style="color: #d35400">Alterada para: </b></span>' .date('d/m/Y', strtotime($model->solic_data_prevista)).'</li>
            </ul>
            ';
        }
        if (!empty($posted['solic_tipo'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b>Tipo <span style="color: #d35400">Alterado para: </b></span> '.$model->solic_tipo.'</li>
            </ul>
            ';
        }

        if (!empty($posted['solic_prioridade'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b>Prioridade <span style="color: #d35400">Alterado para: </b></span> '.$model->solic_prioridade.'</li>
            </ul>
            ';
        }
        if (!empty($posted['solic_usuario_suporte'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b>Técnico Responsável <span style="color: #d35400">Atribuído para: </b></span>: '.ucwords(mb_strtolower($model->usuario->usu_nomeusuario)).' </li>
            </ul>
            ';
        }
        if (!empty($posted['situacao_id'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b>Situação <span style="color: #d35400">Alterado para: </b></span> '.$model->situacao->sit_descricao.'</li>
            </ul>
            ';
        }

        $footer = '<p style="font-size:0.8em; font-style:italic"><b>ESTA É UMA MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
                Você recebeu este e-mail porque você está inscrito na lista de e-mails do Portal Senac.</p></p>';

        Yii::$app->mailer->compose()
        ->setFrom(['sistema.gic@am.senac.br' => 'Suporte GTI'])
        ->setTo($emailSolicitante->emus_email)
        ->setSubject('Suporte #'.$model->solic_id.': ('.$model->situacao->sit_descricao.') - '.$model->solic_titulo.'')
        ->setTextBody('MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL')
        ->setHtmlBody('
            '.$header.'
            '.$titulo.'
            '.$alteracoes.'
            <hr style="width:100%; height:1px; background:#ccc; border:0; margin:1.2em 0">
            '.$footer.'
        ')
        ->send();
    }

    public function actionEnviarEmailTecnicoIndex($id)
    {
        $model = $this->findModel($id);

        $posted = current($_POST['Solicitacao']);

        $emailSolicitante = Email::find()
        ->select('emus_email')
        ->joinWith('usuario')
        ->where(['usu_codusuario' => $model->solic_usuario_suporte])
        ->one();

        $header = '
        <p><b>MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
        Para isso, utilize o módulo de suporte do Portal Senac para responder este e-mail.<br> _<em><i></i></em>__<em>_</em>____________________________________________________________________________________________________</p>
        ';
        $titulo = '<h1>Suporte #'.$model->solic_id.': (<b style="color: #d35400"">'.$model->situacao->sit_descricao.'</b>) - '.$model->solic_titulo.'</h1>';

        $alteracoes = '';
        
        if (!empty($posted['solic_data_prevista'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b> Data Prevista <span style="color: #d35400">Alterada para: </b></span>' .date('d/m/Y', strtotime($model->solic_data_prevista)).'</li>
            </ul>
            ';
        }
        if (!empty($posted['solic_tipo'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b>Tipo <span style="color: #d35400">Alterado para: </b></span> '.$model->solic_tipo.'</li>
            </ul>
            ';
        }

        if (!empty($posted['solic_prioridade'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b>Prioridade <span style="color: #d35400">Alterado para: </b></span> '.$model->solic_prioridade.'</li>
            </ul>
            ';
        }
        if (!empty($posted['solic_usuario_suporte'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b>Técnico Responsável <span style="color: #d35400">Atribuído para: </b></span>: '.ucwords(mb_strtolower($model->tecnico->usu_nomeusuario)).' </li>
            </ul>
            ';
        }
        if (!empty($posted['situacao_id'])) {
            $alteracoes .= '
            <ul style="line-height:1.4em">
                <li><b>Situação <span style="color: #d35400">Alterado para: </b></span> '.$model->situacao->sit_descricao.'</li>
            </ul>
            ';
        }

        $footer = '<p style="font-size:0.8em; font-style:italic"><b>ESTA É UMA MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
                Você recebeu este e-mail porque você está inscrito na lista de e-mails do Portal Senac.</p></p>';

        Yii::$app->mailer->compose()
        ->setFrom(['sistema.gic@am.senac.br' => 'Suporte GTI'])
        ->setTo($emailSolicitante->emus_email)
        ->setSubject('Suporte #'.$model->solic_id.': ('.$model->situacao->sit_descricao.') - '.$model->solic_titulo.'')
        ->setTextBody('MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL')
        ->setHtmlBody('
            '.$header.'
            '.$titulo.'
            '.$alteracoes.'
            <hr style="width:100%; height:1px; background:#ccc; border:0; margin:1.2em 0">
            '.$footer.'
        ')
        ->send();
    }

    public function actionEnviarEmailSolicitante($id)
    {
        $model = $this->findModel($id);

        $emailSolicitante = Email::find()
        ->select('emus_email')
        ->joinWith('usuario')
        ->where(['usu_codusuario' => $model->solic_usuario_solicitante])
        ->one();

        $header = '
        <p><b>MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
        Para isso, utilize o módulo de suporte do Portal Senac para responder este e-mail.<br> _<em><i></i></em>__<em>_</em>____________________________________________________________________________________________________</p>
        ';
        $titulo = '<h1>Suporte #'.$model->solic_id.': (<b style="color: #d35400"">'.$model->situacao->sit_descricao.'</b>) - '.$model->solic_titulo.'</h1>';

        $categoriaSistema = !empty($model->categoriaSistema->sist_descricao) ? $model->categoriaSistema->sist_descricao : '-';

        $alteracoes = '
        <ul style="line-height:1.4em">
            <li><b>Situação</b>: '.$model->situacao->sit_descricao.' </li>
            <li><b>Prioridade</b>: '.$model->solic_prioridade.' </li>
            <li><b>Categoria</b>: '.$categoriaSistema.' </li>
            <li><b>Tipo</b>: '.$model->solic_tipo.' </li>
        </ul>
        ';
        $texto = '<p>'.$_POST['Forum']['for_mensagem'].'</p>';

        $footer = '<p style="font-size:0.8em; font-style:italic"><b>ESTA É UMA MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
                Você recebeu este e-mail porque você está inscrito na lista de e-mails do Portal Senac.</p></p>';

        Yii::$app->mailer->compose()
        ->setFrom(['sistema.gic@am.senac.br' => 'Suporte GTI'])
        ->setTo($emailSolicitante->emus_email)
        ->setSubject('Suporte #'.$model->solic_id.': ('.$model->situacao->sit_descricao.') - '.$model->solic_titulo.'')
        ->setTextBody('MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL')
        ->setHtmlBody('
            '.$header.'
            '.$titulo.'
            '.$alteracoes.'
            '.$texto.'
            <hr style="width:100%; height:1px; background:#ccc; border:0; margin:1.2em 0">
            '.$footer.'
        ')
        ->send();
    }

    public function actionEnviarEmailTecnico($id)
    {
        $model = $this->findModel($id);

        $posted = current($_POST['Solicitacao']);

        $emailTecnico = Email::find()
        ->select('emus_email')
        ->joinWith('usuario')
        ->where(['usu_codusuario' => $model->solic_usuario_suporte])
        ->one();

        $header = '
        <p><b>MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
        Para isso, utilize o módulo de suporte do Portal Senac para responder este e-mail.<br> _<em><i></i></em>__<em>_</em>____________________________________________________________________________________________________</p>
        ';
        $titulo = '<h1>Suporte #'.$model->solic_id.': (<b style="color: #d35400"">'.$model->situacao->sit_descricao.'</b>) - '.$model->solic_titulo.'</h1>';

        $categoriaSistema = !empty($model->categoriaSistema->sist_descricao) ? $model->categoriaSistema->sist_descricao : '-';

        $alteracoes = '
        <ul style="line-height:1.4em">
            <li><b>Solicitante</b>: '.ucwords(mb_strtolower($model->usuario->usu_nomeusuario)).' </li>
            <li><b>Situação</b>: '.$model->situacao->sit_descricao.' </li>
            <li><b>Prioridade</b>: '.$model->solic_prioridade.' </li>
            <li><b>Categoria</b>: '.$categoriaSistema.' </li>
            <li><b>Tipo</b>: '.$model->solic_tipo.' </li>
        </ul>
        ';
        $texto = '<p>'.$_POST['Forum']['for_mensagem'].'</p>';

        $footer = '<p style="font-size:0.8em; font-style:italic"><b>ESTA É UMA MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
                Você recebeu este e-mail porque você está inscrito na lista de e-mails do Portal Senac.</p></p>';

        //Será verificado se existe algum técnico que assumiu o chamado, se não houver, enviará para a lista GTI
        if(!empty($emailTecnico->emus_email)){ 
            $email = $emailTecnico->emus_email;
        }else{
            $email = 'gti-suporte@am.senac.br';
        }

        Yii::$app->mailer->compose()
        ->setFrom(['sistema.gic@am.senac.br' => 'Suporte GTI'])
        ->setTo([$email])
        ->setSubject('Suporte #'.$model->solic_id.': ('.$model->situacao->sit_descricao.') - '.$model->solic_titulo.'')
        ->setTextBody('MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL')
        ->setHtmlBody('
            '.$header.'
            '.$titulo.'
            '.$alteracoes.'
            '.$texto.'
            <hr style="width:100%; height:1px; background:#ccc; border:0; margin:1.2em 0">
            '.$footer.'
        ')
        ->send();

    }

    public function actionEnviarEmailSuporteFinalizadoPeloUsuario($id)
    {
        $model = $this->findModel($id);

        $header = '
        <p><b>MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
        Para isso, utilize o módulo de suporte do Portal Senac para responder este e-mail.<br> _<em><i></i></em>__<em>_</em>____________________________________________________________________________________________________</p>
        ';
        $titulo = '<h1>Suporte #'.$model->solic_id.': (<b style="color: #d35400"">'.$model->situacao->sit_descricao.'</b>) - '.$model->solic_titulo.'</h1>';

        $alteracoes = '
            <ul style="line-height:1.4em">
                <li><b>Situação <span style="color: #d35400">Alterado para: </b></span> '.$model->situacao->sit_descricao.'</li>
            </ul>
        ';

        $footer = '<p style="font-size:0.8em; font-style:italic"><b>ESTA É UMA MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
                Você recebeu este e-mail porque você está inscrito na lista de e-mails do Portal Senac.</p></p>';

        Yii::$app->mailer->compose()
        ->setFrom(['sistema.gic@am.senac.br' => 'Suporte GTI'])
        ->setTo(['gti-suporte@am.senac.br', ])
        ->setSubject('Suporte #'.$model->solic_id.': ('.$model->situacao->sit_descricao.') - '.$model->solic_titulo.'')
        ->setTextBody('MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL')
        ->setHtmlBody('
            '.$header.'
            '.$titulo.'
            '.$alteracoes.'
            <hr style="width:100%; height:1px; background:#ccc; border:0; margin:1.2em 0">
            '.$footer.'
        ')
        ->send();
    }

    public function actionEnviarEmailSuporteFinalizadoPeloTecnico($id)
    {
        $model = $this->findModel($id);

        $emailSolicitante = Email::find()
        ->select('emus_email')
        ->joinWith('usuario')
        ->where(['usu_codusuario' => $model->solic_usuario_solicitante])
        ->one();

        $header = '
        <p><b>MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
        Para isso, utilize o módulo de suporte do Portal Senac para responder este e-mail.<br> _<em><i></i></em>__<em>_</em>____________________________________________________________________________________________________</p>
        ';
        $titulo = '<h1>Suporte #'.$model->solic_id.': (<b style="color: #d35400"">'.$model->situacao->sit_descricao.'</b>) - '.$model->solic_titulo.'</h1>';

        $alteracoes = '
            <ul style="line-height:1.4em">
                <li><b>Situação <span style="color: #d35400">Alterado para: </b></span> '.$model->situacao->sit_descricao.'</li>
            </ul>
        ';

        $footer = '<p style="font-size:0.8em; font-style:italic"><b>ESTA É UMA MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
                Você recebeu este e-mail porque você está inscrito na lista de e-mails do Portal Senac.</p></p>';

        Yii::$app->mailer->compose()
        ->setFrom(['sistema.gic@am.senac.br' => 'Suporte GTI'])
        ->setTo($emailSolicitante->emus_email)
        ->setSubject('Suporte #'.$model->solic_id.': ('.$model->situacao->sit_descricao.') - '.$model->solic_titulo.'')
        ->setTextBody('MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL')
        ->setHtmlBody('
            '.$header.'
            '.$titulo.'
            '.$alteracoes.'
            <hr style="width:100%; height:1px; background:#ccc; border:0; margin:1.2em 0">
            '.$footer.'
        ')
        ->send();
    }

    public function actionEnviarEmailSolicitacaoCriacao($id)
    {
        $model = $this->findModel($id);

        $emailSolicitante = Email::find()
        ->select('emus_email')
        ->joinWith('usuario')
        ->where(['usu_codusuario' => $model->solic_usuario_solicitante])
        ->one();

        $header = '
        <p><b>MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
        Para isso, utilize o módulo de suporte do Portal Senac para responder este e-mail.<br> _<em><i></i></em>__<em>_</em>____________________________________________________________________________________________________</p>
        ';
        $titulo = '<h1>Suporte #'.$model->solic_id.': (<b style="color: #d35400"">'.$model->situacao->sit_descricao.'</b>) - '.$model->solic_titulo.'</h1>';

        $categoriaSistema = !empty($model->categoriaSistema->sist_descricao) ? $model->categoriaSistema->sist_descricao : '-';

        $alteracoes = '
        <ul style="line-height:1.4em">
            <li><b>Solicitante</b>: '.ucwords(mb_strtolower($model->usuario->usu_nomeusuario)).' </li>
            <li><b>Situação</b>: '.$model->situacao->sit_descricao.' </li>
            <li><b>Prioridade</b>: '.$model->solic_prioridade.' </li>
            <li><b>Categoria</b>: '.$categoriaSistema.' </li>
            <li><b>Tipo</b>: '.$model->solic_tipo.' </li>
        </ul>
        ';
        $texto = '<p>'.$model->solic_desc_serv.'</p>';

        $footer = '<p style="font-size:0.8em; font-style:italic"><b>ESTA É UMA MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
                Você recebeu este e-mail porque você está inscrito na lista de e-mails do Portal Senac.</p></p>';

        Yii::$app->mailer->compose()
        ->setFrom(['sistema.gic@am.senac.br' => 'Suporte GTI'])
        ->setTo(['gti-suporte@am.senac.br', $emailSolicitante->emus_email])
        ->setSubject('Suporte #'.$model->solic_id.': ('.$model->situacao->sit_descricao.') - '.$model->solic_titulo.'')
        ->setTextBody('MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL')
        ->setHtmlBody('
            '.$header.'
            '.$titulo.'
            '.$alteracoes.'
            '.$texto.'
            <hr style="width:100%; height:1px; background:#ccc; border:0; margin:1.2em 0">
            '.$footer.'
        ')
        ->send();
    }

    /**
     * Finds the Solicitacao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Solicitacao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Solicitacao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
