<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 15)->unique()->index();
            $table->string('email')->unique()->nullable()->index();
             $table->string('password')->nullable();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->text('address')->nullable();
            $table->string('school', 255)->nullable();
            $table->string('matriculation_number', 50)->nullable();
            $table->string('state_of_origin', 100)->nullable();
            $table->string('lga', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 15)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->string('passport_photo')->nullable();
            $table->enum('profile_completion_status', ['incomplete', 'completed'])->default('incomplete');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('application_status', ['not_started', 'pending', 'reviewing', 'accepted', 'rejected'])->default('not_started');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('profile_completed_at')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->timestamp('application_submitted_at')->nullable();          
            $table->boolean('is_admin')->default(false);
            $table->timestamp('last_login_at')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index(['payment_status', 'application_status']);
            $table->index('profile_completion_status');
            $table->index('is_admin');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
