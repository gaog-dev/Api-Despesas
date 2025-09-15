<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

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
            [['descricao'], 'string', 'max' => 255],
            [['valor'], 'number', 'min' => 0.01],
            [['data'], 'date', 'format' => 'php:Y-m-d'],
            [['categoria'], 'string', 'max' => 100],
            [['categoria'], 'in', 'range' => ['alimentação', 'transporte', 'lazer']],
            [['user_id'], 'integer'],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descricao' => 'Descrição',
            'categoria' => 'Categoria',
            'valor' => 'Valor',
            'data' => 'Data',
            'user_id' => 'Usuário',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Garantir que a categoria esteja em minúsculas
        $this->categoria = strtolower($this->categoria);

        // Converter data para o formato do banco de dados se necessário
        if ($this->data && is_string($this->data)) {
            // Se a data estiver no formato Y-m-d, converter para timestamp
            $this->data = date('Y-m-d', strtotime($this->data));
        }

        return true;
    }

    public function afterFind()
    {
        parent::afterFind();

        // Formatar data para exibição
        if ($this->data) {
            $this->data = date('Y-m-d', strtotime($this->data));
        }
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    # Formata o valor para exibição

    public function getValorFormatado()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }
    # Formata a data para exibição
    public function getDataFormatada()
    {
        return date('d/m/Y', strtotime($this->data));
    }


    # Campos devem ser retornados na API
    public function fields()
    {
        return [
            'id',
            'descricao',
            'categoria',
            'valor',
            'data',
            'created_at',
            'updated_at',
        ];
    }

    # Bancos Extra que podem ser incluidos.
    public function extraFields()
    {
        return [
            'user',
            'valorFormatado' => 'valorFormatado',
            'dataFormatada' => 'dataFormatada',
        ];
    }
}

