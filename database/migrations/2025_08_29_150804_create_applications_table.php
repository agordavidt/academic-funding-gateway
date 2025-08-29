<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_institution_id')->nullable()->constrained()->nullOnDelete();
            $table->text('need_assessment_text');
            $table->json('supporting_documents')->nullable();
            $table->timestamp('terms_agreed_at');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('approved_amount', 10, 2)->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('training_institution_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
};