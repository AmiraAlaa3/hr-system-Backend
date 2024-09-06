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
        Schema::create('casual__holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reason');
            $table->enum('status',['approved','Unapproved','New']);
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
        Schema::dropIfExists('casual__holidays');
    }
};