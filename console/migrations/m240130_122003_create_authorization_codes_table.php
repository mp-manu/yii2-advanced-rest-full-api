<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%authorization_codes}}`.
 */
class m240130_122003_create_authorization_codes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%authorization_codes}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(255),
            'expires_at' => $this->integer(11),
            'user_id' => $this->integer(11),
            'app_id' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%authorization_codes}}');
    }
}
