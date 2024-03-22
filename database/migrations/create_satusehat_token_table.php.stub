<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatusehatTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('satusehatintegration.database_connection_satusehat'))->create(config('satusehatintegration.token_table_name'), function (Blueprint $table) {
            $table->string('environment');
            $table->string('client_id');
            $table->longText('token');
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
        Schema::connection(config('satusehatintegration.database_connection_satusehat'))->dropIfExists(config('satusehatintegration.token_table_name'));
    }
}
