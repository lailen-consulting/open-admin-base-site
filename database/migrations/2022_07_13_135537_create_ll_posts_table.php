<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ll_posts', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt');
            $table->longText('content');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('published_at');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ll_posts');
    }
};
