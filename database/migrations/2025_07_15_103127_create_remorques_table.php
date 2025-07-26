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
    Schema::create('remorques', function (Blueprint $table) {
    $table->id();
    $table->string('matricule', 20)->unique();
    $table->string('type', 60)->nullable();
    $table->decimal('capacite_max', 10, 2)->nullable();
    $table->boolean('est_interne')->default(true);
    $table->string('societe_proprietaire', 120)->nullable();
    $table->string('photo_url', 255)->nullable();
    $table->foreignId('camion_id')->nullable()->constrained('camions')->nullOnDelete();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remorques');
    }
};
