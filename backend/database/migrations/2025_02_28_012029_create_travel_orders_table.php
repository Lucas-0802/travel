<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
   
    public function up(): void
    {
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->uuid('user_id'); 

            $table->foreignId('origin_id')->constrained('cities')->onDelete('cascade'); 
            $table->foreignId('destination_id')->constrained('cities')->onDelete('cascade'); 
            $table->foreignId('travel_type_id')->constrained('travel_types')->onDelete('cascade'); 
            $table->date('departure_date'); 
            $table->date('return_date')->nullable(); 
            $table->unsignedBigInteger('status_id')->default(1);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('travel_order_status')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down(): void
    {   
        Schema::dropIfExists('travel_orders');
    }
};

