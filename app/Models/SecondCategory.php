<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Consts\SecondCategoryConsts;
use Illuminate\Support\Arr;

class SecondCategory extends Model
{
    use HasFactory;
    //
    protected $table = 'second_categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'first_category_id',
        'name',
    ];

    public function first_category(): BelongsTo
    {
        return $this->belongsTo(FirstCategory::class);
    }


    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    
    public function insertSecondCategory(array $data)
    {
        $this->fill($data);

        $this->save();
    }


    public function getAdminLists(array $data)
    {
        $query = $this::with('first_category');

        $query->when(Arr::exists($data, 'first_category_id') && $data['first_category_id'], function ($query) use ($data) {
            return $query->where('first_category_id', $data['first_category_id']);
        });

        $query->when(Arr::exists($data, 'name') && $data['name'], function ($query) use ($data) {
            return $query->where('name', 'like', "%{$data['name']}%");
        });

        $query->orderBy('id', 'desc');

        $lists = $query->paginate(SecondCategoryConsts::PAGENATE_LIST_LIMIT, ['*'], 'page', $data['page']);
        $lists->appends($data);

        return $lists;
    }


    public function updateSecondCategory(array $data, $secondCategory)
    {
        $secondCategory->fill($data);
        $secondCategory->update();

        return $secondCategory;
    }


    public function getLists()
    {
        $lists = $this::all();

        return $lists;
    }
}
