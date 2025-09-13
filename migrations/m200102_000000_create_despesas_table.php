<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%despesas}}`.
 */
class m200102_000000_create_despesas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%despesas}}', [
            'id' => $this->primaryKey(),
            'descricao' => $this->string()->notNull(),
            'valor' => $this->decimal(10, 2)->notNull(),
            'data' => $this->date()->notNull(),
            'categoria' => "ENUM('alimentação', 'transporte', 'lazer') NOT NULL",
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-despesas-user_id}}',
            '{{%despesas}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-despesas-user_id}}',
            '{{%despesas}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-despesas-user_id}}',
            '{{%despesas}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-despesas-user_id}}',
            '{{%despesas}}'
        );

        $this->dropTable('{{%despesas}}');
    }
}