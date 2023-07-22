<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gender extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function scopeAdminGenders($q){
        return $q->where('account_id',null);
    }
}
