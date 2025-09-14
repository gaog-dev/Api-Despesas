<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Despesa;
use yii\data\ActiveDataProvider;

class DashboardController extends Controller
{
    public function actionDespesas()
    {
        $userId = Yii::$app->user->id;

        $model = new Despesa();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Despesa adicionada com sucesso!');
            return $this->refresh();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Despesa::find()->where(['user_id' => $userId])->orderBy(['data' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('despesas', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        if (($model = Despesa::findOne($id)) !== null && $model->user_id == Yii::$app->user->id) {
            $model->delete();
        }
        return $this->redirect(['despesas']);
    }
}
