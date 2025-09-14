<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;

class CategoriaController extends ActiveController
{
    public $modelClass = 'app\models\Categoria';
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }
}