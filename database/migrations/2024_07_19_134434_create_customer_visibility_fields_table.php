<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerVisibilityFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_visibility_fields', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('customer_id')->index('customer_id');
            $table->string('section', 50)->nullable();
            $table->text('fields')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            
            $table->unique(['customer_id', 'section'], 'customer_id_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_visibility_fields');
    }
}
