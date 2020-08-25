<?php

use yii\db\Migration;

class m171207_113848_add_geo_columns_to_ad_tables extends Migration
{
    public function up()
    {
        $this->addColumn('{{%ad_part}}', 'city', $this->string()->notNull()->defaultValue('')->after('active_till'));
        $this->addColumn('{{%ad_part}}', 'region', $this->string(100)->notNull()->defaultValue('')->after('city'));
        $this->addColumn('{{%ad_tire}}', 'city', $this->string()->notNull()->defaultValue('')->after('active_till'));
        $this->addColumn('{{%ad_tire}}', 'region', $this->string(100)->notNull()->defaultValue('')->after('city'));
        $this->addColumn('{{%ad_wheel}}', 'city', $this->string()->notNull()->defaultValue('')->after('active_till'));
        $this->addColumn('{{%ad_wheel}}', 'region', $this->string(100)->notNull()->defaultValue('')->after('city'));
        $this->addColumn('{{%ad_car}}', 'city', $this->string()->notNull()->defaultValue('')->after('active_till'));
        $this->addColumn('{{%ad_car}}', 'region', $this->string(100)->notNull()->defaultValue('')->after('city'));
    }

    public function down()
    {
        $this->dropColumn('{{%ad_part}}', 'city');
        $this->dropColumn('{{%ad_part}}', 'region');
        $this->dropColumn('{{%ad_tire}}', 'city');
        $this->dropColumn('{{%ad_tire}}', 'region');
        $this->dropColumn('{{%ad_wheel}}', 'city');
        $this->dropColumn('{{%ad_wheel}}', 'region');
        $this->dropColumn('{{%ad_car}}', 'city');
        $this->dropColumn('{{%ad_car}}', 'region');
    }
}
