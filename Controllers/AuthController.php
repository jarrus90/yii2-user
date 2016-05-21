<?php

namespace jarrus90\User\Controllers;

use Yii;
class AuthController extends \jarrus90\Core\Web\Controllers\FrontController {
    
    use \jarrus90\Core\Traits\AjaxValidationTrait;
    
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    
    public function actionLogin(){
        $this->view->title = Yii::t('user', 'Log in');
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $loginForm = new \jarrus90\User\Forms\LoginForm();
        $this->performAjaxValidation($loginForm);
        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            $redirect = Yii::$app->request->get('redirect');
            if($redirect){
                $url = base64_decode($redirect);
                if(Url::isRelative($url)) {
                    return $this->redirect($url);
                }
            }
            return $this->goBack(Yii::$app->request->referrer);
        }
        $formView = $this->renderPartial('@jarrus90/User/views/auth/login', [
            'model' => $loginForm,
            'module' => $this->module
        ]);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('@jarrus90/User/views/form-popup', [
                        'content' => $formView
            ]);
        }
        return $this->render('@jarrus90/User/views/form-inline', [
                    'content' => $formView
        ]);
    }
    
    public function actionRegister() {
        $this->view->title = Yii::t('user', 'Signup');
        $model = new \jarrus90\User\Forms\RegistrationForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('@jarrus90/User/views/auth/registration', [ 'registrationForm' => $model]);
        
    }
    public function actionResend() {
        
        
    }
}
