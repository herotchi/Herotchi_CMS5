<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Consts\TagConsts;
use Illuminate\Support\Arr;

class Tag extends Model
{
    use HasFactory;
    //
    protected $table = 'tags';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];


    public function products(): BelongsToMany
    {
        // デフォルトだと中間テーブルのテーブル名の組み合わせはアルファベット順になる
        return $this->belongsToMany(Product::class)->withTimestamps();
    }


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

        $lists = $query->paginate(TagConsts::PAGENATE_LIST_LIMIT, ['*'], 'page', $data['page']);
        $lists->appends($data);

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
