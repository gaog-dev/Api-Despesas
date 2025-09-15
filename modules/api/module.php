<?php

namespace modules\api;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'modules\api\controllers';

    public function init()
    {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }
}