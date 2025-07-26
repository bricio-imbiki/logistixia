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
      Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camion_id')->nullable()->constrained('camions');
            $table->foreignId('trajet_id')->nullable()->constrained('trajets');
            $table->enum('type', ['carburant', 'reparation', 'peage', 'salaire', 'autre']);
            $table->decimal('montant', 12, 2);
            $table->date('dep_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
