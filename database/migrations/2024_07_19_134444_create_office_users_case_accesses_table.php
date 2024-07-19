<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeUsersCaseAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_users_case_accesses', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('office_user_id')->index('office_user_id');
            $table->integer('customer_id');
            $table->enum('type', ['all', 'selected'])->nullable();
            $table->text('case_numbers')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            
            $table->unique(['office_user_id', 'customer_id'], 'office_user_id_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_users_case_accesses');
    }
}
