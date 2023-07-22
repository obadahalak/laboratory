<?php

use App\Models\Account;
use App\Models\adminTupe;
use App\Models\Analyz;
use App\Models\Section;
use App\Models\TestMethod;
use App\Models\TestUnit;
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
        Schema::create('analyzs', function (Blueprint $table) {
            $table->id();
            $table->text('test_code');
            $table->string('test_print_name');
            $table->double('price_for_patient');
            $table->double('price_for_lap');
            $table->double('price_for_company');
            $table->json('class_report');
            $table->foreignIdFor(adminTupe::class)->constrained('admin_tupes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Account::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Section::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(TestMethod::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(TestUnit::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('once');
            $table->boolean('antibiotic')->default(false);
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
        Schema::dropIfExists('analyzs');
    }
};
