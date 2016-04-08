<?php

/**
 * Blog module
 *
 * AdminCommentController
 * 
 * @package app\modules\Blog\Controllers
 */

namespace jarrus90\User\Admin\Controllers;

class RolesController extends \jarrus90\CoreAdmin\Controllers\AdminController {

    public function actionList() {
        return $this->render('list');
    }

    public function actionEdit() {
        return $this->render('item');
    }

    public function actionCreate() {
        return $this->render('item');
    }

}
