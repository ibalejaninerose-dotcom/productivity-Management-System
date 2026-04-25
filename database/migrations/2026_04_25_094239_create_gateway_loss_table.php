<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gateway_loss', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50);
            $table->string('method', 10)->default('GET');
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->json('content_payload')->nullable();
            $table->text('exec_corusage')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gateway_loss');
    }
};