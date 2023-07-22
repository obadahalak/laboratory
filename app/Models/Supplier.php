<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bills()
    {
        return $this->hasMany(Bills::class);
    }

    public function bills_pays()
    {
        return $this->hasMany(Bills::class)->with('pays');
    }

}
