<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use App\Consts\ProductConsts;

class Product extends Model
{
    //
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = [
        'first_category_id',
        'second_category_id',
        'name',
        'detail',
        'release_flg',
    ];


    public function first_category(): BelongsTo
    {
        return $this->belongsTo(FirstCategory::class);
    }


    public function second_category(): BelongsTo
    {
        return $this->belongsTo(SecondCategory::class);
    }

    public function tabs(): BelongsToMany
    {
        // デフォルトだと中間テーブルのテーブル名の組み合わせはアルファベット順になる
        return $this->belongsToMany(Tab::class, 'tab_product')->withTimestamps();
    }


    public function insertProduct(array $data, string $fileName)
    {
        $this->image = 'storage/' . ProductConsts::IMAGE_FILE_DIR . '/' . $fileName;
        $this->fill($data);

        $this->save();

        return $this;
    }


    public function getAdminLists(array $data)
    {
        $query = $this::with(['first_category', 'second_category', 'tabs']);

        $query->when(Arr::exists($data, 'first_category_id') && $data['first_category_id'], function ($query) use ($data) {
            return $query->where('first_category_id', $data['first_category_id']);
        });

        $query->when(Arr::exists($data, 'second_category_id') && $data['second_category_id'], function ($query) use ($data) {
            return $query->where('second_category_id', $data['second_category_id']);
        });

        $query->when(Arr::exists($data, 'tab_ids') && $data['tab_ids'] && is_array($data['tab_ids']) && count($data['tab_ids']) > 0, function ($query) use ($data) {
            return $query->whereIn('id', function ($subQuery) use ($data) {
                $subQuery->select('product_id')
                    ->from('tab_product')
                    ->whereIn('tab_id', $data['tab_ids'])
                    ->groupBy('product_id')
                    ->havingRaw('COUNT(DISTINCT tab_id) = ?', [count($data['tab_ids'])]);
            });
        });

        $query->when(Arr::exists($data, 'name') && $data['name'], function ($query) use ($data) {
            return $query->where('name', 'like', "%{$data['name']}%");
        });

        $query->when(Arr::exists($data, 'release_flg') && $data['release_flg'], function ($query) use ($data) {
            return $query->whereIn('release_flg', $data['release_flg']);
        });

        $query->orderBy('id', 'desc');

        $lists = $query->paginate(ProductConsts::ADMIN_PAGENATE_LIST_LIMIT);

        return $lists;
    }
}
