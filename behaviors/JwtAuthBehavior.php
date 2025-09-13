<?php
namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\UnauthorizedHttpException;

class JwtAuthBehavior extends Behavior
{
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    public function beforeAction($event)
    {
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        
        if (!$authHeader || !preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            throw new UnauthorizedHttpException('You need to be authorized to access this endpoint.');
        }
        
        $token = $matches[1];
        $decoded = Yii::$app->jwt->validateToken($token);
        
        if (!$decoded) {
            throw new UnauthorizedHttpException('Invalid token.');
        }
        
        Yii::$app->user->setIdentity(User::findOne($decoded->userId));
    }
}