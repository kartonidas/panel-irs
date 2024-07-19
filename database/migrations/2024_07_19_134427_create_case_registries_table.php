<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseRegistriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_registries', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('customer_id');
            $table->string('customer_signature', 80)->nullable();
            $table->string('rs_signature', 30)->nullable();
            $table->string('case_number', 7)->nullable()->index('case_number');
            $table->string('opponent', 250)->nullable();
            $table->string('opponent_pesel', 15)->nullable();
            $table->string('opponent_regon', 15)->nullable();
            $table->string('opponent_nip', 15)->nullable();
            $table->string('opponent_krs', 100)->nullable();
            $table->string('opponent_street', 200)->nullable();
            $table->string('opponent_zip', 50)->nullable();
            $table->string('opponent_city', 200)->nullable();
            $table->string('opponent_phone', 50)->nullable();
            $table->string('opponent_email', 50)->nullable();
            $table->integer('status_id')->nullable();
            $table->boolean('death')->default(0);
            $table->date('date_of_death')->nullable();
            $table->boolean('insolvency')->default(0);
            $table->boolean('completed')->default(0);
            $table->string('baliff', 250)->nullable();
            $table->integer('court_id')->nullable();
            $table->decimal('balance', 8, 2)->nullable();
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
        Schema::dropIfExists('case_registries');
    }
}
