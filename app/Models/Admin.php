<?php

namespace App\Models;

use App\Models\Section;

use App\Models\adminTupe;
use App\Models\AdminSetting;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory , HasApiTokens;

    protected $guarded=[];

   

    protected $hidden = [
        'password',
    ];


    public function adminTupe(){
        return $this->hasMany(adminTupe::class);
    }

    public function password(): Attribute
    {
        return new Attribute(

            set: fn ($value) => bcrypt($value),
           
        );
    }


   
}
