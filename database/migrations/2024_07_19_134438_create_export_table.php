<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->enum('source', ['office', 'site'])->nullable();
            $table->integer('user_id')->nullable();
            $table->string('file', 250);
            $table->string('filename', 250);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export');
    }
}
