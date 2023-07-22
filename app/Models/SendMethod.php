<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SendMethod extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function Account(){
        return $this->belongsTo(Account::class);
    }
}
