<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('cupom_id')->nullable()->after('frete');
            $table->foreign('cupom_id')->references('id')->on('coupons')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cupom_id']);
            $table->dropColumn('cupom_id');
        });
    }
}; 