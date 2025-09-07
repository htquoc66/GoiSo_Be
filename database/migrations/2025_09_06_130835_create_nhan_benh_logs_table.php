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
        Schema::create('nhan_benh_logs', function (Blueprint $table) {
                    $table->id();
            $table->unsignedInteger('quay');        // số quầy
            $table->string('phankhu')->nullable();  // phân khu
            $table->unsignedInteger('so');          // số vừa gọi
            $table->enum('loai', ['thuong','uutien']);
            $table->timestamps();

            $table->index(['quay', 'phankhu']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhan_benh_logs');
    }
};
