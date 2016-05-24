<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace jarrus90\User\Controllers;

use jarrus90\User\models\Assignment;
use Yii;
use yii\web\Controller;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class AssignmentController extends Controller {

    /**
     * Show form with auth items for user.
     * 
     * @param int $id
     */
    public function actionAssign($id) {
        $model = Yii::createObject([
                    'class' => Assignment::className(),
                    'user_id' => $id,
        ]);

        if ($model->load(\Yii::$app->request->post()) && $model->updateAssignments()) {
            
        }

        return \jarrus90\User\widgets\Assignments::widget([
                    'model' => $model,
        ]);
    }

}
