<?php

use App\Models\Analyz;
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
        Schema::create('antibiotics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Analyz::class)->nullable()->constrained('analyzs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('subject');
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
        Schema::dropIfExists('antibiotics');
    }
};
