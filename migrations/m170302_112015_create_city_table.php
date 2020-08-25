<?php

use yii\db\Migration;

/**
 * Handles the creation of table `city`.
 */
class m170302_112015_create_city_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addColumn('{{%user}}', 'city_id', $this->integer()->null()->after('username'));
        $this->addForeignKey('fk-user-city_id-city-id', '{{%user}}', 'city_id', '{{%city}}', 'id', 'SET NULL', 'CASCADE');

        $json = file_get_contents(Yii::getAlias('@app/migrations/dump/belarus-cities-dump.json'));
        $citiesArray = \yii\helpers\Json::decode($json);
        $cities = [];
        $citiesToImport = [];
        foreach ($citiesArray as $city) {
            if (!in_array($city['name'], $cities)) {
                $citiesToImport[] = [$city['id'], $city['name']];
                $cities[] = $city['name'];
            }
        }

        Yii::$app->db->createCommand()->batchInsert('{{%city}}', ['id', 'name'], $citiesToImport)->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-user-city_id-city-id', '{{%user}}');
        $this->dropColumn('{{%user}}', 'city_id');
        $this->dropTable('{{%city}}');
    }
}
