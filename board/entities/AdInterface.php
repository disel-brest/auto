<?php

namespace app\board\entities;


interface AdInterface
{
    /**
     * @return string
     */
    public function getFullName();

    /**
     * @return int
     */
    public static function type();

    /**
     * @return string
     */
    public function getMainPhoto();
}