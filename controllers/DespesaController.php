<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use app\models\Despesa;

class DespesaController extends ActiveController
{
    public $modelClass = 'app\models\Despesa';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['update'], $actions['delete'], $actions['view']);
        return $actions;
    }

    public function actionIndex()
    {
        $query = Despesa::find()->orderBy(['data' => SORT_DESC]);

        // Aplicar filtros se existirem
        if (Yii::$app->request->get('categoria')) {
            $query->andFilterWhere(['categoria' => Yii::$app->request->get('categoria')]);
        }
        if (Yii::$app->request->get('data_inicio')) {
            $query->andFilterWhere(['>=', 'data', Yii::$app->request->get('data_inicio')]);
        }
        if (Yii::$app->request->get('data_fim')) {
            $query->andFilterWhere(['<=', 'data', Yii::$app->request->get('data_fim')]);
        }

        return $query->all();
    }

    public function actionView($id)
    {
        $model = Despesa::findOne($id);
        if (!$model) {
            throw new yii\web\NotFoundHttpException('Despesa não encontrada');
        }
        return $model;
    }

    public function actionCreate()
    {
        $model = new Despesa();

        // Obter os dados do corpo da requisição
        $rawData = Yii::$app->request->getRawBody();

        // Verificar se há dados
        if (empty($rawData)) {
            return ['success' => false, 'message' => 'Nenhum dado recebido'];
        }

        // Converter para UTF-8 se necessário
        if (!mb_check_encoding($rawData, 'UTF-8')) {
            $rawData = mb_convert_encoding($rawData, 'UTF-8', 'ISO-8859-1');
        }

        // Decodificar JSON
        $postData = json_decode($rawData, true);

        // Verificar se o JSON foi decodificado corretamente
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'message' => 'JSON inválido: ' . json_last_error_msg()];
        }

        // Verificar se os dados necessários estão presentes
        if (!is_array($postData)) {
            return ['success' => false, 'message' => 'Formato de dados inválido'];
        }

        // Atribuir valores ao modelo
        $model->descricao = isset($postData['descricao']) ? trim($postData['descricao']) : '';
        $model->valor = isset($postData['valor']) ? floatval($postData['valor']) : 0;
        $model->data = isset($postData['data']) ? date('Y-m-d', strtotime($postData['data'])) : date('Y-m-d');

        // Tratar a categoria com mais cuidado
        if (isset($postData['categoria'])) {
            // Normalizar a categoria
            $categoria = trim($postData['categoria']);

            // Remover possíveis caracteres especiais ou invisíveis
            $categoria = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $categoria);

            // Garantir que a categoria seja uma das opções válidas
            $categoriasValidas = ['alimentação', 'transporte', 'lazer'];
            if (in_array($categoria, $categoriasValidas)) {
                $model->categoria = $categoria;
            } else {
                $model->categoria = 'alimentação';
            }
        } else {
            $model->categoria = 'alimentação';
        }

        // Para testes, usar um ID de usuário fixo
        $model->user_id = 1;

        // Validar e salvar
        if ($model->validate() && $model->save()) {
            return ['success' => true, 'message' => 'Despesa adicionada com sucesso!', 'data' => $model];
        } else {
            return ['success' => false, 'errors' => $model->errors];
        }
    }

    public function actionUpdate($id)
    {
        $model = Despesa::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Despesa não encontrada'];
        }

        // Obter os dados do corpo da requisição
        $rawData = Yii::$app->request->getRawBody();

        // Verificar se há dados
        if (!empty($rawData)) {
            // Decodificar JSON
            $postData = json_decode($rawData, true);

            // Verificar se o JSON foi decodificado corretamente
            if (json_last_error() === JSON_ERROR_NONE && is_array($postData)) {
                // Atribuir valores ao modelo com verificações de segurança
                if (isset($postData['descricao'])) $model->descricao = $postData['descricao'];
                if (isset($postData['valor'])) $model->valor = $postData['valor'];
                if (isset($postData['data'])) $model->data = $postData['data'];
                if (isset($postData['categoria'])) $model->categoria = $postData['categoria'];
            }
        }

        // Validar e salvar
        if ($model->validate() && $model->save()) {
            return ['success' => true, 'message' => 'Despesa atualizada com sucesso!', 'data' => $model];
        } else {
            return ['success' => false, 'errors' => $model->errors];
        }
    }

    public function actionDelete($id)
    {
        $model = Despesa::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Despesa não encontrada'];
        }

        if ($model->delete()) {
            return ['success' => true, 'message' => 'Despesa excluída com sucesso!'];
        } else {
            return ['success' => false, 'message' => 'Erro ao excluir despesa'];
        }
    }
}