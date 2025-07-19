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
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_league_id')->unique();
            $table->string('name');
            $table->string('type')->nullable(); // League, Cup
            $table->string('logo')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 3)->nullable();
            $table->integer('season')->nullable();
            $table->boolean('current')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->index(['api_league_id', 'season']);
            $table->index(['country', 'current']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leagues');
    }
};
