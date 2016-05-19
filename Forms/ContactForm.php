<?php

namespace jarrus90\User\Forms;

class ContactForm extends \jarrus90\Core\Models\Model {

    /** @var string User name */
    public $name;

    /** @var string User email */
    public $mail;

    /** @var string Message text */
    public $message;
    
    /** @var string Message text */
    public $phone;    

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            [['name', 'mail', 'message', 'phone'], 'required'],
            ['mail', 'email'],
        ];
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => \Yii::t('user', 'Name'),
            'mail' => \Yii::t('user', 'Email'),
            'message' => \Yii::t('user', 'Message'),
        ];
    }

    public function __construct($contactModel) {
        $this->_model = $contactModel;
        $this->setAttributes([
            'name' => $contactModel->name,
            'mail' => $contactModel->mail,
            'message' => $contactModel->message,
            'phone' => $contactModel->phone,
                ], false);
        parent::__construct();
    }

    /**
     * @return array customized attribute labels
     */
    public function save() {
        if ($this->validate()) {
            $this->_model->name = $this->cleanTextinput($this->name);
            $this->_model->mail = $this->cleanTextinput($this->mail);
            $this->_model->message = $this->cleanTextinput($this->message);
            $this->_model->phone = $this->cleanTextinput($this->phone);
            if ($this->_model->save()) {
                return $this->_model;
            }
        }
        return false;
    }

}
