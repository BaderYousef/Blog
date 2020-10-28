<?php

use yii\db\Migration;

/**
 * Class m201028_004125_alter_post_colounm
 */
class m201028_004125_alter_post_colounm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->alterColumn('{{%post}}', 'user_id', $this->integer());
    }

    public function down()
    {
        $this->alterColumn('{{%post}}', 'user_id', $this->integer()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201028_004125_alter_post_colounm cannot be reverted.\n";

        return false;
    }
    */
}
