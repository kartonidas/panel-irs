<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 250)->nullable();
            $table->string('street', 500)->nullable();
            $table->string('zip', 200)->nullable();
            $table->string('city', 200)->nullable();
            $table->string('phone', 200)->nullable();
            $table->string('fax', 200)->nullable();
            $table->string('email', 200)->nullable();
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
        Schema::dropIfExists('courts');
    }
}
