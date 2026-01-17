<?php

use App\Models\Admin;
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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Admin::class)->constrained()->cascadeOnDelete();
            $table->string('adminName');
            $table->string('action'); // pl. 'created', 'updated', 'deleted', 'viewed', 'exported'
            $table->enum('status', ['success', 'failed', 'critical'])->default('success');
            $table->string('category')->nullable(); // pl. 'auth, 'reservation', 'menu', 'user_management'
            $table->longText('description')->nullable(); // Részletes leírás az eseményről
            $table->string('target_type')->nullable(); // pl. 'App\Models\Reservation', 'App\Models\Role'
            $table->unsignedBigInteger('target_id')->nullable(); // A célentitás ID-ja
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
