<?php


namespace prime\controllers\element;


use prime\components\NotificationService;
use prime\models\ar\Element;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Update extends Action
{

    public function run(
        Request $request,
        NotificationService $notificationService,
        User $user,
        int $id
    ) {
        $model = Element::findOne(['id' => $id]);

        if (!isset($model)) {
            throw new NotFoundHttpException();
        }

        if (!$user->can(Permission::PERMISSION_ADMIN, $model->page->project)) {
            throw new ForbiddenHttpException();
        }

        if ($request->isPut) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $notificationService->success(\Yii::t('app', "Element updated"));

                return $this->controller->refresh();
            }
        }

        $codeOptions = [];
        foreach($model->page->project->survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                if ($question->getAnswers() !== null) {
                    $text = strip_tags($question->getText());
                    $codeOptions[$question->getTitle()] = "{$text} ({$question->getTitle()})";
                }
            }
        }

        return $this->controller->render('update', [
            'model' => $model,
            'codeOptions' => $codeOptions,
            'project' => $model->page->project,
            'page' => $model->page
        ]);
    }

}