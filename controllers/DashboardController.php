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
        $query = Despesa::find()->orderBy(['data' => SORT_DESC]);

        // filtros
        $categoria = Yii::$app->request->get('categoria');
        $dataInicio = Yii::$app->request->get('data_inicio');
        $dataFim = Yii::$app->request->get('data_fim');

        if ($categoria) {
            $query->andWhere(['categoria' => $categoria]);
        }
        if ($dataInicio) {
            $query->andWhere(['>=', 'data', $dataInicio]);
        }
        if ($dataFim) {
            $query->andWhere(['<=', 'data', $dataFim]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('despesas', [
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
            return $this->redirect(['dashboard/despesas']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Despesa excluída com sucesso!');
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
