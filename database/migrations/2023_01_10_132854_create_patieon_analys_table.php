<?php

use App\Models\Lab;
use App\Models\Analyz;
use App\Models\Doctor;
use App\Models\Company;
use App\Models\Patient;
use App\Models\Section;
use App\Models\SendMethod;
use App\Models\PaymentMethod;
use PhpParser\Node\Expr\Cast\Double;
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
        Schema::create('patieon_analys', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();


            $table->foreignIdFor(Doctor::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('price_doctor')->nullable();
            $table->double('ratio_price')->nullable();

            $table->foreignIdFor(Lab::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('price_lab')->nullable();

            $table->foreignIdFor(Company::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('price_company')->nullable();


            ///
            $table->foreignIdFor(SendMethod::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->boolean('emergency');
            $table->text('notes')->nullable();





            $table->foreignIdFor(Section::class) ->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Analyz::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();




            $table->foreignIdFor(PaymentMethod::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('patieon_analys');
    }
};
