<?php

namespace jarrus90\User\Controllers;

class AuthController extends \jarrus90\Core\Web\Controllers\FrontController {
    public function actionLogin(){
        return $this->render('login');
    }
}
