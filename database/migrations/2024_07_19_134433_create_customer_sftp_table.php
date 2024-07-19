<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSftpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_sftp', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('customer_id')->unique('customer_id');
            $table->string('host', 200)->nullable();
            $table->integer('port')->nullable();
            $table->string('login', 150)->nullable();
            $table->text('password')->nullable();
            $table->string('path', 150)->nullable();
            $table->enum('transfer_type', ['active', 'passive'])->nullable();
            $table->boolean('ssl')->default(0);
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
        Schema::dropIfExists('customer_sftp');
    }
}
