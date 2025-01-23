<?php

namespace App\Consts;

class TagConsts
{
    public const NAME_LENGTH_MAX = 20;
    public const CSV_FILE_MAX = 1024;
    public const CSV_CODE_UTF8 = 1;
    public const CSV_CODE_SJIS = 2;
    public const CSV_CODE_LIST = [
        self::CSV_CODE_UTF8 => 'UTF-8',
        self::CSV_CODE_SJIS => 'SJIS',
    ];
    public const CSV_HEADER = [
        'name' => 'タグ名'
    ];
    public const CSV_FILE_DIR = 'tag';

    public const PAGENATE_LIST_LIMIT = 10;
}