<?php

use yii\db\Migration;

/**
 * Handles adding views_active to table `parts`.
 */
class m170508_230946_add_views_active_columns_to_parts_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%ad_part}}', 'views', $this->integer()->notNull()->unsigned()->defaultValue(0)->after('status'));
        $this->addColumn('{{%ad_part}}', 'active_till', $this->integer()->notNull()->after('views'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%ad_part}}', 'views');
        $this->dropColumn('{{%ad_part}}', 'active_till');
    }
}
