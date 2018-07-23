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
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * SolicitacaoController implements the CRUD actions for Solicitacao model.
 */
class SolicitacaoController extends Controller
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

    public function enviarEmailSolicitante($id)
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

    // public function enviarEmailSolicitanteCriacao($id)
    // {
    //     $model = $this->findModel($id);

    //     $posted = current($_POST['Solicitacao']);

    //     $emailSolicitante = Email::find()
    //     ->select('emus_email')
    //     ->joinWith('usuario')
    //     ->where(['usu_codusuario' => $model->solic_usuario_solicitante])
    //     ->one();

    //     $header = '
    //     <p><b>MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
    //     Para isso, utilize o módulo de suporte do Portal Senac para responder este e-mail.<br> _<em><i></i></em>__<em>_</em>____________________________________________________________________________________________________</p>
    //     ';
    //     $titulo = '<h1>Suporte #'.$model->solic_id.': (<b style="color: #d35400"">'.$model->situacao->sit_descricao.'</b>) - '.$model->solic_titulo.'</h1>';

    //     $alteracoes = '
    //     <ul style="line-height:1.4em">
    //         <li><b>Solicitante</b>: '.ucwords(mb_strtolower($model->usuario->usu_nomeusuario)).' </li>
    //         <li><b>Situação</b>: '.$model->situacao->sit_descricao.' </li>
    //         <li><b>Prioridade</b>: '.$model->solic_prioridade.' </li>
    //         <li><b>Categoria</b>: '.$model->categoriaSistema->sist_descricao.' </li>
    //         <li><b>Tipo</b>: '.$model->solic_tipo.' </li>
    //     </ul>
    //     ';
    //     $texto = '<p>'.$model->solic_desc_serv.'</p>';

    //     $footer = '<p style="font-size:0.8em; font-style:italic"><b>ESTA É UMA MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL.</b><br>
    //             Você recebeu este e-mail porque você está inscrito na lista de e-mails do Portal Senac.</p></p>';

    //     Yii::$app->mailer->compose()
    //     ->setFrom(['sistema.gic@am.senac.br' => 'Suporte GTI'])
    //     ->setTo($emailSolicitante->emus_email)
    //     ->setSubject('Suporte #'.$model->solic_id.': ('.$model->situacao->sit_descricao.') - '.$model->solic_titulo.'')
    //     ->setTextBody('MENSAGEM AUTOMÁTICA. POR FAVOR, NÃO RESPONDA ESSE E-MAIL')
    //     ->setHtmlBody('
    //         '.$header.'
    //         '.$titulo.'
    //         '.$alteracoes.'
    //         '.$texto.'
    //         <hr style="width:100%; height:1px; background:#ccc; border:0; margin:1.2em 0">
    //         '.$footer.'
    //     ')
    //     ->send();
    // }

    /**
     * Lists all Solicitacao models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'main-full';

        $session = Yii::$app->session;
        $searchModel = new SolicitacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['solic_id'=>SORT_DESC]];

        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your Solicitacao model for saving
            $solicitacao = Yii::$app->request->post('editableKey');
            $model = Solicitacao::findOne($solicitacao);

            // store a default json response as desired by editable
            $out = Json::encode(['output'=>'', 'message'=>'']);

            $posted = current($_POST['Solicitacao']);
            $post = ['Solicitacao' => $posted];

            // load model like any single model validation
            if ($model->load($post)) {
                // can save model or do something before saving model
                $model->save(false);
                $output = '';
                $out = Json::encode(['output'=>$output, 'message'=>'']);

                //Instancia o fórum para inserir os logs de alterações na solicitação
                $forum = new Forum();
                $forum->solicitacao_id = $model->solic_id;
                $forum->for_usuario_id = $session['sess_codusuario'];
                $forum->for_data = date('Y-m-d H:i');
                if(!empty($posted['situacao_id'])) { $forum->situacao_id = $model->situacao_id; }
                if(!empty($posted['solic_usuario_suporte'])) { $forum->for_usuario_suporte = $model->solic_usuario_suporte; }
                if(!empty($posted['solic_data_prevista'])) { $forum->for_data_prevista = $model->solic_data_prevista; }
                if(!empty($posted['solic_prioridade'])) { $forum->for_prioridade = $model->solic_prioridade; }
                $forum->save(false);
            }
            // return ajax json encoded response and exit
            echo $out;

            //Envia e-mail para o solicitante
            $id = $model->solic_id;
            $this->enviarEmailSolicitante($id);

            Yii::$app->session->setFlash('info', '<b>SUCESSO!</b> Informações Atualizadas!</b>');
            return $this->redirect(['index']);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Solicitacao model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $session = Yii::$app->session;
        $model = $this->findModel($id);
        $modelsForums = $model->forums;

            return $this->render('view', [
                'model' => $model,
                'modelsForums' => $modelsForums,
            ]);
    }

    public function actionGerarSuporte()
    {
        $model = new Solicitacao();
 
        if ($model->load(Yii::$app->request->post())) {
                return $this->redirect(['create', 'solic_tipo' => $model->solic_tipo]);
            }
            return $this->renderAjax('gerar-suporte', [
                'model' => $model,
            ]);
    }

    public function actionInserirMensagem($id)
    {
        $session = Yii::$app->session;
        $model = $this->findModel($id);
        $forum = new Forum();

        $forum->solicitacao_id = $model->solic_id;
        $forum->for_usuario_id = $session['sess_codusuario'];
        $forum->for_data = date('Y-m-d H:i');
        
        $situacao = Situacao::find()->all();

        if ($forum->load(Yii::$app->request->post()) && $forum->save()) {
            ///--------salva os anexos
            $model->file = UploadedFile::getInstances($model, 'file');
            $subdiretorio = "uploads/solicitacoes/" . $model->solic_id;
            if(!file_exists($subdiretorio)) {
                if(!mkdir($subdiretorio, 0777, true));
            }
            if ($model->file && $model->validate()) {
                foreach ($model->file as $file) {
                    $file->saveAs($subdiretorio.'/'. $file->baseName . '.' . $file->extension);
                    $model->save();
                    }
            }
        	if($forum->save()) {
        		if(!empty($forum->situacao_id)) { $model->situacao_id = $forum->situacao_id; }
        		if(!empty($forum->for_usuario_suporte)) { $model->solic_usuario_suporte = $forum->for_usuario_suporte; }
        		if(!empty($forum->for_data_prevista)) { $model->solic_data_prevista = $forum->for_data_prevista; }
        		if(!empty($forum->for_prioridade)) { $model->solic_prioridade = $forum->for_prioridade; }
				$model->save();
			}

        	Yii::$app->session->setFlash('success', '<b>SUCESSO! </b> Suporte Atualizado!');
                return $this->redirect(['view', 'id' => $model->solic_id]);
            }
            return $this->renderAjax('forum/_form', [
                'forum' => $forum,
                'model' => $model,
                'situacao' => $situacao,
            ]);
    }

    /**
     * Creates a new Solicitacao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $session = Yii::$app->session;
        $model = new Solicitacao();

        $model->solic_tipo = $_GET['solic_tipo']; //tipo de solicitação de suporte selecionado
        $model->solic_unidade_solicitante = $session['sess_codunidade'];
        $model->solic_usuario_solicitante = $session['sess_codusuario'];
        $model->solic_data_solicitacao = date('Y-m-d');
        $model->solic_prioridade = 'Normal';
        $model->situacao_id = 1;
        
        $sistemas = Sistemas::find()->orderBy('sist_descricao')->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            ///--------salva os anexos
            $model->file = UploadedFile::getInstances($model, 'file');
            $subdiretorio = "uploads/solicitacoes/" . $model->solic_id;
            if(!file_exists($subdiretorio)) {
                if(!mkdir($subdiretorio, 0777, true));
            }
            if ($model->file && $model->validate()) {
                foreach ($model->file as $file) {
                    $file->saveAs($subdiretorio.'/'. $file->baseName . '.' . $file->extension);
                    $model->save();
                    }
            }
            Yii::$app->session->setFlash('success', '<b>SUCESSO!</b> Suporte criado, por favor, aguarde que um técnico irá atendê-lo.</b>');
            return $this->redirect(['view', 'id' => $model->solic_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'sistemas' => $sistemas,
        ]);
    }

    /**
     * Updates an existing Solicitacao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->solic_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Solicitacao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
