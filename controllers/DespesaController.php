<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\services\DespesaService;
use app\models\Despesa;

class DespesaController extends ActiveController
{
    public $modelClass = 'app\models\Despesa';
    private $despesaService;
    
    public function init()
    {
        parent::init();
        $this->despesaService = new DespesaService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }

    public function actionIndex()
    {
        // Verificar se o usuário está logado
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Usuário não está logado'];
        }
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
        // Verificar se o usuário está logado
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Usuário não está logado'];
        }

        $model = Despesa::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if (!$model) {
            throw new yii\web\NotFoundHttpException('Despesa não encontrada');
        }
        return $model;
    }
    
    public function actionCreate()
    {
// Verificar se o usuário está logado
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Usuário não está logado'];
        }

        $model = new Despesa();

        // Obter os dados do corpo da requisição JSON
        $rawData = Yii::$app->request->getRawBody();
        $postData = json_decode($rawData, true);

        if ($postData) {
            $model->descricao = $postData['descricao'] ?? null;
            $model->valor = $postData['valor'] ?? null;
            $model->data = $postData['data'] ?? null;
            $model->categoria = $postData['categoria'] ?? null;
        } else {
            // Tentar carregar via POST normal (fallback)
            $model->load(Yii::$app->request->post(), '');
        }

        $model->user_id = Yii::$app->user->id;

        if ($model->save()) {
            return ['success' => true, 'message' => 'Despesa adicionada com sucesso!', 'data' => $model];
        } else {
            return ['success' => false, 'errors' => $model->errors];
        }
    }
    
    public function actionUpdate($id)
    {
        // Verificar se o usuário está logado
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Usuário não está logado'];
        }

        $model = Despesa::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if (!$model) {
            return ['success' => false, 'message' => 'Despesa não encontrada'];
        }

        // Obter os dados do corpo da requisição JSON
        $rawData = Yii::$app->request->getRawBody();
        $postData = json_decode($rawData, true);

        if ($postData) {
            $model->descricao = $postData['descricao'] ?? $model->descricao;
            $model->valor = $postData['valor'] ?? $model->valor;
            $model->data = $postData['data'] ?? $model->data;
            $model->categoria = $postData['categoria'] ?? $model->categoria;
        } else {
            // Tentar carregar via POST normal (fallback)
            $model->load(Yii::$app->request->post(), '');
        }

        if ($model->save()) {
            return ['success' => true, 'message' => 'Despesa atualizada com sucesso!', 'data' => $model];
        } else {
            return ['success' => false, 'errors' => $model->errors];
        }
    }
    
    public function actionDelete($id)
    {
        // Verificar se o usuário está logado
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Usuário não está logado'];
        }

        $model = Despesa::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
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