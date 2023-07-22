<?php

namespace App\Models;

use App\Models\Section;
use App\Models\TestUnit;
use App\Models\TestMethod;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Analyz extends Model
{
    use HasFactory;

    protected $table = 'analyzs';

    protected $guarded = [];


    public function priceForPatient(): Attribute
    {
        return new Attribute(
            get: fn ($value) => auth('lab')->user()->price_status == 1 ? (int) Str::substr($value, 0, -3) : $value,
            set: fn ($value) => $value,
        );
    }
    public function priceForLap(): Attribute
    {
        return new Attribute(
            get: fn ($value) => auth('lab')->user()->price_status == 1 ? (int) Str::substr($value, 0, -3) : $value,
            set: fn ($value) => $value,
        );
    }
    public function priceForCompany(): Attribute
    {
        return new Attribute(
            get: fn ($value) => auth('lab')->user()->price_status == 1 ? (int) Str::substr($value, 0, -3) : $value,
            set: fn ($value) => $value,
        );
    }
    public function adminTupe()
    {
        return $this->belongsTo(adminTupe::class);
    }


    public function antibiotic()
    {
        return $this->hasMany(Antibiotic::class, 'analyz_id');
    }

    public function antibiotics()
    {
        return $this->hasMany(Antibiotic::class, 'analyz_id');
    }

    public function TestUnit()
    {
        return $this->belongsTo(TestUnit::class);
    }

    public function TestMethod()
    {
        return $this->belongsTo(TestMethod::class);
    }

    public function Section()
    {
        return $this->belongsTo(Section::class);
    }

    public function classReport(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }
    public function scopeAdminSection($q)
    {
        return $q->select('id', 'test_print_name as name')->where('section_id', null)->where('account_id', null);
    }

    // public function scopeLabSection($q){
    //     return $q->select('id','test_print_name as name')->where('section_id',null);
    // }
    public function scopeAdminAnalys($q, $section_id)
    {
        return $q->select('id', 'test_print_name as name')->where('account_id', null)->where('section_id', $section_id);
    }
    public function scopeAccountAnalys($q)
    {
        return $q->where('account_id', auth('lab')->user()->id)->select('id', 'test_print_name as name');
    }
}
