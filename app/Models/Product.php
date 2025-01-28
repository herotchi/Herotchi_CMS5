<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Support\Arr;
use App\Consts\ProductConsts;
use Illuminate\Support\Facades\Storage;

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

    public function tags(): BelongsToMany
    {
        // デフォルトだと中間テーブルのテーブル名の組み合わせはアルファベット順になる
        return $this->belongsToMany(Tag::class)->withTimestamps();
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
        $query = $this::with(['first_category', 'second_category', 'tags']);

        $query->when(Arr::exists($data, 'first_category_id') && $data['first_category_id'], function ($query) use ($data) {
            return $query->where('first_category_id', $data['first_category_id']);
        });

        $query->when(Arr::exists($data, 'second_category_id') && $data['second_category_id'], function ($query) use ($data) {
            return $query->where('second_category_id', $data['second_category_id']);
        });

        $query->when(Arr::exists($data, 'tag_ids') && $data['tag_ids'] && is_array($data['tag_ids']) && count($data['tag_ids']) > 0, function ($query) use ($data) {
            return $query->whereIn('id', function ($subQuery) use ($data) {
                $subQuery->select('product_id')
                    ->from('product_tag')
                    ->whereIn('tag_id', $data['tag_ids'])
                    ->groupBy('product_id')
                    ->havingRaw('COUNT(DISTINCT tag_id) = ?', [count($data['tag_ids'])]);
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


    public function updateProduct(array $data, string $fileName, Product $product) 
    {
        if ($fileName !== '') {
            $product->image = 'storage/' . ProductConsts::IMAGE_FILE_DIR . '/' . $fileName;
        }
        $product->fill($data);
        $product->update();

        return $product;
    }


    public function batchDeleteProduct(array $data)
    {
        foreach ($data['delete_ids'] as $id) {
            $product = $this::find($id);
            $previousImages = explode('/', $product->image);
            $product->tags()->detach();
            $product->delete();
            Storage::disk('public')->delete(ProductConsts::IMAGE_FILE_DIR . '/' . $previousImages[2]);
        }
    }
}
