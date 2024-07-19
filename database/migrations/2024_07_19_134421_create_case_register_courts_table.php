<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseRegisterCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_register_courts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('case_registry_id');
            $table->string('signature', 200);
            $table->integer('court_id');
            $table->string('department', 250);
            $table->string('court_street', 500);
            $table->string('court_zip', 200);
            $table->string('court_city', 200);
            $table->integer('status_id');
            $table->integer('mode_id');
            $table->date('date');
            $table->date('date_enforcement')->nullable();
            $table->date('date_execution')->nullable();
            $table->decimal('cost_representation_court_proceedings', 8, 2)->nullable();
            $table->decimal('cost_representation_clause_proceedings', 8, 2)->nullable();
            $table->string('code_epu_warranty', 150)->nullable();
            $table->string('code_epu_clause', 150)->nullable();
            $table->string('code_epu_files', 150)->nullable();
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
        Schema::dropIfExists('case_register_courts');
    }
}
