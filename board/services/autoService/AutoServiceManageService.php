<?php

namespace app\board\services\autoService;


use app\board\entities\AutoService;
use app\board\entities\AutoServicesWorksAssignment;
use app\board\forms\manage\AutoService\AutoServiceCreateForm;
use app\board\forms\manage\AutoService\AutoServiceEditForm;
use app\board\forms\manage\AutoService\PhotosForm;
use app\board\repositories\AutoServiceRepository;
use app\board\services\TransactionManager;
use app\board\helpers\AutoServiceHelper;
use app\modules\main\models\City;
use yii\web\UploadedFile;

class AutoServiceManageService
{
    private $repository;
    private $transaction;

    public function __construct(AutoServiceRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(AutoServiceCreateForm $form)
    {
        $cityID = City::find()->select('id')->where(['name' => $form->city])->scalar();
        $workSchedule = AutoServiceHelper::generateWorkScheduleArray($form->workScheduleDay, $form->workScheduleFrom, $form->workScheduleTill);
        list($lat, $lng) = explode(',', $form->coordinates);

        $phones = [];
        foreach ($form->phones as $i => $phone) {
            $phones[] = [$phone, $form->phoneOperators[$i]];
        }
        $autoService = AutoService::create(
            $form->name,
            $form->subText,
            $form->legalName,
            $cityID,
            $form->street,
            $form->UNP,
            $form->year,
            $phones,
            $form->site,
            $workSchedule,
            $form->about,
            $form->info,
            (float)$lat,
            (float)$lng,
            $form->works
        );
        $this->transaction->wrap(function () use ($autoService, $form) {
            $this->repository->save($autoService);
            $save = false;
            if ($form->background instanceof UploadedFile) {
                $autoService->saveBackground($form->background);
                $save = true;
            }
            if ($form->photos->files) {
                $autoService->savePhotos($form->photos->files);
                $save = true;
            }
            if ($save) {
                $this->repository->save($autoService);
            }
            foreach ($autoService->autoServicesWorksAssignments as $assignment) {
                $sort = AutoServicesWorksAssignment::find()
                        ->where(['work_id' => $assignment->work_id])
                        ->andWhere(['<>', 'service_id', $autoService->id])
                        ->max('sort');
                $assignment->sort = $sort ? $sort + 1 : 1;
                $assignment->save();
            }

        });
        return $autoService;
    }

    public function edit($id, AutoServiceEditForm $form)
    {
        $cityID = City::find()->select('id')->where(['name' => $form->city])->scalar();
        $workSchedule = AutoServiceHelper::generateWorkScheduleArray($form->workScheduleDay, $form->workScheduleFrom, $form->workScheduleTill);
        list($lat, $lng) = explode(',', $form->coordinates);

        $phones = [];
        foreach ($form->phones as $i => $phone) {
            $phones[] = [$phone, $form->phoneOperators[$i]];
        }
        $autoService = $this->repository->get($id);
        $oldAssignment = $autoService->autoServicesWorksAssignments;
        $autoService->edit(
            $form->name,
            $form->subText,
            $form->legalName,
            $cityID,
            $form->street,
            $form->UNP,
            $form->year,
            $phones,
            $form->site,
            $workSchedule,
            $form->about,
            $form->info,
            (float)$lat,
            (float)$lng,
            $form->works
        );
        if ($form->background instanceof UploadedFile) {
            $autoService->saveBackground($form->background);
        }
        $this->repository->save($autoService);

        $oldAss = [];
        foreach ($oldAssignment as $old) {
            $oldAss[] = $old->work_id;
        }
        foreach ($autoService->getAutoServicesWorksAssignments()->each() as $assignment) {
            if (!in_array($assignment->work_id, $oldAss)) {
                $sort = AutoServicesWorksAssignment::find()
                    ->where(['work_id' => $assignment->work_id])
                    ->andWhere(['<>', 'service_id', $autoService->id])
                    ->max('sort');
                $assignment->sort = $sort ? $sort + 1 : 1;
                $assignment->save();
            }
        }

        return $autoService;
    }

    public function addPhotos($id, PhotosForm $form)
    {
        $autoService = $this->repository->get($id);
        foreach ($form->files as $file) {
            $autoService->addPhoto($file);
        }
        $this->repository->save($autoService);
    }

    public function removePhoto($id, $photoId)
    {
        $autoService = $this->repository->get($id);
        $autoService->removePhoto($photoId);
        $this->repository->save($autoService);
    }

    public function move($direction, $id, $workId)
    {
        if ($direction != "up" && $direction != "down") {
            throw new \DomainException("Неверное направление");
        }

        $autoService = $this->repository->get($id);
        $autoService->move($direction, $workId);
        $this->repository->save($autoService);
    }
}