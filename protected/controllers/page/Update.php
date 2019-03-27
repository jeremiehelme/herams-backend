<?php


namespace prime\controllers\page;


use prime\models\ar\Page;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

class Update extends Action
{

    public function run(
        Request $request,
        Session $session,
        User $user,
        int $id

    ) {
        $page = Page::findOne(['id' => $id]);
        if (!isset($page)) {
            throw new NotFoundHttpException();
        }

        if (!$user->can(Permission::PERMISSION_ADMIN, $page->project)) {
            throw new ForbiddenHttpException();
        }

        if ($request->isPut) {
            if ($page->load($request->bodyParams) && $page->save()) {
                $session->setFlash(
                    'toolUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "Tool <strong>{$page->title}</strong> is updated.",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                return $this->controller->refresh();
            }
        }


        return $this->controller->render('update', [
            'page' => $page,
            'project' => $page->project
        ]);
    }

}