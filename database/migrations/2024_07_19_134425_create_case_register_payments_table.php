<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseRegisterPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_register_payments', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('case_registry_id')->index('case_registry_id');
            $table->date('date')->nullable();
            $table->decimal('amount', 8, 2)->nullable();
            $table->string('currency', 3)->default('PLN');
            $table->decimal('amount_pln', 8, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_register_payments');
    }
}
