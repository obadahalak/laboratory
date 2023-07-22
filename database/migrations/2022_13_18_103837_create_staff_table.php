<?php

use App\Models\Account;
use App\Models\Job_title;
use App\Models\Specialization;
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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->constrained('accounts');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('DOB');
            $table->string('address');
            $table->string('work_start');
            $table->foreignIdFor(Job_title::class)->nullable()->constrained();
            $table->foreignIdFor(Specialization::class)->nullable()->constrained();
            $table->string('experiance')->nullable();
            $table->string('note')->nullable();
            $table->string('collage')->nullable();
            $table->double('salary')->nullable();
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
        Schema::dropIfExists('staff');
    }
};
