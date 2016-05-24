<?php

namespace jarrus90\User\traits;

use jarrus90\User\Module;

/**
 * Trait ModuleTrait
 * @property-read Module $module
 * @package jarrus90\User\traits
 */
trait ModuleTrait {

    /**
     * @return Module
     */
    public function getModule() {
        return \Yii::$app->getModule('user');
    }

}
