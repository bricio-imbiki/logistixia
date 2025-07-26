<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Exécuter les migrations : Création de toutes les tables pour le système logistique.
     */
    public function up(): void
    {
        // TABLE : clients
        Schema::create('clients', function (Blueprint $table) {
            $table->id('client_id');
            $table->string('nom_entreprise', 100);
            $table->string('contact', 100)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('adresse')->nullable();
            $table->timestamps();
        });

        // TABLE : camions
        Schema::create('camions', function (Blueprint $table) {
            $table->id('camion_id');
            $table->string('matricule', 20)->unique();
            $table->string('marque', 50)->nullable();
            $table->string('modele', 50)->nullable();
            $table->integer('capacite_kg')->nullable();
            $table->enum('statut', ['disponible', 'en mission', 'panne', 'maintenance'])->default('disponible');
            $table->boolean('est_interne')->default(true);
            $table->string('societe_proprietaire', 100)->nullable();
            $table->timestamps();
        });

        // TABLE : remorques
        Schema::create('remorques', function (Blueprint $table) {
            $table->id('remorque_id');
            $table->string('matricule', 20)->unique();
            $table->string('type', 50);
            $table->decimal('capacite_max', 10, 2)->nullable();
            $table->boolean('est_interne')->default(true);
            $table->string('societe_proprietaire', 100)->nullable();
            $table->foreignId('camion_id')->nullable()->constrained('camions')->nullOnDelete();
            $table->timestamps();
        });

        // TABLE : chauffeurs
        Schema::create('chauffeurs', function (Blueprint $table) {
            $table->id('chauffeur_id');
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('telephone', 20);
            $table->string('permis', 50);
            $table->enum('statut', ['titulaire', 'remplaçant'])->default('titulaire');
            $table->timestamps();
        });

        // TABLE : itineraires
        Schema::create('itineraires', function (Blueprint $table) {
            $table->id('itineraire_id');
            $table->string('lieu_depart', 100);
            $table->string('lieu_arrivee', 100);
            $table->decimal('distance_km', 6, 2);
            $table->decimal('duree_estimee_hr', 5, 2);
            $table->timestamps();
        });

        // TABLE : trajets
        Schema::create('trajets', function (Blueprint $table) {
            $table->id('trajet_id');
            $table->foreignId('camion_id')->constrained('camions');
            $table->foreignId('remorque_id')->nullable()->constrained('remorques');
            $table->foreignId('chauffeur_id')->constrained('chauffeurs');
            $table->foreignId('itineraire_id')->constrained('itineraires');
            $table->dateTime('date_depart');
            $table->dateTime('date_arrivee_estimee');
            $table->dateTime('date_arrivee_reelle')->nullable();
            $table->enum('statut', ['prévu', 'en cours', 'terminé', 'annulé'])->default('prévu');
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });

        // TABLE : marchandises
        Schema::create('marchandises', function (Blueprint $table) {
            $table->id('marchandise_id');
            $table->foreignId('trajet_id')->constrained('trajets');
            $table->foreignId('client_id')->constrained('clients');
            $table->text('description')->nullable();
            $table->decimal('poids_kg', 10, 2);
            $table->decimal('valeur_estimee', 12, 2)->nullable();
            $table->string('lieu_livraison', 100);
            $table->enum('statut_livraison', ['chargée', 'en transit', 'livrée'])->default('chargée');
            $table->timestamps();
        });

        // TABLE : suivis GPS
        Schema::create('suivis_gps', function (Blueprint $table) {
            $table->id('suivi_id');
            $table->foreignId('camion_id')->constrained('camions')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('vitesse_kmh', 6, 2)->nullable();
            $table->timestamp('date_heure')->useCurrent();
            $table->timestamps();
        });

        // TABLE : pièces
        Schema::create('pieces', function (Blueprint $table) {
            $table->id('piece_id');
            $table->string('nom_piece', 100);
            $table->integer('quantite_stock')->default(0);
            $table->decimal('prix_achat', 10, 2);
            $table->integer('seuil_alerte')->default(5);
            $table->timestamps();
        });

        // TABLE : mouvements de stock
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id('mouvement_id');
            $table->foreignId('piece_id')->constrained('pieces');
            $table->date('date_mouvement');
            $table->enum('type_mouvement', ['entrée', 'sortie']);
            $table->integer('quantite');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // TABLE : dépenses
        Schema::create('depenses', function (Blueprint $table) {
            $table->id('depense_id');
            $table->foreignId('camion_id')->nullable()->constrained('camions');
            $table->foreignId('trajet_id')->nullable()->constrained('trajets');
            $table->enum('type_depense', ['carburant', 'réparation', 'péage', 'salaire', 'autre']);
            $table->decimal('montant', 10, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // TABLE : revenus
        Schema::create('revenus', function (Blueprint $table) {
            $table->id('revenu_id');
            $table->foreignId('marchandise_id')->constrained('marchandises');
            $table->decimal('montant', 10, 2);
            $table->date('date_reception');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // TABLE : utilisateurs
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id('utilisateur_id');
            $table->string('nom_utilisateur', 50)->unique();
            $table->string('mot_de_passe');
            $table->enum('role', ['admin', 'operateur', 'gestionnaire_stock']);
            $table->timestamps();
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
        Schema::dropIfExists('revenus');
        Schema::dropIfExists('depenses');
        Schema::dropIfExists('mouvements_stock');
        Schema::dropIfExists('pieces');
        Schema::dropIfExists('suivis_gps');
        Schema::dropIfExists('marchandises');
        Schema::dropIfExists('trajets');
        Schema::dropIfExists('itineraires');
        Schema::dropIfExists('chauffeurs');
        Schema::dropIfExists('remorques');
        Schema::dropIfExists('camions');
        Schema::dropIfExists('clients');
    }
};
