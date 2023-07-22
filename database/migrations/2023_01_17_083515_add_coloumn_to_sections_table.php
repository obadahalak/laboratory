<?php

use App\Models\TestUnit;
use App\Models\adminTupe;
use App\Models\TestMethod;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {


            $table->text('test_code')->nullable();
            $table->string('test_print_name')->nullable();
            $table->double('price_for_patient')->nullable();
            $table->double('price_for_lap')->nullable();
            $table->double('price_for_company')->nullable();

            $table->json('class_report')->nullable();

            $table->foreignIdFor(adminTupe::class)->nullable()->constrained('admin_tupes')->cascadeOnDelete()->cascadeOnUpdate();


            $table->foreignIdFor(TestMethod::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(TestUnit::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('once')->nullable();
            $table->boolean('antibiotic')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('test_code');
            $table->dropColumn('test_print_name');
            $table->dropColumn('price_for_patient');
            $table->dropColumn('price_for_lap');
            $table->dropColumn('price_for_company');
            $table->dropColumn('class_report');
            $table->dropColumn('test_method_id');
            $table->dropColumn('test_unit_id');
            $table->dropColumn('once');
            $table->dropColumn('antibiotic');
        });
    }
};
