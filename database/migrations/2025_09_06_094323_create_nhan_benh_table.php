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
        Schema::create('nhan_benh', function (Blueprint $table) {
            $table->id();
            $table->integer('quay')->unique();        // số quầy
            $table->string('phankhu', 10)->nullable();   // số phòng / phân khu
            $table->integer('sott_thuong')->default(0);  // số thường
            $table->integer('sott_uutien')->default(0);  // số ưu tiên
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
        Schema::dropIfExists('nhan_benh');
    }
};
