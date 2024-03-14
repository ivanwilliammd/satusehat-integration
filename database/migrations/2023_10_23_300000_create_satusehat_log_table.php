<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatusehatLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('satusehatintegration.database_connection'))->create(config('satusehatintegration.log_table_name'), function (Blueprint $table) {
            $table->string('response_id')->nullable();
            $table->string('action');
            $table->string('url');
            $table->json('payload')->nullable();
            $table->json('response');
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('satusehatintegration.database_connection'))->dropIfExists(config('satusehatintegration.log_table_name'));
    }
}
