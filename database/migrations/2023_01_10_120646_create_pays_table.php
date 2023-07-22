<?php

use App\Models\Bills;
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
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Bills::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('Amount_$_before_payment');
            $table->double('Amount_ID_before_payment');
            $table->double('Amount_$_after_payment');
            $table->double('Amount_ID_after_payment');
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
        Schema::dropIfExists('pays');
    }
};
