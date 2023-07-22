<?php

namespace App\Models;

use App\Models\Lab;
use App\Models\Doctor;
use App\Models\Company;
use App\Models\PatieonAnalys;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SendPatient extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function patieonAnalys(){
        return $this->belongsTo(PatieonAnalys::class)->with(['patient',
        'doctor:id,account_id,name',
        'lab:id,account_id,lab_name','company:id,account_id,name']);
    }



}

