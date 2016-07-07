<?php

/**
 * BaseMigration
 *
 * @package app\console\migrations
 */

namespace jarrus90\User\migrations;

use Yii;
use yii\db\Migration;
use yii\rbac\DbManager;
use yii\base\InvalidConfigException;

/**
 * Migration is the base class for representing a database migration.
 * Migration is designed to be used together with the "yii migrate" command.
 * Basically added functions to create and assign roles from migrations
 */
class RbacMigration extends Migration {

    /**
     * @var \yii\rbac\DbManager Auth manager application component.
     */
    protected $authManager;

    /**
     * Initialize migrations.
     * Calls parent init method, then loads current authManager instance.
     * 
     * @return DbManager
     * @throws yii\base\InvalidConfigException
     */
    public function init() {
        parent::init();
        $this->authManager = Yii::$app->getAuthManager();
        if (!$this->authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
    }

    /**
     * Create role.
     * If role already exists doesn nothing.
     *
     * @param string $name Role name
     * @param string $description Role description
     * @return \yii\rbac\Permission the new Permission object
     */
    function createRole($name, $description) {
        if ($user = $this->authManager->getRole($name)) {
            echo "{$name} role already exists\n";
        } else {
            $user = $this->authManager->createRole($name);
            $user->description = $description;
            $this->authManager->add($user);
            echo "{$name} role created\n";
        }
        return $user;
    }

    /**
     * Assigns child role to parent
     *
     * @param string $parent Parent role name
     * @param string $child Child role name
     */
    function assignChildRole($parent, $child) {
        if (!$this->authManager->hasChild($parent, $child)) {
            $this->authManager->addChild($parent, $child);
            echo "New child '{$child->name}' added to '{$parent->name}'\n";
        } else {
            echo "Role '{$child->name}' was already added to '{$parent->name}'\n";
        }
    }

}
