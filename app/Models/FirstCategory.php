<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Consts\FirstCategoryConsts;
use Illuminate\Support\Arr;

class FirstCategory extends Model
{
    use HasFactory;
    //
    protected $table = 'first_categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function second_categories(): HasMany
    {
        return $this->hasMany(SecondCategory::class);
    }


    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


    public function insertFirstCategory(array $data)
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

        $lists = $query->paginate(FirstCategoryConsts::PAGENATE_LIST_LIMIT, ['*'], 'page', $data['page']);
        $lists->appends($data);

        return $lists;
    }


    public function updateFirstCategory(array $data, $firstCategory)
    {
        $firstCategory->fill($data);
        $firstCategory->update();

        return $firstCategory;
    }


    public function getLists()
    {
        $lists = $this::all();

        return $lists;
    }
}
