<?php

use App\Models\Account;
use App\Models\Analyz;
use App\Models\Company;
use App\Models\Doctor;
use App\Models\Gender;
use App\Models\Lab;
use App\Models\Section;
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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('address');
            $table->string('age');
            $table->date('date_of_visit')->format('Y-m-d');
            $table->date('receive_of_date')->format('Y-m-d');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->foreignIdFor(Gender::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();




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
        Schema::dropIfExists('patients');
    }
};
