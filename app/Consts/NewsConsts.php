<?php

namespace App\Consts;

class NewsConsts
{
    public const TITLE_LENGTH_MIN = 4;
    public const TITLE_LENGTH_MAX = 100;
    public const LINK_FLG_ON = 1;
    public const LINK_FLG_OFF = 2;
    public const LINK_FLG_LIST = [
        self::LINK_FLG_ON => 'あり',
        self::LINK_FLG_OFF => 'なし',
    ];
    public const URL_LENGTH_MAX = 255;
    public const OVERVIEW_LENGTH_MAX = 2000;
    public const RELEASE_FLG_ON = 1;
    public const RELEASE_FLG_OFF = 2;
    public const RELEASE_FLG_LIST = [
        self::RELEASE_FLG_ON => '表示',
        self::RELEASE_FLG_OFF => '非表示',
    ];

    public const TOP_LIST_LIMIT = 10;
    public const PAGENATE_LIST_LIMIT = 15;
    public const ADMIN_PAGENATE_LIST_LIMIT = 20;
}