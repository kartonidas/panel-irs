<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseRegisterEnforcementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_register_enforcements', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('case_registry_id')->index('case_registry_id');
            $table->string('signature', 200);
            $table->string('baliff', 250);
            $table->string('baliff_street', 200);
            $table->string('baliff_zip', 50);
            $table->string('baliff_city', 200);
            $table->integer('execution_status_id');
            $table->date('date');
            $table->decimal('cost_representation_execution_proceedings', 8, 2)->nullable();
            $table->decimal('enforcement_costs', 8, 2)->nullable();
            $table->date('date_against_payment')->nullable();
            $table->date('date_ineffective')->nullable();
            $table->date('date_another_redemption')->nullable();
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
        Schema::dropIfExists('case_register_enforcements');
    }
}
