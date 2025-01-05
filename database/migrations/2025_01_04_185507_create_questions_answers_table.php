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
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key with UUID

            $table->string('question'); // Text of the question
            $table->uuid('type_id'); // Foreign key for question type (multi-choice, subjective, etc.)
            $table->uuid('grade_id'); // Foreign key for grade (class)
            $table->uuid('created_by'); // Foreign key for the user who created the question
            $table->uuid('updated_by'); // Foreign key for the user who updated the question
            $table->integer('marks')->default(0); // Marks associated with the question

            $table->timestamps(); // Created_at and updated_at
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('type_id');
            $table->index('grade_id');
            $table->index('created_by');
        });

        // Create answers table
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key with UUID
            $table->uuid('question_id'); // Foreign key referencing questions table
            $table->text('answer_text'); // Text of the answer option
            $table->boolean('is_correct')->default(false); // Flag to mark the correct answer

            $table->timestamps(); // Created_at and updated_at

            // Foreign key constraints
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');

            // Index
            $table->index('question_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
    }
};
