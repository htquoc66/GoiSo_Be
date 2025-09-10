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
        Schema::create('benh_nhans', function (Blueprint $table) {
           $table->id();
            $table->string('mathe')->nullable();
            $table->string('hoten')->nullable();
            $table->string('ngaysinh')->nullable();
            $table->unsignedInteger('phankhu');
            $table->unsignedInteger('sott');
            $table->timestamps();

            $table->index(['mathe', 'phankhu']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('benhnhans');
    }
};
