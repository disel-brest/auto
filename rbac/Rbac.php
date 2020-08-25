<?php

namespace app\rbac;

class Rbac
{
    const PERMISSION_AD_MANAGE = 'permAdManage';
    const PERMISSION_OWN_AD_MANAGE = 'permOwnAdManage';

    const PERMISSION_USER = 'permUser';
    const PERMISSION_MODERATE = 'permModerate';
    const PERMISSION_ADMIN = 'permAdmin';

    const ROLE_WAITING = 'waiting';
    const ROLE_BANNED = 'banned';
    const ROLE_USER = 'user';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_ADMIN = 'admin';
}