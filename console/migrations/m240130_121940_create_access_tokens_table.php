<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%access_tokens}}`.
 */
class m240130_121940_create_access_tokens_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%access_tokens}}', [
            'id' => $this->primaryKey(),
            'token' => $this->string(255),
            'expires_at' => $this->integer(11),
            'auth_code' => $this->string(255),
            'user_id' => $this->integer(11),
            'app_id' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%access_tokens}}');
    }
}
