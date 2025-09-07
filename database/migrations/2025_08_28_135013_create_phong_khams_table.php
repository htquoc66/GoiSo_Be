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
        Schema::create('phong_khams', function (Blueprint $table) {
             $table->id();
            $table->string('ma_phong')->unique(); // Mã phòng
            $table->unsignedInteger('so_hien_tai')->default(0); // Số hiện tại
            $table->unsignedInteger('so_uu_tien')->default(0); // Số hiện tại
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
        Schema::dropIfExists('phong_khams');
    }
};
