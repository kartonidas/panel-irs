<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->boolean('active')->default(0);
            $table->string('name', 250)->nullable();
            $table->string('street', 250)->nullable();
            $table->string('house_no', 50)->nullable();
            $table->string('apartment_no', 50)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('nip', 20)->nullable();
            $table->string('regon', 20)->nullable();
            $table->string('krs', 20)->nullable();
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
        Schema::dropIfExists('customers');
    }
}
