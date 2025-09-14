<?php
namespace app\behaviors;

use Yii;
use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;
class JwtAuthBehavior extends AuthMethod
{
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $token = $matches[1];
            try {
                $data = Yii::$app->jwt->decode($token); // usando JwtComponent
                $identity = $user->loginByAccessToken($token, get_class($this));
                if ($identity !== null) {
                    return $identity;
                }
            } catch (\Exception $e) {
                throw new UnauthorizedHttpException('Token inválido: ' . $e->getMessage());
            }
        }

        return null;
    }
}