<?php
namespace app\commands;

use yii\console\Controller;
use app\models\User;

class TestUserController extends Controller
{
    public function actionCreate($username, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->setPassword($password);
        $user->generateAuthKey();
        
        if ($user->save()) {
            echo "Usuário criado com sucesso!\n";
            echo "Username: $username\n";
            echo "Senha: $password\n";
        } else {
            echo "Erro ao criar usuário:\n";
            print_r($user->errors);
        }
    }
}