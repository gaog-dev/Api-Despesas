<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use app\behaviors\JwtAuthBehavior;
use app\services\DespesaService;

class DespesaController extends ActiveController
{
    public $modelClass = 'app\models\Despesas';
    
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
        // Controle de acesso com sessão
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index', 'view', 'create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'], // apenas usuários logados na sessão
                ],
            ],
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
        $despesas = $this->despesaService->getDespesaById($id);
        
        if (!$despesas) {
            throw new yii\web\NotFoundHttpException('Despesa not found.');
        }
        
        return $despesas;
    }
    
    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        $despesas = $this->despesaService->createDespesa($data);
        
        if ($despesas) {
            return $despesas;
        } else {
            Yii::$app->response->statusCode = 422;
            return ['errors' => $despesas->errors];
        }
    }
    
    public function actionUpdate($id)
    {
        $data = Yii::$app->request->post();
        $despesas = $this->despesaService->updateDespesa($id, $data);
        
        if ($despesas) {
            return $despesas;
        } else {
            Yii::$app->response->statusCode = 422;
            return ['errors' => $despesas->errors];
        }
    }
    
    public function actionDelete($id)
    {
        if ($this->despesaService->deleteDespesa($id)) {
            Yii::$app->response->statusCode = 204;
            return;
        } else {
            throw new yii\web\NotFoundHttpException('Despesa not found.');
        }
    }
}