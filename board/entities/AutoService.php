<?php

namespace app\board\entities;

use app\board\helpers\AutoServiceHelper;
use app\board\readModels\query\AutoServiceQuery;
use app\modules\main\models\City;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%auto_services}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $sub_text
 * @property string $legal_name
 * @property integer $city_id
 * @property string $street
 * @property string $unp
 * @property integer $year
 * @property string $phones
 * @property string $site
 * @property string $work_schedule
 * @property string $about
 * @property string $info
 * @property string $background
 * @property string $photos
 * @property string $lat
 * @property string $lng
 * @property int $status [smallint(6)]
 * @property int $views [int(11)]
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property City $city
 * @property AutoServicesWorksAssignment[] $autoServicesWorksAssignments
 * @property AutoServiceWork[] $works
 */
class AutoService extends ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static function create($name, $subText, $legalName, $cityID, $street, $UNP, $year, array $phones, $site, array $workSchedule, $about, $info, $lat, $lng, array $works)
    {
        $autoService = new static();
        $autoService->name = $name;
        $autoService->sub_text = $subText;
        $autoService->legal_name = $legalName;
        $autoService->city_id = $cityID;
        $autoService->street = $street;
        $autoService->unp = $UNP;
        $autoService->year = $year;
        $autoService->setPhones($phones);
        $autoService->site = $site;
        $autoService->setWorkSchedule($workSchedule);
        $autoService->about = $about;
        $autoService->info = $info;
        $autoService->lat = $lat;
        $autoService->lng = $lng;
        $autoService->created_at = time();
        $autoService->status = self::STATUS_ACTIVE;
        $autoService->views = 0;

        $worksArray = [];
        foreach ($works as $work) {
            if ($work) {
                //$sort = AutoServicesWorksAssignment::find()->where(['work_id' => $work])->max('sort') + 1;
                //$worksArray[] = ['work_id' => $work, 'sort' => $sort ? $sort : 1];
                $worksArray[] = $work;
            }
        }
        $autoService->works = $worksArray;

        return $autoService;
    }

    public function edit($name, $subText, $legalName, $cityID, $street, $UNP, $year, array $phones, $site, array $workSchedule, $about, $info, $lat, $lng, array $works)
    {
        $this->name = $name;
        $this->sub_text = $subText;
        $this->legal_name = $legalName;
        $this->city_id = $cityID;
        $this->street = $street;
        $this->unp = $UNP;
        $this->year = $year;
        $this->setPhones($phones);
        $this->site = $site;
        $this->setWorkSchedule($workSchedule);
        $this->about = $about;
        $this->info = $info;
        $this->lat = $lat;
        $this->lng = $lng;

        $worksArray = [];
        foreach ($works as $work) {
            if ($work) {
                $worksArray[] = $work;
            }
        }
        $this->works = $worksArray;
    }

    public function saveBackground(UploadedFile $file)
    {
        $name = $this->id . "_bg_" . time() . "." . $file->extension;
        if ($file->saveAs(AutoServiceHelper::filesPath(false) . "/" . $name)) {
            if ($this->background && $this->background != $name && is_file(AutoServiceHelper::filesPath(false) . "/" . $this->background)) {
                unlink(AutoServiceHelper::filesPath(false) . "/" . $this->background);
            }
            $this->background = $name;
        }
    }

    public function savePhotos(array $files)
    {
        $oldFiles = $this->getPhotos();
        $newFiles = [];
        foreach ($files as $n => $file) {
            if ($file instanceof UploadedFile) {
                $name = $this->id . "_" . $n . "_" . time() ."." . $file->extension;
                if ($file->saveAs(AutoServiceHelper::filesPath(false) . "/" . $name)) {
                    $newFiles[] = $name;
                }
            }
        }
        foreach ($oldFiles as $oldFile) {
            if (!in_array($oldFile, $newFiles) && is_file(AutoServiceHelper::filesPath(false) . "/" . $oldFile)) {
                unlink(AutoServiceHelper::filesPath(false) . "/" . $oldFile);
            }
        }
        $this->setPhotos($newFiles);
    }

    public function addPhoto(UploadedFile $file)
    {
        $files = $this->getPhotos();
        $name = $this->id . "_" . (count($files) + 1) . "_" . time() ."." . $file->extension;
        if ($file->saveAs(AutoServiceHelper::filesPath(false) . "/" . $name)) {
            $files[] = $name;
            $this->setPhotos($files);
        }
    }

    public function removePhoto($id)
    {
        $photos = $this->getPhotos();
        if (isset($photos[$id])) {
            if (is_file(AutoServiceHelper::filesPath(false) . "/" . $photos[$id])) {
                unlink(AutoServiceHelper::filesPath(false) . "/" . $photos[$id]);
            }
            unset($photos[$id]);
            $this->setPhotos($photos);
            return;
        }
        throw new \DomainException('Photo is not found.');
    }

    public function getPhotos()
    {
        return $this->photos ? Json::decode($this->photos) : [];
    }

    public function setPhotos($files)
    {
        $this->photos = Json::encode(is_array($files) ? $files : []);
    }

    public function getPhones()
    {
        return $this->phones ? Json::decode($this->phones) : [];
    }

    public function setPhones($phones)
    {
        $this->phones = Json::encode(is_array($phones) ? $phones : []);
    }

    public function getWorkSchedule()
    {
        return $this->work_schedule ? Json::decode($this->work_schedule) : [];
    }

    public function setWorkSchedule($workSchedule)
    {
        $this->work_schedule = Json::encode(is_array($workSchedule) ? $workSchedule : []);
    }

    public function getWorkAssignmentByWorkId($workID)
    {
        return $this->getAutoServicesWorksAssignments()->where(['work_id' => $workID])->limit(1)->one();
    }

    public function move($direction, $workId)
    {
        $workAssignment = $this->getWorkAssignmentByWorkId($workId);
        $currentSort = $workAssignment->sort;
        $changeWorkAssignment = AutoServicesWorksAssignment::find()
            ->where(['work_id' => $workId])
            ->andWhere([$direction == 'up' ? '<' : '>', 'sort', $currentSort])
            ->orderBy(['sort' => $direction == 'up' ? SORT_DESC : SORT_ASC])
            ->limit(1)
            ->one();

        if ($changeWorkAssignment == null) {
            throw new \DomainException($direction == 'up' ? "Это наивысшая позиция" : "Это последняя позиция");
        }

        $workAssignment->sort = $changeWorkAssignment->sort;
        $changeWorkAssignment->sort = $currentSort;

        $workAssignment->save();
        $changeWorkAssignment->save();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auto_services}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'saveRelations' => [
                'class'     => SaveRelationsBehavior::className(),
                'relations' => ['works']
            ],
        ];
    }

    public function afterDelete()
    {
        foreach ($this->getPhotos() as $photo) {
            if (is_file(AutoServiceHelper::filesPath(false) . "/" . $photo)) {
                unlink(AutoServiceHelper::filesPath(false) . "/" . $photo);
            }
        }
        parent::afterDelete();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Общее название',
            'sub_text' => 'Подтекст',
            'legal_name' => 'Юридическое название',
            'city_id' => 'Город',
            'street' => 'Улица',
            'unp' => 'УНП',
            'year' => 'Год',
            'phones' => 'Телефоны',
            'site' => 'Сайт',
            'work_schedule' => 'График работы',
            'about' => 'Описание',
            'info' => 'Дополнительная информация',
            'background' => 'Фон',
            'photos' => 'Фотографии',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'status' => 'Статус',
            'views' => 'Просмотры',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutoServicesWorksAssignments()
    {
        return $this->hasMany(AutoServicesWorksAssignment::className(), ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorks()
    {
        return $this->hasMany(AutoServiceWork::className(), ['id' => 'work_id'])->viaTable('{{%auto_services_works_assignment}}', ['service_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkCategories()
    {
        return AutoServiceCategory::find()
            ->alias('c')
            ->innerJoin(AutoServiceWork::tableName() . ' w', 'w.category_id=c.id')
            ->innerJoin(AutoServicesWorksAssignment::tableName() . ' a', 'a.work_id=w.id')
            ->innerJoin(AutoService::tableName() . ' s', 's.id=a.service_id')
            ->andWhere(['a.service_id' => $this->id]);
    }

    /**
     * @inheritdoc
     * @return AutoServiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AutoServiceQuery(get_called_class());
    }
}
