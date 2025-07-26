/* ===============================================================
   0.  MÉTADONNÉES GÉNÉRALES
   =============================================================== */
CREATE DATABASE IF NOT EXISTS logistixia_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE logistixia_db;

/* ===============================================================
   1.  CLIENTS
   =============================================================== */
CREATE TABLE clients (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    raison_sociale  VARCHAR(120)                NOT NULL,
    contact         VARCHAR(100),
    telephone       VARCHAR(20),
    email           VARCHAR(120),
    adresse         TEXT,
    type_client     ENUM('industriel','commercial','particulier') DEFAULT 'industriel',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* ===============================================================
   2.  CAMIONS
   =============================================================== */
CREATE TABLE camions (
    id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    matricule           VARCHAR(20)  NOT NULL UNIQUE,
    marque              VARCHAR(50),
    modele              VARCHAR(50),
    annee               SMALLINT,
    capacite_kg         INT,
    statut              ENUM('disponible','en_mission','panne','maintenance') DEFAULT 'disponible',
    est_interne         BOOLEAN      DEFAULT TRUE,
    societe_proprietaire VARCHAR(120),
    photo_url           VARCHAR(255),     -- chemin ou URL vers l’image
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* ===============================================================
   3.  REMORQUES
   =============================================================== */
CREATE TABLE remorques (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    matricule       VARCHAR(20)  NOT NULL UNIQUE,
    type            VARCHAR(60),
    capacite_max    DECIMAL(10,2),
    est_interne     BOOLEAN DEFAULT TRUE,
    societe_proprietaire VARCHAR(120),
    photo_url       VARCHAR(255),
    camion_id       BIGINT UNSIGNED,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_remorque_camion FOREIGN KEY (camion_id) REFERENCES camions(id) ON DELETE SET NULL
);

/* ===============================================================
   4.  CHAUFFEURS
   =============================================================== */
CREATE TABLE chauffeurs (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nom             VARCHAR(60)  NOT NULL,
    prenom          VARCHAR(60),
    date_naissance  DATE,
    telephone       VARCHAR(20),
    email           VARCHAR(120),
    adresse         TEXT,
    permis_num      VARCHAR(50),
    permis_categorie VARCHAR(10),      -- ex: C, CE
    permis_expire   DATE,
    statut          ENUM('titulaire','remplacant') DEFAULT 'titulaire',
    date_embauche   DATE,
    experience_annees SMALLINT,
    cin_num         VARCHAR(30),
    apte_medicalement BOOLEAN DEFAULT TRUE,
    photo_url       VARCHAR(255),
    document_permis VARCHAR(255),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* ===============================================================
   5.  ITINÉRAIRES (gabarit fixe de liaison Tana – Tôla, etc.)
   =============================================================== */
CREATE TABLE itineraires (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    lieu_depart     VARCHAR(120),
    lieu_arrivee    VARCHAR(120),
    distance_km     DECIMAL(8,2),
    duree_estimee_h DECIMAL(6,2),
    peage_estime    DECIMAL(10,2),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* ===============================================================
   6.  TRAJETS  (missions réelles)
   =============================================================== */
CREATE TABLE trajets (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    camion_id       BIGINT UNSIGNED,
    remorque_id     BIGINT UNSIGNED,
    chauffeur_id    BIGINT UNSIGNED,
    itineraire_id   BIGINT UNSIGNED,
    date_depart     DATETIME,
    date_arrivee_etd DATETIME,     -- Estimated
    date_arrivee_eta DATETIME,     -- Actual
    statut          ENUM('prevu','en_cours','termine','annule') DEFAULT 'prevu',
    commentaire     TEXT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_trajet_camion    FOREIGN KEY (camion_id)   REFERENCES camions(id),
    CONSTRAINT fk_trajet_remorque  FOREIGN KEY (remorque_id) REFERENCES remorques(id),
    CONSTRAINT fk_trajet_chauffeur FOREIGN KEY (chauffeur_id)REFERENCES chauffeurs(id),
    CONSTRAINT fk_trajet_itineraire FOREIGN KEY (itineraire_id) REFERENCES itineraires(id)
);

/* ===============================================================
   7.  MARCHANDISES par trajet + client
   =============================================================== */
CREATE TABLE marchandises (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    trajet_id       BIGINT UNSIGNED,
    client_id       BIGINT UNSIGNED,
    description     TEXT,
    poids_kg        DECIMAL(10,2),
    volume_m3       DECIMAL(10,2),
    valeur_estimee  DECIMAL(12,2),
    lieu_livraison  VARCHAR(120),
    statut          ENUM('chargee','en_transit','livree','retour') DEFAULT 'chargee',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (trajet_id) REFERENCES trajets(id),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

/* ===============================================================
   8.  GPS live (temps réel)
   =============================================================== */
CREATE TABLE suivis_gps (
    id          BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    camion_id   BIGINT UNSIGNED,
    latitude    DECIMAL(10,8) NOT NULL,
    longitude   DECIMAL(11,8) NOT NULL,
    vitesse_kmh DECIMAL(6,2),
    niveau_carburant DECIMAL(5,2),  -- pour boîtier OBD-II
    event_time  DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX ix_gps_camion_time (camion_id,event_time),
    FOREIGN KEY (camion_id) REFERENCES camions(id)
);

/* ===============================================================
   9.  PIECES & STOCK
   =============================================================== */
CREATE TABLE pieces (
    id            BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nom_piece     VARCHAR(120),
    ref_fournisseur VARCHAR(60),
    quantite      INT DEFAULT 0,
    prix_achat    DECIMAL(10,2),
    seuil_alerte  INT DEFAULT 5,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE mouvements_stock (
    id          BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    piece_id    BIGINT UNSIGNED,
    type        ENUM('entree','sortie'),
    quantite    INT,
    ref_text    VARCHAR(120),
    user_id     BIGINT UNSIGNED,
    event_date  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (piece_id) REFERENCES pieces(id),
    FOREIGN KEY (user_id)  REFERENCES utilisateurs(id)
);

/* ===============================================================
   10. FINANCES
   =============================================================== */
CREATE TABLE depenses (
    id          BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    camion_id   BIGINT UNSIGNED,
    trajet_id   BIGINT UNSIGNED,
    type        ENUM('carburant','reparation','peage','salaire','autre'),
    montant     DECIMAL(12,2),
    dep_date    DATE,
    notes       TEXT,
    FOREIGN KEY (camion_id) REFERENCES camions(id),
    FOREIGN KEY (trajet_id) REFERENCES trajets(id)
);

CREATE TABLE revenus (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    marchandise_id  BIGINT UNSIGNED,
    montant         DECIMAL(12,2),
    date_encaisse   DATE,
    notes           TEXT,
    FOREIGN KEY (marchandise_id) REFERENCES marchandises(id)
);

/* ===============================================================
   11. UTILISATEURS (pour Sanctum / JWT)
   =============================================================== */
CREATE TABLE utilisateurs (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nom_utilisateur VARCHAR(60) UNIQUE,
    email           VARCHAR(120) UNIQUE,
    mot_de_passe    VARCHAR(255),
    role            ENUM('admin','operateur','magasinier') DEFAULT 'operateur',
    actif           BOOLEAN DEFAULT TRUE,
    last_login      DATETIME,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
