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
  // migration correcte pour cette logique :
Schema::create('revenus', function (Blueprint $table) {
    $table->id();
    $table->foreignId('transport_id')->constrained('transports')->onDelete('cascade');
    $table->decimal('montant', 12, 2);
    $table->date('date_encaisse');
    $table->text('notes')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenus');
    }
};
