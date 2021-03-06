<?php


namespace prime\controllers;


use prime\actions\DeleteAction;
use prime\components\Controller;
use prime\controllers\workspace\Configure;
use prime\controllers\workspace\Create;
use prime\controllers\workspace\Download;
use prime\controllers\workspace\Import;
use prime\controllers\workspace\Limesurvey;
use prime\controllers\workspace\Refresh;
use prime\controllers\workspace\Share;
use prime\controllers\workspace\Update;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\User;

class WorkspaceController extends Controller
{
    public $layout = '//admin';

    public function actions()
    {
        return [
            'configure' => Configure::class,
            'limesurvey' => Limesurvey::class,
            'update' => Update::class,
            'create' => Create::class,
            'share' => Share::class,
            'import' => Import::class,
            'download' => Download::class,
            'refresh' => Refresh::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => Workspace::find(),
                'permission' => function(User $user, Workspace $model): bool {
                    return $user->can(Permission::PERMISSION_ADMIN, $model)
                        || $user->can(Permission::PERMISSION_WRITE, $model->project);
                },
                'redirect' => function(Workspace $workspace) {
                    return ['/project/workspaces', 'id' => $workspace->tool_id];
                }
            ],

        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'verb' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'create' => ['get', 'post']
                    ]
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }



}