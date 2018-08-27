<?php

namespace app\controllers;

use Yii;
use app\models\base\Sistemas;
use app\models\base\Situacao;
use app\models\base\Usuario;
use app\models\base\Unidade;
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

    public function actionFinalizarSuporte($id) 
    {
        $session = Yii::$app->session;
        $model = $this->findModel($id);

        $connection = Yii::$app->db;
        $connection->createCommand()
        ->update('solicitacao', [
            'solic_usuario_finalizacao' => $session['sess_nomeusuario'], 
            'situacao_id' => 6, //Solicitação Finalizada
            'solic_data_finalizacao' => date('Y-m-d H:i:s') 
        ], ['solic_id' => $model->solic_id])
        ->execute();

        $connection->createCommand()
        ->insert('forum', [
            'solicitacao_id' => $model->solic_id,
            'for_usuario_id' => $session['sess_codusuario'],
            'for_data' => date('Y-m-d H:i'),
            'situacao_id' => 6, //Solicitação Finalizada
        ])
        ->execute();

        //Envia e-mail a GTI informando a finalização do suporte pelo solicitante
        Yii::$app->runAction('email/enviar-email-suporte-finalizado', ['id' => $model->solic_id]);
        Yii::$app->session->setFlash('success', '<b>SUCESSO! </b> Suporte: ' . '<b>' .$model->solic_id. '</b> foi <b>finalizado!</b>');
        return $this->redirect(['index']);
    }

    public function actionFinalizarSuportePeloTecnico($id) 
    {
        $session = Yii::$app->session;
        $model = $this->findModel($id);

        $connection = Yii::$app->db;
        $connection->createCommand()
        ->update('solicitacao', [
            'solic_usuario_finalizacao' => $session['sess_nomeusuario'], 
            'situacao_id' => 7, //Finalizado Pelo Técnico
            'solic_data_finalizacao' => date('Y-m-d H:i:s') 
        ], ['solic_id' => $model->solic_id])
        ->execute();

        $connection->createCommand()
        ->insert('forum', [
            'solicitacao_id' => $model->solic_id,
            'for_usuario_id' => $session['sess_codusuario'],
            'for_data' => date('Y-m-d H:i'),
            'situacao_id' => 7, //Finalizado Pelo Técnico
        ])
        ->execute();

        //Envia e-mail a GTI informando a finalização do suporte pelo solicitante
        Yii::$app->runAction('email/enviar-email-suporte-finalizado', ['id' => $model->solic_id]);
        Yii::$app->session->setFlash('success', '<b>SUCESSO! </b> Suporte: ' . '<b>' .$model->solic_id. '</b> foi <b>finalizado!</b>');
        return $this->redirect(['index']);
    }

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
                //Caso o tipo for alterado para Equipamentos, a coluna Sistema ficará nulo
                if(!empty($posted['solic_tipo']) && $model->solic_tipo == 'Equipamentos') { 
                    $model->solic_sistema_id = NULL;
                }
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
                if(!empty($posted['solic_tipo'])) { $forum->for_tipo = $model->solic_tipo; }
                $forum->save(false);
            }
            // return ajax json encoded response and exit
            echo $out;
            //Envia e-mail para o solicitante
            Yii::$app->runAction('email/enviar-email-solicitante', ['id' => $model->solic_id]);
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
        $unidades = Unidade::find()->orderBy('uni_nomeabreviado')->all();
        $usuarios = Usuario::find()->where(['usu_codsituacao' => 1, 'usu_codtipo' => 2])->orderBy('usu_nomeusuario')->all();

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

            //Envia e-mail a GTI informando uma nova solicitação
            Yii::$app->runAction('email/enviar-email-solicitacao-criacao', ['id' => $model->solic_id]);
            Yii::$app->session->setFlash('success', '<b>SUCESSO!</b> Suporte criado, por favor, aguarde que um técnico irá atendê-lo em breve.</b>');
            return $this->redirect(['view', 'id' => $model->solic_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'sistemas' => $sistemas,
            'unidades' => $unidades,
            'usuarios' => $usuarios,
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
