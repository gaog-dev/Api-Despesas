<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
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
        unset($behaviors['authenticator']); // 🔓 Sem JWT por enquanto
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
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['status' => 'error', 'message' => 'Não autenticado'];
        }

        $despesas = Despesa::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['data' => SORT_DESC])
            ->all();

        return ['status' => 'success', 'data' => $despesas];
    }
    
    public function actionView($id)
    {
        $despesa = $this->despesaService->getDespesaById($id);
        
        if (!$despesa) {
            throw new yii\web\NotFoundHttpException('Despesa not found.');
        }
        
        return $despesa;
    }
    
    public function actionCreate()
    {
        $model = new Despesa();
        $model->load(Yii::$app->request->post(), '');
        // atribui automaticamente o usuário logado
        if (!Yii::$app->user->isGuest) {
            $model->user_id = Yii::$app->user->id;
        }
        if ($model->save()) {
            return ['status' => 'success', 'data' => $model];
        }
        Yii::$app->response->statusCode = 422;
        return ['status' => 'error', 'errors' => $model->errors];
    }
    
    public function actionUpdate($id)
    {
        $model = Despesa::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Despesa não encontrada");
        }

        $model->load(Yii::$app->request->post(), '');
        if ($model->save()) {
            return ['status' => 'success', 'data' => $model];
        }
        return ['status' => 'error', 'errors' => $model->errors];
    }
    
    public function actionDelete($id)
    {
        $model = Despesa::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Despesa não encontrada");
        }

        if ($model->delete()) {
            return ['status' => 'success', 'message' => 'Despesa removida'];
        }
        return ['status' => 'error', 'message' => 'Erro ao remover'];
    }
}