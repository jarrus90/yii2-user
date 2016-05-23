<?php

namespace jarrus90\User\Controllers;

use Yii;


class PanelController extends \jarrus90\Core\Web\Controllers\FrontController {

    use \jarrus90\Core\Traits\AjaxValidationTrait;
    
    public function actionIndex() {
        
        return $this->render('@jarrus90/User/views/panel/index', [
            'title' => Yii::t('user', 'Panel')
        ]);
    }
    
    public function actionPrivate() {
        $modelEmail = new \jarrus90\User\Forms\PrivateEmailSettings();
        $this->performAjaxValidation($modelEmail);
        if ($modelEmail->load(Yii::$app->request->post()) && $modelEmail->save()) {
            Yii::$app->session->setFlash('success', Yii::t('user', 'Your account details have been updated'));
            return $this->refresh();
        }

        $modelPassword = new \jarrus90\User\Forms\PrivatePasswordSettings();
        $this->performAjaxValidation($modelPassword);
        if ($modelPassword->load(Yii::$app->request->post()) && $modelPassword->save()) {
            Yii::$app->session->setFlash('success', Yii::t('user', 'Your account details have been updated'));
            return $this->refresh();
        }
        
        $modelPhone = new \jarrus90\User\Forms\PrivatePhoneSettings();
        $this->performAjaxValidation($modelPhone);
        if ($modelPhone->load(Yii::$app->request->post()) && $modelPhone->save()) {
            Yii::$app->session->setFlash('success', Yii::t('user', 'Your account details have been updated'));
            return $this->refresh();
        }
        return $this->render('@jarrus90/User/views/panel/private', [
                    'modelEmail' => $modelEmail,
                    'modelPassword' => $modelPassword,
                    'modelPhone' => $modelPhone,
                    'title' => Yii::t('user', 'Private settings'),
                    'user' => Yii::$app->user->identity,
        ]);
        
    }
}
