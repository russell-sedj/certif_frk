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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('libelle_demande');
            $table->string('notif_envoi');
            $table->string('notif_reponse')->nullable();
            $table->unsignedBigInteger('sessionpro_user_id');
            $table->foreign('sessionpro_user_id')->references('id')->on('sessionpro_user')->OnDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
