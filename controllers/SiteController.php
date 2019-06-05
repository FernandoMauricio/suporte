<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\solicitacao\Solicitacao;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        if($session['sess_codunidade'] == 1){ //Se for da GTI

        $aguardAtendimento = Solicitacao::find()->where(['situacao_id' => 1])->count();
        $emProcesso = Solicitacao::find()->where(['IN', 'situacao_id', [2,3,4,5,8]])->count();
        $atrasados = Solicitacao::find()->where(['<', new \yii\db\Expression('DATEDIFF(solic_data_prevista, NOW())'), 0])->andWhere(['NOT IN', 'situacao_id', [6,7]])->count();
        $finalizadosTecnico = Solicitacao::find()->where(['situacao_id' => 7])->count();

        $sql = 'SELECT COUNT(*) / (SELECT count(*) FROM `solicitacao`) * 100 AS countAtendimento FROM `solicitacao` WHERE `situacao_id`=1';
        $aguardAtendimentoPorcent = Solicitacao::findBySql($sql)->one();

        $sql = 'SELECT COUNT(*) / (SELECT count(*) FROM `solicitacao`) * 100 AS countAtendimento FROM `solicitacao` WHERE `situacao_id`=2';
        $emProcessoPorcent = Solicitacao::findBySql($sql)->one();

        $sql = 'SELECT COUNT(*) / (SELECT count(*) FROM `solicitacao`) * 100 AS countAtendimento FROM `solicitacao` WHERE (DATEDIFF(solic_data_prevista, NOW()) < 0) AND (`situacao_id` NOT IN (6, 7))';
        $atrasadosPorcent = Solicitacao::findBySql($sql)->one();

        $sql = 'SELECT COUNT(*) / (SELECT count(*) FROM `solicitacao`) * 100 AS countAtendimento FROM `solicitacao` WHERE `situacao_id`=7';
        $finalizadosTecnicoPorcent = Solicitacao::findBySql($sql)->one();

    }else{
        $aguardAtendimento = Solicitacao::find()->where(['situacao_id' => 1, 'solic_unidade_solicitante' => $session['sess_codunidade']])->count();
        $emProcesso = Solicitacao::find()->where(['situacao_id' => 2, 'solic_unidade_solicitante' => $session['sess_codunidade']])->count();
        $atrasados = Solicitacao::find()->where(['<', new \yii\db\Expression('DATEDIFF(solic_data_prevista, NOW())'), 0])->andWhere(['NOT IN', 'situacao_id', [6,7]])->andWhere(['solic_unidade_solicitante' => $session['sess_codunidade']])->count();
        $finalizadosTecnico = Solicitacao::find()->where(['situacao_id' => 7, 'solic_unidade_solicitante' => $session['sess_codunidade']])->count();  

        $sql = 'SELECT COUNT(*) / (SELECT count(*) FROM `solicitacao`) * 100 AS countAtendimento FROM `solicitacao` WHERE `situacao_id`=1 AND `solic_unidade_solicitante` = '.$session['sess_codunidade'].'';
        $aguardAtendimentoPorcent = Solicitacao::findBySql($sql)->one();

        $sql = 'SELECT COUNT(*) / (SELECT count(*) FROM `solicitacao`) * 100 AS countAtendimento FROM `solicitacao` WHERE `situacao_id`=2 AND `solic_unidade_solicitante` = '.$session['sess_codunidade'].'';
        $emProcessoPorcent = Solicitacao::findBySql($sql)->one();

        $sql = 'SELECT COUNT(*) / (SELECT count(*) FROM `solicitacao`) * 100 AS countAtendimento FROM `solicitacao` WHERE (DATEDIFF(solic_data_prevista, NOW()) < 0) AND (`situacao_id` NOT IN (6, 7)) AND `solic_unidade_solicitante` = '.$session['sess_codunidade'].'';
        $atrasadosPorcent = Solicitacao::findBySql($sql)->one();

        $sql = 'SELECT COUNT(*) / (SELECT count(*) FROM `solicitacao`) * 100 AS countAtendimento FROM `solicitacao` WHERE `situacao_id`=7 AND `solic_unidade_solicitante` = '.$session['sess_codunidade'].'';
        $finalizadosTecnicoPorcent = Solicitacao::findBySql($sql)->one();

    }

        return $this->render('index', [
            'emProcesso' => $emProcesso,
            'aguardAtendimento' => $aguardAtendimento,
            'atrasados' => $atrasados,
            'finalizadosTecnico' => $finalizadosTecnico,
            'aguardAtendimentoPorcent' => $aguardAtendimentoPorcent,
            'emProcessoPorcent' => $emProcessoPorcent,
            'atrasadosPorcent' => $atrasadosPorcent,
            'finalizadosTecnicoPorcent' => $finalizadosTecnicoPorcent,
        ]);
    }

    public function actionVersao()
    {
        return $this->render('versao');
    }
    
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
