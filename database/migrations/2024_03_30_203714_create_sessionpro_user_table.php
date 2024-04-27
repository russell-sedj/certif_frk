<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sessionpro_user', function (Blueprint $table) {
            $table->id();
            $table->integer('nb_presence')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('sessionpro_id');
            $table->foreign('sessionpro_id')->references('id')->on('sessionpros')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessionpro_user');
    }
};
