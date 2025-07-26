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
      Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piece_id')->constrained('pieces');
            $table->enum('type', ['entree', 'sortie']);
            $table->integer('quantite');
            $table->string('ref_text', 120)->nullable();
            // $table->foreignId('user_id')->constrained('utilisateurs');
            $table->dateTime('event_date')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};
