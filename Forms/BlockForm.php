<?php

namespace jarrus90\User\Forms;

class BlockForm extends \jarrus90\Core\Models\Model {

    /** @var string reason */
    public $reason;
    private $_user = false;

    /**
     * Returns custom form name
     * @return string
     */
    public function formName() {
        return 'blockuser-' . $this->_user->id;
    }

    public function getUser() {
        return $this->_user;
    }

    /**
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct($user) {
        $this->_user = $user;
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            'reasonTrim' => ['reason', 'trim'],
        ];
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'reason' => \Yii::t('user', 'Reason')
        ];
    }

    public function save() {
        $this->_user->block();
        $this->_user->updateAttributes(['blocked_reason' => $this->reason]);
        return true;
    }

}
