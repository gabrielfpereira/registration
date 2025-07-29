<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_name');
            $table->integer('class_number');
            $table->string('type')->default('infringement');// infringement, measure, suspension
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->date('registration_date_start')->nullable();
            $table->date('registration_date_end')->nullable();
            $table->string('director_name')->nullable();
            $table->string('observation')->nullable();
            $table->timestamps();
        });

        Schema::create('item_registration', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
