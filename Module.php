<?php

namespace jarrus90\User;

class Module extends \yii\base\Module {

    public $urlPrefix = 'user';
    
    public function init() {
        parent::init();
        $this->modules = [
            'admin' => [
                'class' => 'jarrus90\User\Admin\Module',
            ],
            'front' => [
                'class' => 'jarrus90\User\Frontend\Module',
            ],
        ];
    }
    
    public function getUrlRules(){
        return [];
    }

}
