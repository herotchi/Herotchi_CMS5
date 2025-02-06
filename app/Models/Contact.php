<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Consts\ContactConsts;
use Illuminate\Support\Facades\Auth;

class Contact extends Model
{
    //
    protected $table = 'contacts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'body',
    ];


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
}
