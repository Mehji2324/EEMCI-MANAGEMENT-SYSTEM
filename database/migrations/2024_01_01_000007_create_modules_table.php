<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();       // e.g. INFO101
            $table->text('description')->nullable();
            $table->integer('credits')->default(3);
            $table->string('semester');              // S1, S2, S3 ...
            $table->decimal('cc_weight', 4, 2)->default(0.40);   // CC weight in final avg
            $table->decimal('exam_weight', 4, 2)->default(0.60); // Exam weight
            $table->integer('volume_hours')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
