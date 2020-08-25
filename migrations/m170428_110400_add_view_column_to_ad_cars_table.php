<?php

use yii\db\Migration;

/**
 * Handles adding view to table `ad_cars`.
 */
class m170428_110400_add_view_column_to_ad_cars_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn("{{%ad_car}}", "views", $this->integer()->unsigned()->notNull()->defaultValue(0)->after("status"));
        $this->addColumn("{{%ad_car}}", "active_till", $this->integer()->notNull()->after("views"));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn("{{%ad_car}}", "active_till");
        $this->dropColumn("{{%ad_car}}", "views");
    }
}
