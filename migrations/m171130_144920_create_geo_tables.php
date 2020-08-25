<?php

use yii\db\Migration;

class m171130_144920_create_geo_tables extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('fk-user-city_id-city-id', '{{%user}}');
        $this->dropForeignKey('fk-auto_services-city_id-city-id', '{{%auto_services}}');
        $this->truncateTable('{{%city}}');
        $this->addColumn('{{%city}}', 'region', $this->string(100)->notNull()->defaultValue('')->after('id'));
        $this->addColumn('{{%city}}', 'area', $this->string(100)->notNull()->defaultValue('')->after('region'));
        $this->createIndex('idx-city-region-name', '{{%city}}', ['region']);

        $geo = require __DIR__ . '/dump/geo.php';
        $insert = [];
        foreach ($geo as $item) {
            $region = array_shift($item);
            foreach ($item as $areaArray) {
                $area = $areaArray['area'];
                foreach ($areaArray['cities'] as $city) {
                    $insert[] = [$region, $area, $city];
                }
            }
        }

        $this->batchInsert('{{%city}}', ['region', 'area', 'name'], $insert);
        $this->addForeignKey('fk-user-city_id-city-id', '{{%user}}', 'city_id', '{{%city}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-auto_services-city_id-city-id', '{{%auto_services}}', 'city_id', '{{%city}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-auto_services-city_id-city-id', '{{%auto_services}}');
        $this->dropForeignKey('fk-user-city_id-city-id', '{{%user}}');
        $this->truncateTable('{{%city}}');
        $this->dropIndex('idx-city-region-name', '{{%city}}');
        $this->dropColumn('{{%city}}', 'region');
        $this->dropColumn('{{%city}}', 'area');

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
        $this->addForeignKey('fk-user-city_id-city-id', '{{%user}}', 'city_id', '{{%city}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-auto_services-city_id-city-id', '{{%auto_services}}', 'city_id', '{{%city}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
