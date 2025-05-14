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
        Schema::create("tasks", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description")->nullable();
            $table->date("due_date")->nullable();
            $table->string("status"); // Consider Enum or dedicated status table
            $table->foreignId("assigned_to_user_id")->nullable()->constrained("users")->onDelete("set null"); // Assuming a users table exists
            $table->foreignId("related_customer_id")->nullable()->constrained("customers")->onDelete("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tasks");
    }
};

