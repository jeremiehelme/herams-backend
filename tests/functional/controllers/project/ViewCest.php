<?php


namespace prime\tests\functional\controllers\project;

use prime\models\ar\Page;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\tests\FunctionalTester;

class ViewCest
{

    public function testView(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/view', 'id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testViewWithRead(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);
        $I->amOnPage(['project/view', 'id' => $project->id]);
        $I->seeResponseCodeIs(404);
    }

    public function testViewWithPageAndRead(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $page = new Page();
        $page->title = 'Main page';
        $page->tool_id = $project->id;
        $I->save($page);
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);
        $I->amOnPage(['project/view', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->assertSame($page->title, $I->grabTextFrom(['class' => 'header']));
    }

    public function testViewBadSurvey(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $project->base_survey_eid = 11111;
        $I->save($project);
        $page = new Page();
        $page->title = 'Main page';
        $page->tool_id = $project->id;
        $I->save($page);
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);
        $I->amOnPage(['project/view', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->assertSame($page->title, $I->grabTextFrom(['class' => 'header']));
    }
    public function testWrongPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $page = new Page();
        $page->title = 'Main page';
        $page->tool_id = $project->id;
        $I->save($page);
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);
        $I->amOnPage(['project/view', 'id' => $project->id, 'page_id' => $page->id + 1]);
        $I->seeResponseCodeIs(404);
    }

    public function testOtherPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);

        $page = new Page();
        $page->title = 'Main page';
        $page->tool_id = $project->id;
        $I->save($page);

        $otherPage = new Page();
        $otherPage->title = 'Other page';
        $otherPage->tool_id = $project->id;
        $I->save($otherPage);

        $I->amOnPage(['project/view', 'id' => $project->id, 'page_id' => $otherPage->id]);
        $I->seeResponseCodeIs(200);
        $I->assertSame($otherPage->title, $I->grabTextFrom(['class' => 'header']));
    }

    public function testChildPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);

        $page = new Page();
        $page->title = 'Main page';
        $page->tool_id = $project->id;
        $I->save($page);

        $child = new Page();
        $child->parent_id = $page->id;
        $child->title = 'Child page';
        $child->tool_id = $project->id;
        $I->save($child);

        $I->amOnPage(['project/view', 'id' => $project->id, 'page_id' => $child->id, 'parent_id' => $child->parent_id]);
        $I->seeResponseCodeIs(200);
        $I->assertSame($child->title, $I->grabTextFrom(['class' => 'header']));
    }
}