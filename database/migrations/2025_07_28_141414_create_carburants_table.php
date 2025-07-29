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
        Schema::create('carburants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('camion_id')->constrained()->onDelete('cascade');
            $table->foreignId('trajet_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date_achat');
            $table->decimal('quantite_litres', 8, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('prix_total', 12, 2);
            $table->string('station');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carburants');
    }
};
