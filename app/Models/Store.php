<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at',
        'test_unit_id'

        ];

        public function test_unit()
        {
            return $this->belongsTo(TestUnit::class);
        }

        public function out()
        {
            return $this->hasMany(Out_store::class);
        }

}
