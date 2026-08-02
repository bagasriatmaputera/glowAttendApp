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
        Schema::create('office_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->unsignedInteger('radius')->default(100);
            $table->time('clock_in_time');
            $table->time('clock_out_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('office_location_id')
                ->nullable()
                ->after('employee_id')
                ->constrained('office_locations')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['office_location_id']);
            $table->dropColumn('office_location_id');
        });

        Schema::dropIfExists('office_locations');
    }
};
