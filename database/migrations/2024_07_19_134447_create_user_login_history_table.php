<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoginHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_history', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->enum('source', ['office', 'site'])->nullable();
            $table->string('email');
            $table->string('ip', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('successful')->default(0);
            $table->integer('time')->nullable();
            
            $table->index(['source', 'email', 'successful'], 'source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_login_history');
    }
}
