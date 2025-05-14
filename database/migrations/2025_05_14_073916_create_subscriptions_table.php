<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('city');
            $table->enum('frequency', ['hourly', 'daily'])->default('daily');
            $table->boolean('confirmed')->default(false);
            $table->string('confirmation_token')->unique();
            $table->string('unsubscribe_token')->unique();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();

            $table->unique(['email', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
