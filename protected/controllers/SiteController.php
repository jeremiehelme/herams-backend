<?php
    namespace prime\controllers;
    use prime\controllers\site\WorldMap;
    use yii\bootstrap\Html;
    use prime\components\Controller;
    use prime\interfaces\TicketingInterface;
    use yii\captcha\CaptchaAction;
    use yii\filters\AccessControl;
    use yii\helpers\ArrayHelper;

    class SiteController extends Controller
    {
        public function actionLimeSurvey(
            TicketingInterface $limesurveySSo,
            ?string $error = null
        ) {
            if (isset($error)) {
                echo Html::tag('pre', htmlentities($error));
                return;
            }
            $limesurveySSo->loginAndRedirectCurrentUser();
        }

        public function actionLogout()
        {	
            $this->layout = 'logout';	
            return $this->render('logout');	
        }

        public function actions()
        {
            return [
                'captcha' => [
                    'class' => CaptchaAction::class,
                    'fixedVerifyCode' => php_sapi_name() == 'cli-server' ? 'test' : null
                ],
                'world-map' => [
                    'class' => WorldMap::class
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
                                'actions' => ['captcha', 'logout']
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
