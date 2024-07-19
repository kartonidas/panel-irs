<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseRegisterClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_register_claims', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('case_registry_id')->index('case_registry_id');
            $table->decimal('amount', 8, 2);
            $table->char('currency', 3)->default('PLN');
            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('mark', 200)->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount_pln', 8, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_register_claims');
    }
}
