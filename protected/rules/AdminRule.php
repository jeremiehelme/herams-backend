<?php
declare(strict_types=1);

namespace prime\rules;


use prime\components\AuthManager;
use prime\models\permissions\Permission;
use SamIT\abac\interfaces\AccessChecker;
use SamIT\abac\interfaces\Environment;
use SamIT\abac\interfaces\SimpleRule;
use SamIT\abac\values\Authorizable;

class AdminRule implements SimpleRule
{
    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'you have global admin permissions';
    }

    /**
     * @inheritDoc
     */
    public function execute(
        object $source,
        object $target,
        string $permission,
        Environment $environment,
        AccessChecker $accessChecker
    ): bool {
        /** @var AuthManager $authManager */
        $authManager = \Yii::$app->authManager;
        return  !$target instanceof Authorizable
            && $accessChecker->check($source, $environment['globalAuthorizable'], Permission::PERMISSION_ADMIN);
    }
}