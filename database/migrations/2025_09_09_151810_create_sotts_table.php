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
        Schema::create('sotts', function (Blueprint $table) {
            $table->id();
    $table->unsignedInteger('phankhu')->unique();
    $table->unsignedInteger('sott_thuong')->default(0);
    $table->unsignedInteger('sott_uutien')->default(0);
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
        Schema::dropIfExists('sotts');
    }
};
