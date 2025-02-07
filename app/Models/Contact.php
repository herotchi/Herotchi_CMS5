<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

use App\Consts\ContactConsts;
use Illuminate\Support\Arr;

use DateTime;

class Contact extends Model
{
    //
    protected $table = 'contacts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'body',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function insertContact(array $data)
    {
        $this->user_id = Auth::user()->id;
        $this->status = ContactConsts::STATUS_NOT_STARTED;
        $this->fill($data);
        $this->save();

        $this->no = str_pad($this->id, ContactConsts::NO_LENGTH, 0, STR_PAD_LEFT);
        $this->save();

        return $this->no;
    }


    public function getAdminLists(array $data)
    {
        $query = $this::with('user');

        $query->when(Arr::exists($data, 'no') && $data['no'], function ($query) use ($data) {
            return $query->where('no', $data['no']);
        });

        $query->when(Arr::exists($data, 'body') && $data['body'], function ($query) use ($data) {
            return $query->where('body', 'like', "%{$data['body']}%");
        });

        $query->when(Arr::exists($data, 'created_at_from') && $data['created_at_from'], function ($query) use ($data) {
            $from = new DateTime($data['created_at_from']);
            $from->setTime(0, 0, 0);
            return $query->where('created_at', '>=',  $from->format('Y-m-d H:i:s'));
        });

        $query->when(Arr::exists($data, 'created_at_to') && $data['created_at_to'], function ($query) use ($data) {
            $to = new DateTime($data['created_at_to']);
            $to->setTime(23, 59, 59);
            return $query->where('created_at', '<=', $to->format('Y-m-d H:i:s'));
        });

        $query->when(Arr::exists($data, 'status') && $data['status'], function ($query) use ($data) {
            return $query->whereIn('status', $data['status']);
        });

        $query->orderBy('id', 'desc');

        $lists = $query->paginate(ContactConsts::PAGENATE_LIST_LIMIT);

        return $lists;
    }
}
