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
        Schema::create('ll_configs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('key');
            $table->longText('value');
            $table->string('type'); // textarea text number etc. input type atan
            $table->longText('options')->nullable();
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
        Schema::dropIfExists('ll_configs');
    }
};
