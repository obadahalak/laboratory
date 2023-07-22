<?php

namespace App\Models;

use App\Models\Gender;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function  patieonAnalys()
    {
        return $this->hasMany(PatieonAnalys::class);
    }
    public function  patieonAnalysWithData()
    {
        return $this->hasMany(PatieonAnalys::class)->with(
            'lab:id,account_id,lab_name',
            'doctor:id,account_id,name,email,ratio,phone,address',
            'company:id,account_id,name',
            'paymentMethod:id,account_id,name',
            'sendMethod:id,account_id,name',
            'section:id,name,test_print_name,admin_tupe_id',
            'analyz:id,section_id,test_print_name,admin_tupe_id'
        );
    }

    public function  account()
    {
        return $this->belongsTo(Account::class,'account_id');
    }


    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
}
