<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('dni')->comment('Documento de identidad')->unique()->primary();
            $table->foreignId('region_id')->constrained();;
            $table->foreignId('commune_id')->constrained();;
            $table->string('email')->unique();
            $table->string('name');
            $table->string('last_name');
            $table->string('address')->nullable();
            $table->timestamp('data_reg');
            $table->enum('status', ['A', 'I', 'trash'])->default('A')->comment('estado del registro: A: Activo I: Desactivo trash : Registro eliminado');
            $table->softDeletes();
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
        Schema::dropIfExists('customers');
    }
};
