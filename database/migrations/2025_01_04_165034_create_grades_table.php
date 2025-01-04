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
        Schema::create('grades', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key with UUID
            $table->string('name'); // Name of the grade (e.g., Grade 1, Year 5)
            $table->text('description')->nullable(); // Description of the grade
            $table->uuid('teacher_id')->nullable(); // User who is the teacher
            $table->uuid('created_by')->nullable(); // User who created the grade
            $table->uuid('updated_by')->nullable(); // User who last updated the grade
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status (active or inactive)
            $table->timestamps(); // Created_at and updated_at timestamps
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
