<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%video}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m250220_055555_create_videos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%video}}', [
            'video_id' => $this->string(16)->notNull(),
            'title' => $this->string(512)->notNull(),
            'description' => $this->text(),
            'tags' => $this->string(length:512),
            'status' => $this->integer(length:1),
            'has_thumbnail' => $this->boolean(),
            'video_name' => $this->string(length:512),
            'created_at' => $this->integer(length:11),
            'updated_at' => $this->integer(length:11),
            'created_by' => $this->integer(11),
        ]);

        $this->addPrimaryKey(name: 'PK_videos_id', table: '{{%video}}', columns: 'video_id');

        // creates index for column `create_by`
        $this->createIndex(
            name:'{{%idx-videos-create_by}}',
            table:'{{%video}}',
            columns:'create_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            name:'{{%fk-videos-create_by}}',
            table:'{{%video}}',
            columns:'create_by',
            refTable:'{{%user}}',
            refColumns:'id',
            delete:'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-videos-create_by}}',
            '{{%video}}'
        );

        // drops index for column `create_by`
        $this->dropIndex(
            '{{%idx-videos-create_by}}',
            '{{%video}}'
        );

        $this->dropTable('{{%video}}');
    }
}
