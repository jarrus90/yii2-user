<?php

/**
 * Application configuration for unit tests.
 */
return yii\helpers\ArrayHelper::merge(
                require(__DIR__ . '/../_app/config/web.php'), []
);
