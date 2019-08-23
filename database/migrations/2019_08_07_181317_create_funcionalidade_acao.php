<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuncionalidadeAcao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionalidade_acao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('funcionalidade_id');
            $table->foreign('funcionalidade_id')->references('id')->on('funcionalidade');
            $table->integer('acao_id');
            $table->foreign('acao_id')->references('id')->on('acao');
            
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
        Schema::dropIfExists('funcionalidade_acao');
    }
}
