<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Despesa;
use app\models\LoginForm;
use app\models\SignupForm;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $model = new Despesa();
        return $this->render('index', [
            'model' => $model,
        ]);
    }
    
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Gerar token JWT
            $token = Yii::$app->Jwt->generateToken(Yii::$app->user->id);
            
            // Armazenar token no localStorage via JavaScript
            Yii::$app->response->content = $this->renderPartial('login', [
                'model' => $model,
            ]);
            
            $script = <<<JS
localStorage.setItem('token', '$token');
window.location.href = '/';
JS;
            
            $this->view->registerJs($script);
            return;
        }
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    public function actionSignup()
    {
        $model = new SignupForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
            return $this->redirect(['site/login']);
        }
        
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

    // Limpa o token no navegador
        $script = <<<JS
        localStorage.removeItem('token');
        window.location.href = '/site/login'; // ou /site/signup
    JS;

        Yii::$app->response->format = yii\web\Response::FORMAT_HTML;
        return "<script>{$script}</script>";
    }
}