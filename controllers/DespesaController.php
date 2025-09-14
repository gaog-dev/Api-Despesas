<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
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
        $behaviors['authenticator'] = [
            'class' => \app\behaviors\JwtAuthBehavior::class,
        ];
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
        $request = Yii::$app->request;
        $filters = [
            'categoria' => $request->get('categoria'),
            'data_inicio' => $request->get('data_inicio'),
            'data_fim' => $request->get('data_fim'),
        ];
        
        return $this->despesaService->getDespesas($filters);
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
        $data = Yii::$app->request->post();
        $despesa = $this->despesaService->createDespesa($data);

        if ($despesa) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            return [
                'success' => true,
                'despesa' => $despesa,
            ];
        } else {
            Yii::$app->response->statusCode = 422;
            return ['errors' => $despesa ? $despesa->errors : ['Falha ao salvar despesa']];
        }
    }
    
    public function actionUpdate($id)
    {
        $data = Yii::$app->request->post();
        $despesa = $this->despesaService->updateDespesa($id, $data);

        if ($despesa && !$despesa->hasErrors()) {
            return $despesa;
        } else {
            Yii::$app->response->statusCode = 422;
            return ['errors' => $despesa ? $despesa->errors : ['Falha ao atualizar despesa']];
        }
    }
    
    public function actionDelete($id)
    {
        if ($this->despesaService->deleteDespesa($id)) {
            Yii::$app->response->statusCode = 204;
            return ['success' => true];
        } else {
            throw new NotFoundHttpException('Despesa não encontrada.');
        }
    }
}