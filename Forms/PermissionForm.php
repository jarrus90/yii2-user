<?php

namespace jarrus90\User\Forms;

use Yii;

/**
 * Model for collecting data on password recovery.
 *
 * @property \jarrus90\User\Module $module
 *
 */
class PermissionForm extends \jarrus90\Core\Models\Model {

    /** 
     * @var string Permission name
     */
    public $name;

    /** 
     * @var string Permission description
     */
    public $description;

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'name' => Yii::t('user', 'Permission name'),
            'description' => Yii::t('user', 'Permission description'),
        ];
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            'requiredFields' => [['name', 'description'], 'required'],
            'descriptionPattern' => ['description', 'match', 'pattern' => '/^[a-zA-Z0-9_\/-]+$/', 'message' => Yii::t('user', 'Permission description needs to contain only letters a-z, A-Z, _ and /')],
        ];
    }

    /**
     * Returns custom form name
     * @return string
     */
    public function formName() {
        return 'permission';
    }

    public function permissionExists() {
        $permission = Yii::$app->authManager->getPermission($this->name);
        if ($permission) {
            $this->addError('name', Yii::t('user', 'Permission {permissionname} already created', ['permissionname' => $this->name]));
            return true;
        }
        return false;
    }

}
