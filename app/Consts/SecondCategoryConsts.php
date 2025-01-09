<?php

namespace App\Consts;

class SecondCategoryConsts
{
    public const NAME_LENGTH_MAX = 50;
    public const CSV_FILE_MAX = 1024;
    public const CSV_CODE_UTF8 = 1;
    public const CSV_CODE_SJIS = 2;
    public const CSV_CODE_LIST = [
        self::CSV_CODE_UTF8 => 'UTF-8',
        self::CSV_CODE_SJIS => 'SJIS',
    ];
    public const CSV_HEADER = [
        'first_category_name' => '大カテゴリ名',
        'name' => '中カテゴリ名',
    ];
    public const CSV_FILE_DIR = 'second_category';

    public const PAGENATE_LIST_LIMIT = 10;
}