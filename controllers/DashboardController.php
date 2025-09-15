<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Despesa;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class DashboardController extends Controller
{
    public function actionDespesas()
    {
        // Apenas renderizar a view, sem processar formulário
        $model = new Despesa();

        // Lista despesas direto do banco
        $dataProvider = new ActiveDataProvider([
            'query' => Despesa::find()->orderBy(['data' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('despesas', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Despesa();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Despesa adicionada com sucesso!');
            return $this->redirect(['dashboard/despesas']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Despesa atualizada com sucesso!');
            return $this->redirect(['/dashboard/despesas']);
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        if (($model = Despesa::findOne($id)) !== null) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Despesa excluída com sucesso!');
        }
        return $this->redirect(['dashboard/despesas']);
    }

    protected function findModel($id)
    {
        if (($model = Despesa::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Despesa não encontrada.');
    }
}