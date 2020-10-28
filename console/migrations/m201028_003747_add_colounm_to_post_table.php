<?php

use yii\db\Migration;

/**
 * Class m201028_003747_add_colounm_to_post_table
 */
class m201028_003747_add_colounm_to_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%post}}', 'archive_of_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%post}}', 'archive_of_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201028_003747_add_colounm_to_post_table cannot be reverted.\n";

        return false;
    }
    */
}
