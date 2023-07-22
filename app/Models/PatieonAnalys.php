<?php

namespace App\Models;

use App\Models\Patient;
use App\Models\SendMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatieonAnalys extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function patient(){
        return $this->belongsTo(Patient::class)->with('gender:id,name');
    }

    public function patientWithGender(){
        return $this->belongsTo(Patient::class);
    }

    public function antibiotic(){
        return $this->hasMany(Antibiotic::class);
    }

    public function sendMethod(){
        return $this->belongsTo(SendMethod::class);
    }
    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }
    public function section(){
        return $this->belongsTo(Section::class)->with('adminTupe');
    }
    public function analyz(){
        return $this->belongsTo(Analyz::class)->with('adminTupe');
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
    public function lab(){
        return $this->belongsTo(Lab::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
