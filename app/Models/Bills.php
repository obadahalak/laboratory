<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pays()
    {
        return $this->hasMany(Pay::class);
    }
}
