<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $hidden = [
        
          'updated_at',
          'specialization_id',
          'job_title_id'
          ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
    return $this->hasOne(Job_title::class , 'id' , 'job_title_id');
    }

     public function spec()
    {
    return $this->hasOne(Specialization::class , 'id' , 'specialization_id');
    }

}
