<?php

/**
 * User module
 *
 * UsersController
 * 
 * @package jarrus90\User\Controllers
 */

namespace jarrus90\User\Controllers;

class AdminController extends \jarrus90\Core\Web\Controllers\AdminController {

    public function actionList() {
        return $this->render('list');
    }

    public function actionView() {
        return $this->render('view');
    }

    public function actionEdit() {
        return $this->render('item');
    }

    public function actionRoles() {
        return $this->render('roles');
    }

}
