<?php

namespace App\Models;

use App\Models\Analyz;
use App\Models\TestUnit;
use App\Models\adminTupe;
use App\Models\Antibiotic;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const ADMIN = 1;
    public const ACCOUNT = 2;

    public const MAINSECTION = 0;
    public const MAINANALYSIS = 1;

    public static function getLastIndex()
    {
      $lastItem=Self::where('account_id', auth('lab')->user()->id)->latest()->first();
      if($lastItem) return $lastItem->index;
      return 0;
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function (Section $section) {

            $section->index = Self::getLastIndex() + 1;
        });
    }

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

    public function antibioticSections()
    {
        return $this->hasMany(Antibiotic::class, 'section_id');
    }
    public function testMethod()
    {
        return $this->belongsTo(TestMethod::class);
    }
    public function testUnit()
    {
        return $this->belongsTo(TestUnit::class);
    }
    public function adminTupe()
    {
        return $this->belongsTo(adminTupe::class);
    }

    public function analyz()
    {
        return $this->hasMany(Analyz::class);
    }

    public function antibiotic()
    {
        return $this->hasMany(Antibiotic::class);
    }
    public function antibiotics()
    {
        return $this->hasMany(Antibiotic::class, 'analyz_id');
    }
    public function classReport(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }


    public function scopeAdminMainSection($q)
    {
        return $q->where('role', Section::ADMIN)->where('type', Section::MAINSECTION);
    }
    public function scopeAdminMAINANALYSIS($q)
    {
        return  $q->where('role', Section::ADMIN)->where('type', Section::MAINANALYSIS);
    }

    public function scopeAccoutMainSection($q)
    {
        return  $q->where('type', Section::MAINSECTION)->where('account_id', auth('lab')->user()->id);
    }
    public function scopeAccoutMainAnalys($q)
    {
        return  $q->where('type', Section::MAINANALYSIS)->where('account_id', auth('lab')->user()->id);
    }


    public function scopeAdminSections($q)
    {
        return $q->whereRole(self::ADMIN);
    }
}
