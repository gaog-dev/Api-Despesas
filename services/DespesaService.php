<?php
namespace app\services;

use Yii;
use app\models\Despesa;
use yii\data\ActiveDataProvider;

class DespesaService
{
    public function createDespesa($data)
    {
        $despesa = new Despesa();
        $despesa->load($data,'');
        $despesa->user_id = Yii::$app->user->id;
        
        if ($despesa->save()) {
            return $despesa;
        }
        
        return null;
    }
    
    public function updateDespesa($id, $data)
    {
        $despesa = $this->getDespesaById($id);
        
        if (!$despesa) {
            return null;
        }
        
        $despesa->load($data, '');
        
        if ($despesa->save()) {
            return $despesa;
        }
        
        return null;
    }
    
    public function deleteDespesa($id)
    {
        $despesa = $this->getDespesaById($id);
        
        if (!$despesa) {
            return false;
        }
        
        return $despesa->delete() !== false;
    }
    
    public function getDespesaById($id)
    {
        return Despesa::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
    }
    
    public function getDespesas($filters = [])
    {
        $query = Despesa::find()->where(['user_id' => Yii::$app->user->id]);
        
        if (isset($filters['categoria'])) {
            $query->andWhere(['categoria' => $filters['categoria']]);
        }
        
        if (isset($filters['data_inicio'])) {
            $query->andWhere(['>=', 'data', $filters['data_inicio']]);
        }
        
        if (isset($filters['data_fim'])) {
            $query->andWhere(['<=', 'data', $filters['data_fim']]);
        }
        
        $query->orderBy(['data' => SORT_DESC]);
        
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}