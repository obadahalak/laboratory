<?php

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
        Schema::table('patients', function (Blueprint $table) {

            $table->double('price_analysis');
            $table->double('paid_up');
            $table->double('duo');
            $table->double('discount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('paid_up');
            $table->dropColumn('duo');
            $table->dropColumn('discount');
            $table->dropColumn('price_analysis');
        });
    }
};
