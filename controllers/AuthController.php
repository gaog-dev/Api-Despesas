<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\SignupForm;
use app\models\LoginForm;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        $model->load(Yii::$app->request->post(), '');
        
        if ($model->signup()) {
            return ['status' => 'success', 'message' => 'User created successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Registration failed.', 'errors' => $model->errors];
        }
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->post(), '');
        
        $user = $model->login();
        if ($user) {
            $token = Yii::$app->jwt->generateToken($user->id);
            return ['status' => 'success', 'token' => $token];
        } else {
            return ['status' => 'error', 'message' => 'Login failed.', 'errors' => $model->errors];
        }
    }
}