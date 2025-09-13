<?php
// migrations/m200103_000000_create_categorias_table.php

use yii\db\Migration;

class m200103_000000_create_categorias_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('categorias', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('categorias');
    }
}