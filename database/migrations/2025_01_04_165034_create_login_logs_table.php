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
        Schema::create('login_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->index();
            $table->string('tag')->index();
            $table->string('ip_address')->nullable()->index();
            $table->string('user_agent')->nullable()->index();
            $table->boolean('is_successful')->index();
            $table->string('x_forwarded_for')->nullable()->index();
            $table->timestamp('logged_in_at')->nullable()->index();
            $table->timestamp('logged_out_at')->nullable()->index();
            $table->string('device')->nullable()->index();
            $table->string('browser')->nullable()->index();
            $table->string('platform')->nullable()->index();
            $table->boolean('is_mobile')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            // Combined Index for commonly queried fields
            $table->index(['email', 'tag']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
