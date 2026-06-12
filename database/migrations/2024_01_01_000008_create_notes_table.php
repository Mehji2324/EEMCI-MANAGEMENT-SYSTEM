<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->decimal('cc_note', 5, 2)->nullable();
            $table->decimal('exam_note', 5, 2)->nullable();
            $table->decimal('average', 5, 2)->nullable(); // auto-computed on validation
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            // A student can only have one note entry per module
            $table->unique(['student_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
