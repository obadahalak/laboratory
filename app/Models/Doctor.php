<?php

namespace App\Models;

use App\Models\Account;
use App\Models\PatieonAnalys;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor  extends Authenticatable
{
    use HasFactory , HasApiTokens;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function DoctorImage(){
        return $this->morphOne(Photo::class,'photo');
    }

    public function password(): Attribute
    {
        return new Attribute(

            set: fn ($value) => bcrypt($value),
           
        );
    }

    public   function myPatient(){

        return $this->hasMany(PatieonAnalys::class,'doctor_id')->with(['patient','section','analyz']);
    }

}
