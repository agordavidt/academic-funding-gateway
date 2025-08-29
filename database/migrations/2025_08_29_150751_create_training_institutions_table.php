<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('training_institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description');
            $table->string('contact_email');
            $table->string('contact_phone', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->json('programs_offered')->nullable();
            $table->decimal('max_grant_amount', 10, 2)->default(500000.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('training_institutions');
    }
};