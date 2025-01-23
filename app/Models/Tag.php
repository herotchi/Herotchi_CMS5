<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Consts\TagConsts;
use Illuminate\Support\Arr;

class Tag extends Model
{
    //
    protected $table = 'tags';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];


    public function insertTag(array $data)
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

        $lists = $query->paginate(TagConsts::PAGENATE_LIST_LIMIT);

        return $lists;
    }


    public function updateTag(array $data, $tag)
    {
        $tag->fill($data);
        $tag->update();

        return $tag;
    }


    public function getLists()
    {
        $lists = $this::all();

        return $lists;
    }
}
