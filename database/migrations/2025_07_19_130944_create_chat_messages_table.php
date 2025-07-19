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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->constrained('fixtures');
            $table->foreignId('user_id')->constrained('users');
            $table->text('message');
            $table->string('user_name')->nullable(); // Kullanıcı adı cache için
            $table->string('user_avatar')->nullable(); // Avatar URL
            $table->boolean('is_system')->default(false); // Sistem mesajları
            $table->json('metadata')->nullable(); // Ekstra bilgiler (renk, badge vb.)
            $table->timestamps();

            $table->index(['fixture_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('is_system');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};
