<?php

namespace jarrus90\User\Models;

use Yii;

/**
 * This is the model class for table "user_rbac_assignment".
 *
 * @property string $item_name
 * @property integer $user_id
 * @property integer $created_at
 */
class UserRbacAssignment extends \yii\db\ActiveRecord {

    /**
     * Table name
     * @return string
     */
    public static function tableName() {
        return Yii::$app->authManager->assignmentTable;
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            [['item_name', 'user_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['item_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'item_name' => Yii::t('user', 'Item Name'),
            'user_id' => Yii::t('user', 'User ID'),
            'created_at' => Yii::t('user', 'Created At'),
        ];
    }

}
