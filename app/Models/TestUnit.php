<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestUnit extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $hidden = [
        'created_at',
        'updated_at',
        ];



    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function scopeAdmin($q){
        return $q->where('account_id',null);
    }
}
