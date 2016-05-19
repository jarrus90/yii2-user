<?php

namespace jarrus90\User\Controllers;

class AuthController extends \jarrus90\Core\Web\Controllers\FrontController {
    
    use \jarrus90\Core\Traits\AjaxValidationTrait;
    
    public function actionLogin(){
        $this->view->title = Yii::t('user', 'Logg in');
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
        $formView = $this->renderPartial('@app/modules/User/views/auth/login', [
            'model' => $loginForm,
            'module' => $this->module
        ]);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('@app/modules/User/views/form-popup', [
                        'content' => $formView
            ]);
        }
        return $this->render('@app/modules/User/views/form-inline', [
                    'content' => $formView
        ]);
    }
}
