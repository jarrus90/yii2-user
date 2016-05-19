<?php

namespace jarrus90\User\Forms;

use Yii;

/**
 * Model for collecting data on password recovery.
 *
 * @property \jarrus90\User\Module $module
 *
 */
class RoleForm extends \jarrus90\Core\Models\Model {

    /** @var string */
    public $name;
    /** @var string */
    public $description;
    /** @var string */
    public $permissions;

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'name' => Yii::t('user', 'Role name'),
            'description' => Yii::t('user', 'Role description'),
            'permissions' => Yii::t('user', 'Permissions'),
        ];
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            'requiredFields' => [['name', 'description'], 'required'],
            'safeFields' => [['permissions'], 'safe'],
            'namePattern' => ['name', 'match', 'pattern' => '/^[a-zA-Z0-9_\/-]+$/', 'message' => Yii::t('user', 'Role name needs to contain only letters a-z, A-Z, - and _')]
        ];
    }

    /**
     * Returns custom form name
     * @return string
     */
    public function formName() {
        return 'role';
    }

    public function roleExists() {
        $role = Yii::$app->authManager->getRole($this->name);
        if ($role) {
            $this->addError('name', Yii::t('user', 'Role {rolename} already created', ['rolename' => $this->name]));
            return true;
        }
        return false;
    }

}
