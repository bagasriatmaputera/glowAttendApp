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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('date');
            $table->timestamp('clock_in');
            $table->timestamp('clock_out')->nullable();
            $table->string('latitude_in');
            $table->string('longitude_in');
            $table->string('latitude_out')->nullable();
            $table->string('longitude_out')->nullable();
            $table->string('photo_in_url')->nullable();
            $table->string('photo_out_url')->nullable();
            $table->enum('status', ['present', 'late', 'on_leave', 'absent']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
