<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Consts\TabConsts;
use Illuminate\Support\Arr;

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


    public function getAdminLists(array $data)
    {
        $query = $this::query();

        $query->when(Arr::exists($data, 'name') && $data['name'], function ($query) use ($data) {
            return $query->where('name', 'like', "%{$data['name']}%");
        });

        $query->orderBy('id', 'desc');

        $lists = $query->paginate(TabConsts::PAGENATE_LIST_LIMIT);

        return $lists;
    }
}
