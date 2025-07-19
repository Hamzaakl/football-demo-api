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
        Schema::create('match_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->constrained('fixtures');
            $table->foreignId('team_id')->constrained('teams');
            
            // Temel istatistikler
            $table->integer('shots_on_goal')->nullable();
            $table->integer('shots_off_goal')->nullable();
            $table->integer('total_shots')->nullable();
            $table->integer('blocked_shots')->nullable();
            $table->integer('shots_inside_box')->nullable();
            $table->integer('shots_outside_box')->nullable();
            
            // Faul ve kartlar
            $table->integer('fouls')->nullable();
            $table->integer('corner_kicks')->nullable();
            $table->integer('offsides')->nullable();
            $table->string('ball_possession')->nullable(); // %50 format
            $table->integer('yellow_cards')->nullable();
            $table->integer('red_cards')->nullable();
            
            // Kaleci istatistikleri
            $table->integer('goalkeeper_saves')->nullable();
            $table->integer('total_passes')->nullable();
            $table->integer('passes_accurate')->nullable();
            $table->string('passes_percentage')->nullable();
            
            // Ek istatistikler
            $table->json('additional_stats')->nullable(); // Diğer istatistikler
            
            $table->timestamps();

            $table->unique(['fixture_id', 'team_id']);
            $table->index('fixture_id');
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
        Schema::dropIfExists('match_statistics');
    }
};
