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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_fixture_id')->unique();
            $table->foreignId('league_id')->constrained('leagues');
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');
            $table->string('referee')->nullable();
            $table->string('timezone')->default('UTC');
            $table->dateTime('fixture_date');
            $table->string('venue_name')->nullable();
            $table->string('venue_city')->nullable();
            
            // Durum bilgileri
            $table->string('status')->default('NS'); // NS, 1H, 2H, FT vb.
            $table->string('status_long')->nullable();
            $table->integer('elapsed')->nullable(); // Geçen süre (dakika)
            
            // Skor bilgileri
            $table->integer('home_goals')->nullable();
            $table->integer('away_goals')->nullable();
            $table->json('score')->nullable(); // Devre skorları, penaltı vb.
            
            // Ek bilgiler
            $table->integer('round')->nullable();
            $table->integer('season');
            $table->boolean('is_live')->default(false);
            $table->timestamp('last_updated')->nullable();
            
            $table->timestamps();

            $table->index(['api_fixture_id']);
            $table->index(['league_id', 'season']);
            $table->index(['fixture_date', 'status']);
            $table->index(['is_live']);
            $table->index(['home_team_id', 'away_team_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixtures');
    }
};
