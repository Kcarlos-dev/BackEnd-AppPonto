<?php

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
        Schema::create('tabela_pontos', function (Blueprint $table) {
            $table->id();
            $table->string('cpf');
            $table->foreign('cpf')
                  ->references('cpf')
                  ->on('funcionarios')
                  ->onDelete('cascade');
            $table->string('email');
            $table->string('hora');
            $table->string('data_ponto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabela_pontos');
    }
};
