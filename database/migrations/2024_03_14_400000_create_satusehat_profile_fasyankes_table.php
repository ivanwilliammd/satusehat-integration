<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection(config('satusehatintegration.database_connection'))->create('satusehat_profile_fasyankes', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('(gen_random_uuid())'))->primary();
            $table->enum('env', ['DEV', 'STG', 'PROD'])->default('DEV');
            $table->string('kode', 11)->unique();
            $table->string('ihs_id');
            $table->string('nama');
            $table->string('client_key');
            $table->string('secret_key');
            $table->string('organization_id');
            $table->string('alamat');
            $table->string('kota');
            $table->string('kode_pos');
            $table->string('kode_provinsi');
            $table->string('kode_kabupaten');
            $table->string('kode_kecamatan');
            $table->string('kode_kelurahan');
            $table->string('website');
            $table->string('phone');
            $table->string('email');
            $table->decimal('lat', 10, 8);
            $table->decimal('long', 11, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('satusehatintegration.database_connection'))->dropIfExists('satusehat_profile_fasyankes');
    }
};
