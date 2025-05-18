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
            $table->string('email')->unique();
            $table->string('city')->index();
            $table->enum('frequency', ['hourly', 'daily'])->default('daily');
            $table->boolean('confirmed')->default(false);
            $table->string('confirmation_token')->unique()->nullable();
            $table->string('unsubscribe_token')->unique()->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();

            $table->index(
                ['city', 'frequency', 'confirmed'],
                'subscriptions_city_frequency_confirmed_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
