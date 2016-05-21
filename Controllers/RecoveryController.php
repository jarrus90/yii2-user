<?php

namespace jarrus90\User\Controllers;

use jarrus90\User\Forms\RecoveryForm;
use jarrus90\User\Models\Token;
use Yii;


class RecoveryController extends \jarrus90\Core\Web\Controllers\FrontController {

    use \jarrus90\Core\Traits\AjaxValidationTrait;
    
    public function actionRequest() {
        
        /*if(!Yii::$app->user->can('?')) {
            $this->goHome();
        }*/
        /** @var RecoveryForm $model */
        $model = Yii::createObject([
                    'class' => RecoveryForm::className(),
                    'scenario' => 'request',
        ]);
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->request->post()) && $model->sendRecoveryMessage()) {
            Yii::$app->session->setFlash('info', Yii::t('user', 'An email has been sent with instructions for resetting your password'));
            return $this->refresh();
        }

        return $this->render('@jarrus90/User/views/recovery/request', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays page where user can reset password.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset($id, $code) {
        if(!Yii::$app->user->can('?')) {
            $this->goHome();
        }
        /** @var Token $token */
        $token = Token::findOne(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY]);
        if ($token === null || $token->isExpired || $token->user === null) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.'));
            $model = null;
        } else {
            /** @var RecoveryForm $model */
            $model = Yii::createObject([
                        'class' => RecoveryForm::className(),
                        'scenario' => 'reset',
            ]);
            $this->performAjaxValidation($model);
            if ($model->load(Yii::$app->getRequest()->post()) && $model->resetPassword($token)) {

            }
        }
        return $this->render('@jarrus90/User/views/recovery/reset', [
                    'model' => $model,
        ]);
    }

}
