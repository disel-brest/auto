<?php

use yii\db\Migration;

class m171117_155852_add_usd_price_to_ads extends Migration
{
    public function up()
    {
        $this->addColumn('{{%ad_tire}}', 'price_usd', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('price'));
        $this->addColumn('{{%ad_car}}', 'price_usd', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('price'));
        $this->addColumn('{{%ad_wheel}}', 'price_usd', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('price'));
    }

    public function down()
    {
        $this->dropColumn('{{%ad_tire}}', 'price_usd');
        $this->dropColumn('{{%ad_car}}', 'price_usd');
        $this->dropColumn('{{%ad_wheel}}', 'price_usd');
    }
}
