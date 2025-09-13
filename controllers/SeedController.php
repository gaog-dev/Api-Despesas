<?php
// commands/SeedController.php

namespace app\commands;

use yii\console\Controller;
use app\models\Categoria;

class SeedController extends Controller
{
    public function actionIndex()
    {
        $categorias = [
            ['nome' => 'Alimentação'],
            ['nome' => 'Transporte'],
            ['nome' => 'Moradia'],
            ['nome' => 'Saúde'],
            ['nome' => 'Educação'],
            ['nome' => 'Lazer'],
            ['nome' => 'Outros'],
        ];
        
        foreach ($categorias as $categoria) {
            $model = new Categoria();
            $model->attributes = $categoria;
            $model->save();
        }
        
        echo "Categorias adicionadas com sucesso!\n";
    }
}