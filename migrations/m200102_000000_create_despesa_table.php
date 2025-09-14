<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%despesas}}`.
 */
class m200102_000000_create_despesa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%despesa}}', [
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
            '{{%idx-despesa-user_id}}',
            '{{%despesa}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-despesa-user_id}}',
            '{{%despesa}}',
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
            '{{%fk-despesa-user_id}}',
            '{{%despesa}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-despesa-user_id}}',
            '{{%despesa}}'
        );

        $this->dropTable('{{%despesa}}');
    }
}