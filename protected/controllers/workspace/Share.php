<?php


namespace prime\controllers\workspace;


use prime\components\NotificationService;
use prime\models\ar\Workspace;
use prime\models\forms\Share as ShareForm;
use prime\models\permissions\Permission;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Share extends Action
{
    public function run(
        NotificationService $notificationService,
        Request $request,
        AuthManager $abacManager,
        User $user,
        int $id
    )
    {
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        if (!($user->can(Permission::PERMISSION_SHARE, $workspace))) {
            throw new ForbiddenHttpException();
        }
        $model = new ShareForm($workspace, $abacManager, $user->identity, [
            'permissions' => [
                Permission::PERMISSION_WRITE => \Yii::t('app', 'Manage the underlying response data'),
                Permission::PERMISSION_ADMIN => \Yii::t('app', 'Full access, includes editing the workspace properties, token and response data'),
            ]
        ]);

        if ($request->isPost) {
            if ($model->load($request->bodyParams)) {
                $model->createRecords();
                $notificationService->success(\Yii::t('app',
                    "Workspace <strong>{modelName}</strong> has been shared with: <strong>{users}</strong>",
                    [
                        'modelName' => $workspace->title,
                        'users' => implode(', ', array_map(function ($model) {
                            return $model->name;
                        }, $model->getUsers()->all()))
                    ])

                );
                return $this->controller->refresh();
            }

        }


        return $this->controller->render('share', [
            'model' => $model,
            'workspace' => $workspace
        ]);
    }
}