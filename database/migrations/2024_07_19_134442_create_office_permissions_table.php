<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_permissions', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 60)->nullable();
            $table->enum('role', ['admin', 'employee']);
            $table->enum('admin_permission_type', ['full', 'selected'])->nullable();
            $table->text('permissions');
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
        Schema::dropIfExists('office_permissions');
    }
}
