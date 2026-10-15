<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id(); // Auto-incrementing unique ID (1, 2, 3...)
            $table->timestamp('form_timestamp')->nullable();
            $table->string('nom');
            $table->string('prenom');
            $table->string('ecole')->nullable();
            $table->string('filiere')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->date('date_de_naissance')->nullable();
            $table->string('ville')->nullable();
            $table->string('quartier')->nullable();
            $table->boolean('is_present')->default(false); // For check-in status
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};