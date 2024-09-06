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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->date('birthdate');
            $table->string('address');
            $table->string('phone_number');
            $table->date('hire_date');
            $table->integer('ssn')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->string('nationality');
            $table->string('position');
            $table->enum('Marital_status',['married','widowed','single']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};