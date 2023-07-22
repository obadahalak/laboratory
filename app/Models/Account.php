<?php

namespace App\Models;

use App\Models\Photo;
use App\Models\Analyz;
use App\Models\Doctor;
use App\Models\Gender;
use App\Models\Company;
use App\Models\Patient;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\TestUnit;
use App\Models\Job_title;
use App\Models\SendMethod;
use App\Models\TestMethod;
use App\Models\PaymentMethod;
use App\Models\Scopes\ActiveAccountScope;
use App\Models\Specialization;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];

    protected $table = 'accounts';


    // Start-Relations///
    public function Section()
    {
        return $this->hasMany(Section::class);
    }

    public function SendPatient()
    {
        return $this->hasMany(SendPatient::class)->with(['patieonAnalys']);
    }
    public function recevedSendPatient(){
        return $this->hasMany(SendPatient::class)->where('recived_id',auth('lab')->user()->id)->with('patieonAnalys');

    }

    public function mainSection()
    {
        return $this->hasMany(Section::class)->where('type',Section::MAINSECTION);
    }
    public function mainAnalys()
    {
        return $this->hasMany(Section::class)->where('type',Section::MAINANALYSIS);
    }

   
    public function Supplier()
    {
        return $this->hasMany(Supplier::class)->with('bills_pays');
    }

    public function Jobtitle(){
        return $this->hasMany(Job_title::class);
    }
    public function specialization(){
        return $this->hasMany(Specialization::class);
    }
    // Supplier

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
    public function analyz()
    {
        return $this->hasMany(Analyz::class);
    }
    public function analyzWhereNotSectin()
    {
        return $this->hasMany(Analyz::class)->select('id','test_print_name as name','price_for_lap','price_for_company','price_for_patient')->where('section_id',null);
    }

    public function AccountImage()
    {
        return $this->morphOne(Photo::class, 'photo');
    }


    public function labs()
    {
        return $this->hasMany(Lab::class);
    }
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }


    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function patientsWithGender()
    {
        return $this->hasMany(Patient::class)->with('gender:account_id,id,name')->withWhereHas('patieonAnalysWithData');
    }

   

    ///// begin Settings  Account ////
    public function Genders()
    {
        return $this->hasMany(Gender::class);
    }


    public function setting()
    {
        return $this->hasMany(Setting::class);
    }

    public function testUnit()
    {
        return $this->hasMany(TestUnit::class)->select('id','unit')->orderBy('id','desc');
    }

    public function testMethod()
    {
        return $this->hasMany(TestMethod::class)->select('id','test_method')->orderBy('id','desc');
    }
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
    public function sendMethod()
    {
        return $this->hasMany(SendMethod::class);
    }
    public function paymentMethod()
    {
        return $this->hasMany(PaymentMethod::class);
    }
     // end-Relations///

    ///// end Settings  Account ////
    public function password(): Attribute
    {
        return new Attribute(

            set: fn ($value) => bcrypt($value),
           
        );
    }
    protected static function booted()
    {
        static::addGlobalScope(new ActiveAccountScope);
    }
}
