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
        $model = new Despesa();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Despesa adicionada com sucesso!');
                return $this->redirect(['dashboard/despesas']);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao salvar a despesa.');
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Despesa::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['data' => SORT_DESC]),
        ]);

        return $this->render('despesas', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUpdate($id)
    {
        $model = Despesa::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException("Despesa não encontrada.");
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Despesa atualizada!');
            return $this->redirect(['dashboard/despesas']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = Despesa::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if ($model) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Despesa excluída!');
        } else {
            Yii::$app->session->setFlash('error', 'Despesa não encontrada.');
        }
        return $this->redirect(['dashboard/despesas']);
    }
}
