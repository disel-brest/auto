<?php

namespace app\board\helpers;


use app\board\entities\AutoServiceWork;

class AutoServiceWorkHelper
{
    public static function categoryPhoto(AutoServiceWork $autoServiceWork = null)
    {
        return $autoServiceWork ? $autoServiceWork->category->getPhotoUrl() : "/new-images/service-photo.jpg";
    }
}