<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseRegisterDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_register_documents', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('case_registry_id')->index('case_registry_id');
            $table->string('name', 250);
            $table->string('file', 250);
            $table->string('origfile', 250)->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('case_register_documents');
    }
}
