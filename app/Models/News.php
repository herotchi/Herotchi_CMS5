<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Consts\NewsConsts;

class News extends Model
{
    //
    protected $table = 'news';
    protected $primaryKey = 'id';

    protected $casts = [
        'release_date' => 'date',
    ];

    protected $fillable = [
        'title',
        'link_flg',
        'release_date',
        'release_flg'
    ];


    public function insertNews(array $data)
    {
        if ($data['link_flg'] == NewsConsts::LINK_FLG_ON) {
            $this->url = $data['url'];
        } elseif ($data['link_flg'] == NewsConsts::LINK_FLG_OFF) {
            $this->overview = $data['overview'];
        }
        $this->fill($data);

        $this->save();
    }
}
