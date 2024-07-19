<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_users', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 200)->nullable();
            $table->string('email');
            $table->string('password', 190)->nullable();
            $table->boolean('active')->default(1)->index('admin_active_deleted_index');
            $table->boolean('block')->default(0);
            $table->enum('block_reason', ['credentials', 'inactive_long_time', 'admin'])->nullable();
            $table->integer('office_permission_id');
            $table->rememberToken();
            $table->integer('last_login')->nullable();
            $table->integer('last_activity')->nullable();
            $table->enum('case_access_type', ['all', 'selected'])->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_users');
    }
}
