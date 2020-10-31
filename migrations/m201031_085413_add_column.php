<?php

use yii\db\Migration;

/**
 * Class m201031_085413_add_column
 */
class m201031_085413_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advance', 'note_id', $this->integer());
        $this->createIndex('idx_advance-note_id', 'advance', 'note_id');

        $this->addForeignKey('fk_advance-note_id',
            'advance', 'note_id',
            'file', 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
