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
        Schema::create("leads", function (Blueprint $table) {
            $table->id();
            $table->string("company_name");
            $table->string("contact_person")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->string("stage")->default("New Lead"); // Default to the first stage
            $table->decimal("value", 15, 2)->nullable();
            $table->integer("probability")->nullable(); // Percentage 0-100
            $table->string("source")->nullable();
            $table->text("notes")->nullable();
            // Add foreign key if leads are directly linked to a user or customer initially
            // $table->foreignId("user_id")->nullable()->constrained()->onDelete("set null");
            // $table->foreignId("customer_id")->nullable()->constrained()->onDelete("set null"); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("leads");
    }
};

