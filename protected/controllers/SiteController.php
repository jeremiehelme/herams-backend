<?php
    namespace prime\controllers;
    use app\components\Html;
    use prime\components\Controller;
    use prime\models\ar\Tool;
    use yii\captcha\CaptchaAction;
    use yii\filters\AccessControl;
    use yii\helpers\ArrayHelper;
    use yii\helpers\FileHelper;
    use yii\web\Response;
    use yii\web\Session;
    use yii\web\User;

    class SiteController extends Controller
    {

        public function actionAbout()
        {
            $this->layout = 'main';
            return $this->render('about');
        }

        public function actionLogout()
        {
            $this->layout = 'logout';
            return $this->render('logout');
        }

        public function actionIndex(User $user)
        {
            if($user->id !== null) {
                return $this->redirect(['/marketplace']);
            } else {
                return $this->redirect(['/site/about']);
            }
        }

        public function actionTextImage(Response $response, $text)
        {
            $text = filter_var($text, FILTER_SANITIZE_STRING);
            $response->headers->set('Content-Type', FileHelper::getMimeTypeByExtension('.svg'));
            $response->format = Response::FORMAT_RAW;
            return Html::textImage($text);
        }

        public function actions()
        {
            return [
                'captcha' => [
                    'class' => CaptchaAction::class,
                    'fixedVerifyCode' => php_sapi_name() == 'cli-server' ? 'test' : null
                ]
            ];
        }

        public function behaviors()
        {
            return ArrayHelper::merge(parent::behaviors(),
                [
                    'access' => [
                        'class' => AccessControl::class,
                        'rules' => [
                            [
                                'allow' => 'true',
                                'actions' => ['captcha', 'about', 'index', 'logout']
                            ],
                            [
                                'allow' => 'true',
                                'roles' => ['@']
                            ]
                        ]
                    ]
                ]
            );
        }
    }
