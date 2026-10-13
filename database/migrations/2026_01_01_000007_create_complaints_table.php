<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignUuid('resident_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('original_description');
            $table->string('original_language')->default('ceb');
            $table->text('english_translation')->nullable();
            $table->text('english_summary')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('complaint_categories')->nullOnDelete();
            $table->string('incident_location');
            $table->foreignId('purok_id')->nullable()->constrained('puroks')->nullOnDelete();
            $table->date('incident_date');
            $table->time('incident_time')->nullable();
            $table->string('ai_suggested_category')->nullable();
            $table->string('ai_suggested_priority')->nullable();
            $table->string('final_priority')->default('medium')->index();
            $table->text('priority_explanation')->nullable();
            $table->string('status')->default('submitted')->index();
            $table->foreignUuid('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resident_visible_remarks')->nullable();
            $table->string('ai_analysis_status')->default('pending')->index();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
