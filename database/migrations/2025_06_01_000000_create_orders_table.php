<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nome_cliente');
            $table->string('telefone');
            $table->string('email');
            $table->text('endereco');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('frete', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('novo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}; 