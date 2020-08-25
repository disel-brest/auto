<?php

use yii\db\Migration;

class m171116_154541_change_ad_part_photo_column extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%ad_part}}', 'photo', $this->string(255)->notNull()->defaultValue('[]'));
    }

    public function down()
    {
        $this->alterColumn('{{%ad_part}}', 'photo', $this->string(38)->notNull()->defaultValue(''));
    }
}
