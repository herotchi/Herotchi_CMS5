<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Consts\FirstCategoryConsts;

class FirstCategory extends Model
{
    //
    protected $table = 'first_categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function insertFirstCategory(array $data)
    {
        $this->fill($data);

        $this->save();
    }
}
