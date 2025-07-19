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
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->constrained('fixtures');
            $table->foreignId('team_id')->constrained('teams');
            $table->integer('elapsed'); // Dakika
            $table->integer('elapsed_plus')->nullable(); // Ek süre
            
            // Olay türü
            $table->string('type'); // Goal, Card, subst vb.
            $table->string('detail')->nullable(); // Normal Goal, Yellow Card vb.
            $table->text('comments')->nullable();
            
            // Oyuncu bilgileri
            $table->string('player_name')->nullable();
            $table->integer('player_id')->nullable();
            $table->string('assist_name')->nullable();
            $table->integer('assist_id')->nullable();
            
            $table->timestamps();

            $table->index(['fixture_id', 'elapsed']);
            $table->index(['type', 'detail']);
            $table->index('team_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_events');
    }
};
