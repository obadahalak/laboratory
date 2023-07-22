<?php

use App\Models\Account;
use App\Models\Measuring_unit;
use App\Models\TestUnit;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('product_name');
            $table->string('image');
            $table->string('description');
            $table->string('company');
            $table->date('expire_date')->nullable();
            $table->date('out_date')->nullable();
            $table->string('model');
            $table->foreignIdFor(TestUnit::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
};
