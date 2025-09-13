<?php
namespace app\commands;

use yii\console\Controller;
use yii\db\Exception;

class CreateTablesController extends Controller
{
    public function actionIndex()
    {
        try {
            // Criar tabela user
            $this->createUserTable();
            echo "Tabela 'user' criada com sucesso.\n";
            
            // Criar tabela despesas
            $this->createDespesasTable();
            echo "Tabela 'despesas' criada com sucesso.\n";
            
        } catch (Exception $e) {
            echo "Erro ao criar tabelas: " . $e->getMessage() . "\n";
        }
    }
    
    private function createUserTable()
    {
        $this->db->createCommand()->createTable('user', [
            'id' => $this->db->primaryKey(),
            'username' => $this->db->string()->notNull()->unique(),
            'password_hash' => $this->db->string()->notNull(),
            'auth_key' => $this->db->string(32)->notNull(),
            'created_at' => $this->db->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->db->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ])->execute();
    }
    
    private function createDespesasTable()
    {
        $this->db->createCommand()->createTable('despesas', [
            'id' => $this->db->primaryKey(),
            'descricao' => $this->db->string()->notNull(),
            'valor' => $this->db->decimal(10, 2)->notNull(),
            'data' => $this->db->date()->notNull(),
            'categoria' => "ENUM('alimentação', 'transporte', 'lazer') NOT NULL",
            'user_id' => $this->db->integer()->notNull(),
            'created_at' => $this->db->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->db->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ])->execute();
        
        // Criar índice para user_id
        $this->db->createCommand()->createIndex(
            'idx-despesas-user_id',
            'despesas',
            'user_id'
        )->execute();
        
        // Adicionar chave estrangeira
        $this->db->createCommand()->addForeignKey(
            'fk-despesas-user_id',
            'despesas',
            'user_id',
            'user',
            'id',
            'CASCADE'
        )->execute();
    }
}