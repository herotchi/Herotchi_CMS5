<?php

namespace App\Consts;

class ProductConsts
{
    public const NAME_LENGTH_MAX = 50;
    public const IMAGE_FILE_MAX = 5120;
    public const DETAIL_LENGTH_MAX = 2000;
    public const RELEASE_FLG_ON = 1;
    public const RELEASE_FLG_OFF = 2;
    public const RELEASE_FLG_LIST = [
        self::RELEASE_FLG_ON => '表示',
        self::RELEASE_FLG_OFF => '非表示',
    ];
    public const KEYWORD_LENGTH_MAX = 30;

    public const IMAGE_FILE_DIR = 'products';
    public const PRODUCT_NEWS_INSERT_MESSAGE = 'が製品情報に追加されました。';
    public const PRODUCT_NEWS_UPDATE_MESSAGE = 'の製品情報が更新されました。';

    public const PAGENATE_LIST_LIMIT = 12;
    public const ADMIN_PAGENATE_LIST_LIMIT = 10;
}