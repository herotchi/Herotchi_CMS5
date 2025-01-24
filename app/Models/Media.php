<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Consts\MediaConsts;
use Illuminate\Support\Arr;

class Media extends Model
{
    //
    protected $table = 'media';
    protected $primaryKey = 'id';

    protected $fillable = [
        'media_flg',
        'alt',
        'url',
        'release_flg',
    ];


    public function insertMedia(array $data, string $fileName)
    {
        $this->image = 'storage/' . MediaConsts::IMAGE_FILE_DIR . '/' . $fileName;
        $this->fill($data);

        $this->save();
    }
}
