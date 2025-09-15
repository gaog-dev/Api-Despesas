<?php

namespace modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use app\models\Despesa;

class DespesaController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    // Listar todas as despesas
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

    // Criar uma nova despesa
    public function actionCreate()
    {
        $model = new Despesa();

        // Carregar dados da requisição
        $model->descricao = Yii::$app->request->post('descricao');
        $model->valor = Yii::$app->request->post('valor');
        $model->data = Yii::$app->request->post('data');
        $model->categoria = Yii::$app->request->post('categoria');
        $model->user_id = Yii::$app->user->id;

        if ($model->save()) {
            return [
                'success' => true,
                'message' => 'Despesa adicionada com sucesso!',
                'data' => $model
            ];
        } else {
            return [
                'success' => false,
                'errors' => $model->errors
            ];
        }
    }

    // Atualizar uma despesa
    public function actionUpdate($id)
    {
        $model = Despesa::findOne($id);

        if (!$model) {
            return [
                'success' => false,
                'message' => 'Despesa não encontrada'
            ];
        }

        // Carregar dados da requisição
        $model->descricao = Yii::$app->request->post('descricao');
        $model->valor = Yii::$app->request->post('valor');
        $model->data = Yii::$app->request->post('data');
        $model->categoria = Yii::$app->request->post('categoria');

        if ($model->save()) {
            return [
                'success' => true,
                'message' => 'Despesa atualizada com sucesso!',
                'data' => $model
            ];
        } else {
            return [
                'success' => false,
                'errors' => $model->errors
            ];
        }
    }

    // Excluir uma despesa
    public function actionDelete($id)
    {
        $model = Despesa::findOne($id);

        if (!$model) {
            return [
                'success' => false,
                'message' => 'Despesa não encontrada'
            ];
        }

        if ($model->delete()) {
            return [
                'success' => true,
                'message' => 'Despesa excluída com sucesso!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Erro ao excluir despesa'
            ];
        }
    }
}