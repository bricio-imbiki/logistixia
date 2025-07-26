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
    Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('raison_sociale', 120);
            $table->string('contact', 100)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 120)->nullable();
            $table->text('adresse')->nullable();
            $table->enum('type_client', ['industriel', 'commercial', 'particulier'])->default('industriel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
