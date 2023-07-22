<?php

use App\Models\Account;
use App\Models\Analyz;
use App\Models\Lab;
use App\Models\Patient;
use App\Models\PatieonAnalys;
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
        Schema::create('send_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate(); ///sender
            $table->foreignId('recived_id')->nullable()->constrained('accounts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Patient::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Lab::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(PatieonAnalys::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('shipping_cost');
            $table->double('total_amount');
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
        Schema::dropIfExists('send_patients');
    }
};
