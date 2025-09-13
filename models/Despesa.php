<?php
namespace app\models;

use yii\db\ActiveRecord;

class Despesa extends ActiveRecord
{
    public static function tableName()
    {
        return 'despesas';
    }

    public function rules()
    {
        return [
            [['descricao', 'valor', 'data', 'categoria', 'user_id'], 'required'],
            [['descricao'], 'string'],
            [['valor'], 'number', 'min' => 0.01],
            [['data'], 'date', 'format' => 'php:Y-m-d'],
            [['categoria'], 'in', 'range' => ['alimentação', 'transporte', 'lazer']],
            [['user_id'], 'integer'],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}