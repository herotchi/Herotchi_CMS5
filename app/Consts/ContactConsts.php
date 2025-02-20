<?php

namespace App\Consts;

class ContactConsts
{
    public const NO_LENGTH = 12;
    public const BODY_LENGTH_MAX = 2000;
    public const BODY_LIST_LENGTH_MAX = 50;
    public const STATUS_COMPLETED = 1;
    public const STATUS_IN_PROGRESS = 2;
    public const STATUS_NOT_STARTED = 3;
    public const STATUS_LIST = [
        self::STATUS_COMPLETED => '対応済',
        self::STATUS_IN_PROGRESS => '対応中',
        self::STATUS_NOT_STARTED => '未対応',
    ];

    public const PAGENATE_LIST_LIMIT = 2;
}