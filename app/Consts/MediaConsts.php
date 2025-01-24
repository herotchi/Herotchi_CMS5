<?php

namespace App\Consts;

class MediaConsts
{
    public const MEDIA_FLG_CAROUSEL = 1;
    public const MEDIA_FLG_PICKUP = 2;
    public const MEDIA_FLG_LIST = [
        self::MEDIA_FLG_CAROUSEL => 'カルーセル',
        self::MEDIA_FLG_PICKUP => 'pick up',
    ];
    public const IMAGE_FILE_MAX = 5120;
    public const ALT_LENGTH_MAX = 100;
    public const URL_LENGTH_MAX = 255;

    public const RELEASE_FLG_ON = 1;
    public const RELEASE_FLG_OFF = 2;
    public const RELEASE_FLG_LIST = [
        self::RELEASE_FLG_ON => '表示',
        self::RELEASE_FLG_OFF => '非表示',
    ];

    public const IMAGE_FILE_DIR = 'media';

    public const PAGENATE_LIST_LIMIT = 12;
}