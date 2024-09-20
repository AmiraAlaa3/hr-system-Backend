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
        Schema::create('annual_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('title');
            $table->string('description');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('numberOfDays');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    
    public function down(): void
    {
        Schema::dropIfExists('annual_holidays');
    }
};