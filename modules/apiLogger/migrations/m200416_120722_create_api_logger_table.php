<?php

namespace app\modules\apiLogger\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%api_logger}}`.
 */
class m200416_120722_create_api_logger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%api_logger}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->dateTime(4)->notNull(),
            'duration' => $this->float(),
            'url' => $this->string()->notNull(),
            'method' => $this->string()->notNull(),
            'request' => $this->json()->null(),
            'headers' => $this->json()->null(),
            'answer' => $this->json()->null(),
            'app_version' => $this->string(),
            'app_platform' => "enum('web', 'android', 'ios') default 'web'",
            'user_id' => $this->integer()
        ]);

        $this->createIndex('idx_api_logger-user_id', 'api_logger', 'user_id');
        $this->createIndex('idx_api_logger-created_at', 'api_logger', 'created_at');
        $this->createIndex('idx_api_logger-method', 'api_logger', 'method');
        $this->createIndex('idx_api_logger-duration', 'api_logger', 'duration');
        $this->createIndex('idx_api_logger-app_platform', 'api_logger', 'app_platform');

        $this->addForeignKey('fk_api_logger-user_id',
            'api_logger', 'user_id',
            'user','id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%api_logger}}');
    }
}
