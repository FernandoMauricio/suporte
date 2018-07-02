<?php

namespace app\controllers;

use Yii;
use app\models\base\Sistemas;
use app\models\base\Situacao;
use app\models\base\Usuario;
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

    /**
     * Lists all Solicitacao models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SolicitacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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

    public function actionInserirMensagem($id)
    {
        $session = Yii::$app->session;
        $model = $this->findModel($id);
        $forum = new Forum();

        $forum->solicitacao_id = $model->solic_id;
        $forum->for_usuario_id = $session['sess_codusuario'];
        $forum->for_data = date('Y-m-d H:i');
        
        $situacao = Situacao::find()->all();

        if ($forum->load(Yii::$app->request->post())) {
                return $this->redirect(['view', 'solic_tipo' => $forum->solic_tipo]);
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
