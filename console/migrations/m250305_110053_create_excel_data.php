<?php

use yii\db\Migration;

class m250305_110053_create_excel_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('excel_data', [
            'id' => $this->primaryKey(),
            'data' => $this->json()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('excel_data');
    }

    /**
     * {@inheritdoc}
     */

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250305_110053_create_excel_data cannot be reverted.\n";

        return false;
    }
    */
}
