<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\CryptForm;
use yii\web\Response;
class SiteController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
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
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
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


    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * Displays crypt page.
     *
     * @return string
     */
    public function actionCrypt()
    {
        $this->enableCsrfValidation = false;


        $form = new CryptForm();

        $crypt_methods  = $form->crypt_methods;
        $set_offset_by  = $form->set_offset_by;
        $rsa_key_size   = $form->rsa_key_size;
        $lang_list      = $form->lang_list;

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $InputString= $data['InputString'];
            $key= explode(":", $data['key']);
            $selected_method= explode(":", $data['selected_method']);
            $selected_lang= explode(":", $data['selected_lang']);
            $IsEncrypt= explode(":", $data['IsEncrypt']);
            $IsBrute= explode(":", $data['IsBrute']);
            $OffsetBy= explode(":", $data['OffsetBy']);

            $form->set_data($InputString,$key[0],$IsEncrypt[0],$selected_method[0],$selected_lang[0],$IsBrute[0],$OffsetBy[0]);
           // $form->parse_input();
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'result' => $form->crypt(),
               // 'error_code'=>$form->get_error_code()
            ];
        }

        return $this->render('crypt',
            ['form' => $form,'crypt_methods'=>$crypt_methods,'lang_list'=>$lang_list,'set_offset_by'=>$set_offset_by,'rsa_key_size'=>$rsa_key_size]
        );
    }
}
