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
        Schema::create('malaria_cases', function (Blueprint $table) {
            $table->uuid('id')->primary();  
            $table->string('status');       
            $table->string('age_group');    
            $table->string('sex');          
            $table->boolean('diagnosed');  
            $table->uuid('household_stat_id');  
            $table->timestamps();

            $table->foreign('household_stat_id')->references('id')->on('household_stats')->onDelete('cascade');
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('malaria_cases');
    }
};
