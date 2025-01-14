<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Consts\TabConsts;

class Tab extends Model
{
    //
    protected $table = 'tabs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];


    public function insertTab(array $data)
    {
        $this->fill($data);

        $this->save();
    }
}
