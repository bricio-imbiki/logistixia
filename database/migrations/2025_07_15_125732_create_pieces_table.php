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
       Schema::create('pieces', function (Blueprint $table) {
            $table->id();
            $table->string('nom_piece', 120);
            $table->string('ref_fournisseur', 60)->nullable();
            $table->integer('quantite')->default(0);
            $table->decimal('prix_achat', 10, 2)->nullable();
            $table->integer('seuil_alerte')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pieces');
    }
};
