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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_team_id')->unique();
            $table->string('name');
            $table->string('code', 10)->nullable(); // Takım kodu (BES, GS vb.)
            $table->string('country')->nullable();
            $table->integer('founded')->nullable();
            $table->boolean('national')->default(false);
            $table->string('logo')->nullable();
            $table->json('venue')->nullable(); // Stadyum bilgileri
            $table->timestamps();

            $table->index('api_team_id');
            $table->index(['country', 'national']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
};
