<?php

namespace app\board\services\autoService;


use app\board\entities\AutoServiceCategory;
use app\board\entities\AutoServiceWork;
use app\board\forms\manage\AutoServiceCategoryForm;
use app\board\forms\manage\AutoServiceWorkForm;
use app\board\repositories\AutoServiceWorkRepository;
use app\board\services\TransactionManager;
use yii\web\UploadedFile;

class AutoServiceWorkManageService
{
    private $repository;
    private $transaction;

    public function __construct(AutoServiceWorkRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(AutoServiceWorkForm $form)
    {
        $autoServiceWork = AutoServiceWork::create(
            $form->category,
            $form->name
        );
        $this->repository->save($autoServiceWork);
        return $autoServiceWork;
    }

    public function update($id, AutoServiceWorkForm $form)
    {
        $autoServiceWork = $this->repository->get($id);
        $autoServiceWork->edit(
            $form->category,
            $form->name
        );
        $this->repository->save($autoServiceWork);
        return $autoServiceWork;
    }

    public function createCategory(AutoServiceCategoryForm $form)
    {
        $category = AutoServiceCategory::create($form->name);
        $this->transaction->wrap(function () use ($category, $form) {
            $this->repository->saveCategory($category);
            if ($form->photo instanceof UploadedFile) {
                $category->savePhoto($form->photo);
                $this->repository->saveCategory($category);
            }
        });
        return $category;
    }

    public function updateCategory($id, AutoServiceCategoryForm $form)
    {
        $category = $this->repository->getCategory($id);
        $category->edit($form->name);
        if ($form->photo instanceof UploadedFile) {
            $category->savePhoto($form->photo);
        }
        $this->repository->saveCategory($category);

        return $category;
    }
}