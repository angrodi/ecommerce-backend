<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('direccion');
            $table->decimal('monto', 7, 2);
            $table->dateTime('fecha_creacion')->nullable();
            $table->dateTime('fecha_entrega')->nullable();
            $table->string('metodo_pago');
            $table->string('estado');

            $table->unsignedBigInteger('empleado_id')->nullable();
            $table->foreign('empleado_id')
                  ->references('id')->on('usuarios')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')
                  ->references('id')->on('usuarios')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

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
        Schema::dropIfExists('pedidos');
    }
}
