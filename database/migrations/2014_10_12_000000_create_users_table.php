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
            $table->string('phone_number')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address')->nullable();
            $table->string('school')->nullable();
            $table->string('matriculation_number')->nullable();
            $table->enum('registration_stage', ['imported', 'profile_completion', 'payment', 'completed'])->default('imported');
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->enum('application_status', ['pending', 'reviewing', 'accepted', 'rejected'])->default('pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};