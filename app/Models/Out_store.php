<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Out_store extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function store()
    {
        return $this->belongsTo(Store::class)->with('test_unit');
    }

}
