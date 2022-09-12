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
        Schema::create('ll_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->string('title')->nullable();
            $table->string('type', 10)->nullable();
            $table->unsignedBigInteger('attachable_id');
            $table->string('attachable_type');
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
        Schema::dropIfExists('attachments');
    }
};
