
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION
    ;
SET
    time_zone = "+02:00";


CREATE TABLE `admins`(
    `id` INT(11) NOT NULL,
    `email` VARCHAR(50) DEFAULT NULL,
    `telephone` VARCHAR(45) DEFAULT NULL,
    `password` TEXT DEFAULT NULL,
    `status` TINYINT(1) DEFAULT NULL,
    `token` TEXT DEFAULT NULL,
    `personnes_idPersonne` INT(11) NOT NULL,
    `categorieAdmins_idCategorie` INT(11) NOT NULL,
    `services_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `adresses`(
    `id` INT(11) NOT NULL,
    `pays` VARCHAR(45) DEFAULT NULL,
    `ville` VARCHAR(45) DEFAULT NULL,
    `commune` VARCHAR(45) DEFAULT NULL,
    `quartier` VARCHAR(45) DEFAULT NULL,
    `avenue` VARCHAR(45) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `agents`(
    `id` INT(11) NOT NULL,
    `telephone` VARCHAR(15) DEFAULT NULL,
    `email` VARCHAR(45) DEFAULT NULL,
    `password` TEXT DEFAULT NULL,
    `token` TEXT DEFAULT NULL,
    `status` TINYINT(1) DEFAULT NULL,
    `personnes_idPersonne` INT(11) NOT NULL,
    `services_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `categorie_admins`(
    `id` INT(11) NOT NULL,
    `designation` VARCHAR(45) DEFAULT NULL,
    `status` TINYINT(1) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `clients`(
    `id` INT(11) NOT NULL,
    `telephone` VARCHAR(15) DEFAULT NULL,
    `email` VARCHAR(45) DEFAULT NULL,
    `password` TEXT DEFAULT NULL,
    `token` TEXT DEFAULT NULL,
    `status` TINYINT(1) DEFAULT NULL,
    `personnes_idPersonne` INT(11) NOT NULL,
    `services_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `commande_aliments`(
    `id` INT(11) NOT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `commandeClients_idCommande` INT(11) NOT NULL,
    `montant` DOUBLE DEFAULT NULL,
    `prixtotal` DOUBLE DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `commande_biogaz`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `commandeClients_idCommande` INT(11) NOT NULL,
    `montant` VARCHAR(45) DEFAULT NULL,
    `prixtotal` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `commande_clients`(
    `id` INT(11) NOT NULL,
    `statusCmd_id` INT(11) DEFAULT NULL,
    `date` VARCHAR(45) DEFAULT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `clients_idClient` INT(11) NOT NULL,
    `id_sortie` INT(11) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `commande_fournisseurs`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `status` VARCHAR(45) DEFAULT NULL,
    `dateDebut` TIMESTAMP NULL DEFAULT NULL,
    `dateFin` TIMESTAMP NULL DEFAULT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `Fournisseurs_idFournisseur` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `commande_oeufs`(
    `id` INT(11) NOT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `commandeClients_idCommande` INT(11) NOT NULL,
    `montant` VARCHAR(45) DEFAULT NULL,
    `prixtotal` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `commande_poussins`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `commandeClients_idCommande` INT(11) NOT NULL,
    `montant` VARCHAR(45) DEFAULT NULL,
    `prixtotal` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `entrees`(
    `id` INT(11) NOT NULL,
    `date` TIMESTAMP NULL DEFAULT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `motifSorties_idMotif` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `entree_aliments`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `entrees_idEntree` INT(11) NOT NULL,
    `stock_Aliments_idStock` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `entree_biogaz`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `entrees_idEntree` INT(11) NOT NULL,
    `stock_Biogaz_idStock` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `entree_oeufs`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `entrees_idEntree` INT(11) NOT NULL,
    `stock_Oeufs_idStock` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `entree_poussins`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `entrees_idEntree` INT(11) NOT NULL,
    `stock_Poussins_idStock` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `etat_rapport`(
    `id` INT(11) NOT NULL,
    `designation` VARCHAR(60) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(), `updated_at` TIMESTAMP NULL DEFAULT NULL) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
  
CREATE TABLE `fournisseurs`(
    `id` INT(11) NOT NULL,
    `telephone` VARCHAR(15) DEFAULT NULL,
    `email` VARCHAR(45) DEFAULT NULL,
    `logo` VARCHAR(45) DEFAULT NULL,
    `personnes_idPersonne` INT(11) NOT NULL,
    `services_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `incubations`(
    `id` INT(11) NOT NULL,
    `dateEntree` TIMESTAMP NULL DEFAULT NULL,
    `datePrevue` TIMESTAMP NULL DEFAULT NULL,
    `dateSortie` TIMESTAMP NULL DEFAULT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `status_id` INT(11) DEFAULT NULL,
    `agents_idAgent` INT(11) NOT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `log_activity`(
    `id` INT(11) NOT NULL,
    `table_name` VARCHAR(60) DEFAULT NULL,
    `type` VARCHAR(60) DEFAULT NULL,
    `date` TIMESTAMP NULL DEFAULT NULL,
    `userId` INT(11) DEFAULT NULL,
    `userRole` VARCHAR(70) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


CREATE TABLE `motif_sorties`(
    `id` INT(11) NOT NULL,
    `designation` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `natures`(
    `id` INT(11) NOT NULL,
    `designation` VARCHAR(45) DEFAULT NULL,
    `type` VARCHAR(45) DEFAULT NULL,
    `sexe` VARCHAR(45) DEFAULT NULL,
    `prixunitaire` FLOAT DEFAULT NULL,
    `devise` VARCHAR(45) DEFAULT NULL,
    `categorie` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `notifications`(
    `id` INT(11) NOT NULL,
    `date` TIMESTAMP NULL DEFAULT NULL,
    `titre` VARCHAR(120) NOT NULL,
    `description` TEXT NOT NULL,
    `auteurID` INT(11) NOT NULL,
    `role` VARCHAR(60) NOT NULL,
    `active` TINYINT(4) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'Notification que Nagritech envoie aux utilisateur de l''app.';

CREATE TABLE `personnes`(
    `id` INT(11) NOT NULL,
    `nom` VARCHAR(45) DEFAULT NULL,
    `postnom` VARCHAR(45) DEFAULT NULL,
    `prenom` VARCHAR(45) DEFAULT NULL,
    `sexe` VARCHAR(15) DEFAULT NULL,
    `adresses_idAdresse` INT(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `rapport_aliments`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `date` VARCHAR(45) DEFAULT NULL,
    `etat_rapportID` INT(11) DEFAULT NULL,
    `commentaire` TEXT DEFAULT NULL,
    `agents_idAgent` INT(11) NOT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `status_rapport_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `rapport_biogaz`(
    `id` INT(11) NOT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `date` TIMESTAMP NULL DEFAULT NULL,
    `etat_rapportID` INT(11) DEFAULT NULL,
    `commentaire` TEXT DEFAULT NULL,
    `agents_idAgent` INT(11) NOT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `status_rapport_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `rapport_oeufs`(
    `id` INT(11) NOT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `date` TIMESTAMP NULL DEFAULT NULL,
    `etat_rapportID` INT(11) DEFAULT NULL,
    `commentaire` TEXT DEFAULT NULL,
    `agents_idAgent` INT(11) NOT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `status_rapport_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `rapport_poussins`(
    `id` INT(11) NOT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `date` TIMESTAMP NULL DEFAULT NULL,
    `etat_rapportID` INT(11) DEFAULT NULL,
    `commentaire` TEXT DEFAULT NULL,
    `agents_idAgent` INT(11) NOT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `status_rapport_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `requetes`(
    `id` INT(11) NOT NULL,
    `question` TEXT DEFAULT NULL,
    `reponse` TEXT DEFAULT NULL,
    `destinateur` VARCHAR(45) DEFAULT 'all',
    `date` TIMESTAMP NULL DEFAULT NULL,
    `lecture` TINYINT(1) DEFAULT 0,
    `expediteur` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `reservation_cf`(
    `id` INT(11) NOT NULL,
    `dateEntree` TIMESTAMP NULL DEFAULT NULL,
    `dateFin` TIMESTAMP NULL DEFAULT NULL,
    `dateSortie` TIMESTAMP NULL DEFAULT NULL,
    `libelle` VARCHAR(45) DEFAULT NULL,
    `detail` TEXT DEFAULT NULL,
    `status` VARCHAR(45) DEFAULT NULL,
    `clients_idClient` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `reservation_pouletabattage`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `date` VARCHAR(45) DEFAULT NULL,
    `clients_idClient` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `services`(
    `id` INT(11) NOT NULL,
    `designation` VARCHAR(45) DEFAULT NULL,
    `abrege` VARCHAR(45) NOT NULL,
    `status` TINYINT(1) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `sorties`(
    `id` INT(11) NOT NULL,
    `date` VARCHAR(45) DEFAULT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `motifSorties_idMotif` INT(11) NOT NULL,
    `agents_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `sortie_aliments`(
    `id` INT(11) NOT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `sorties_idSortie` INT(11) NOT NULL,
    `clients_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `sortie_biogaz`(
    `id` INT(11) NOT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `sorties_idSortie` INT(11) NOT NULL,
    `clients_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `sortie_oeufs`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `sorties_idSortie` INT(11) NOT NULL,
    `clients_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `sortie_poussins`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `sorties_idSortie` INT(11) NOT NULL,
    `clients_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `status_commandes`(
    `id` INT(11) NOT NULL,
    `designation` VARCHAR(60) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


CREATE TABLE `status_incubation`(
    `id` INT(11) NOT NULL,
    `designation` VARCHAR(60) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(), `updated_at` TIMESTAMP NULL DEFAULT NULL) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
   
CREATE TABLE `status_rapport`(
    `id` INT(11) NOT NULL,
    `designation` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `stock_aliments`(
    `id` INT(11) NOT NULL,
    `designation_lot` VARCHAR(45) DEFAULT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `date` VARCHAR(45) DEFAULT NULL,
    `etat` INT(11) DEFAULT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

CREATE TABLE `stock_biogaz`(
    `id` INT(11) NOT NULL,
    `designation_lot` VARCHAR(45) DEFAULT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `date` TIMESTAMP NULL DEFAULT NULL,
    `etat` INT(11) DEFAULT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `stock_oeufs`(
    `id` INT(11) NOT NULL,
    `designation_lot` VARCHAR(45) DEFAULT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `date` TIMESTAMP NULL DEFAULT NULL,
    `etat` INT(11) DEFAULT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `stock_poussins`(
    `id` INT(11) NOT NULL,
    `designation_lot` VARCHAR(45) DEFAULT NULL,
    `quantite` FLOAT DEFAULT NULL,
    `date` TIMESTAMP NULL DEFAULT NULL,
    `etat` INT(11) DEFAULT NULL,
    `natures_idNature` INT(11) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;


CREATE TABLE `vente_pouletabattage`(
    `id` INT(11) NOT NULL,
    `quantite` VARCHAR(45) DEFAULT NULL,
    `date` VARCHAR(45) DEFAULT NULL,
    `client` INT(11) DEFAULT NULL,
    `reservation_PouletAbattage_idEntree` INT(11) NOT NULL,
    `prixunitaire` VARCHAR(45) DEFAULT NULL,
    `prixtotal` VARCHAR(45) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;
--

-- Index pour les tables déchargées
--

--

-- Index pour la table `admins`
--

ALTER TABLE
    `admins` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_admins_personnes`(`personnes_idPersonne`),
    ADD KEY `fk_admins_categorieAdmins1`(`categorieAdmins_idCategorie`),
    ADD KEY `fk_admins_services1`(`services_id`);
    --

    -- Index pour la table `adresses`
    --

ALTER TABLE
    `adresses` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `agents`
    --

ALTER TABLE
    `agents` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_agents_personnes1`(`personnes_idPersonne`),
    ADD KEY `fk_agents_services1`(`services_id`);
    --

    -- Index pour la table `categorie_admins`
    --

ALTER TABLE
    `categorie_admins` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `clients`
    --

ALTER TABLE
    `clients` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_clients_personnes1`(`personnes_idPersonne`),
    ADD KEY `fk_clients_services1`(`services_id`);
    --

    -- Index pour la table `commande_aliments`
    --

ALTER TABLE
    `commande_aliments` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_commandeAliments_commandeClients1`(`commandeClients_idCommande`);
    --

    -- Index pour la table `commande_biogaz`
    --

ALTER TABLE
    `commande_biogaz` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_commandeBiogaz_commandeClients1`(`commandeClients_idCommande`);
    --

    -- Index pour la table `commande_clients`
    --

ALTER TABLE
    `commande_clients` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_commandeClients_natures1`(`natures_idNature`),
    ADD KEY `fk_commandeClients_clients1`(`clients_idClient`);
    --

    -- Index pour la table `commande_fournisseurs`
    --

ALTER TABLE
    `commande_fournisseurs` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_commandeFournisseurs_natures1`(`natures_idNature`),
    ADD KEY `fk_commande_Fournisseurs_Fournisseurs1`(`Fournisseurs_idFournisseur`);
    --

    -- Index pour la table `commande_oeufs`
    --

ALTER TABLE
    `commande_oeufs` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_commandePoussinsOeufs_commandeClients1`(`commandeClients_idCommande`);
    --

    -- Index pour la table `commande_poussins`
    --

ALTER TABLE
    `commande_poussins` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_commandePoussins_commandeClients1`(`commandeClients_idCommande`);
    --

    -- Index pour la table `entrees`
    --

ALTER TABLE
    `entrees` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_entrees_natures1`(`natures_idNature`),
    ADD KEY `fk_entrees_motifSorties1`(`motifSorties_idMotif`);
    --

    -- Index pour la table `entree_aliments`
    --

ALTER TABLE
    `entree_aliments` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_entreesAliments_entrees1`(`entrees_idEntree`),
    ADD KEY `fk_entree_Aliments_stock_Aliments1`(`stock_Aliments_idStock`);
    --

    -- Index pour la table `entree_biogaz`
    --

ALTER TABLE
    `entree_biogaz` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_entreesBiogaz_entrees1`(`entrees_idEntree`),
    ADD KEY `fk_entree_Biogaz_stock_Biogaz1`(`stock_Biogaz_idStock`);
    --

    -- Index pour la table `entree_oeufs`
    --

ALTER TABLE
    `entree_oeufs` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_entree_Oeufs_entrees1`(`entrees_idEntree`),
    ADD KEY `fk_entree_Oeufs_stock_Oeufs1`(`stock_Oeufs_idStock`);
    --

    -- Index pour la table `entree_poussins`
    --

ALTER TABLE
    `entree_poussins` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_entreesPoussinsOeufs_entrees1`(`entrees_idEntree`),
    ADD KEY `fk_entree_PoussinsOeufs_stock_Poussins1`(`stock_Poussins_idStock`);
    --

    -- Index pour la table `etat_rapport`
    --

ALTER TABLE
    `etat_rapport` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `fournisseurs`
    --

ALTER TABLE
    `fournisseurs` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_Fornisseurs_personnes1`(`personnes_idPersonne`),
    ADD KEY `fk_Fournisseurs_services1`(`services_id`);
    --

    -- Index pour la table `incubations`
    --

ALTER TABLE
    `incubations` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_incubations_agents1`(`agents_idAgent`),
    ADD KEY `fk_incubations_natures1`(`natures_idNature`);
    --

    -- Index pour la table `log_activity`
    --

ALTER TABLE
    `log_activity` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `motif_sorties`
    --

ALTER TABLE
    `motif_sorties` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `natures`
    --

ALTER TABLE
    `natures` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `notifications`
    --

ALTER TABLE
    `notifications` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `personnes`
    --

ALTER TABLE
    `personnes` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_personnes_adresses1`(`adresses_idAdresse`);
    --

    -- Index pour la table `rapport_aliments`
    --

ALTER TABLE
    `rapport_aliments` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_rapportAliments_agents1`(`agents_idAgent`),
    ADD KEY `fk_rapportAliments_natures1`(`natures_idNature`),
    ADD KEY `fk_rapportAliments_status_rapport1`(`status_rapport_id`);
    --

    -- Index pour la table `rapport_biogaz`
    --

ALTER TABLE
    `rapport_biogaz` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_rapportBiogaz_agents1`(`agents_idAgent`),
    ADD KEY `fk_rapport_Biogaz_natures1`(`natures_idNature`),
    ADD KEY `fk_rapport_Biogaz_status_rapport1`(`status_rapport_id`);
    --

    -- Index pour la table `rapport_oeufs`
    --

ALTER TABLE
    `rapport_oeufs` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_rapport_Oeufs_agents1`(`agents_idAgent`),
    ADD KEY `fk_rapport_Oeufs_natures1`(`natures_idNature`),
    ADD KEY `fk_rapport_Oeufs_status_rapport1`(`status_rapport_id`);
    --

    -- Index pour la table `rapport_poussins`
    --

ALTER TABLE
    `rapport_poussins` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_rapportPoussinsOeufs_agents1`(`agents_idAgent`),
    ADD KEY `fk_rapport_Poussins_natures1`(`natures_idNature`),
    ADD KEY `fk_rapport_Poussins_status_rapport1`(`status_rapport_id`);
    --

    -- Index pour la table `requetes`
    --

ALTER TABLE
    `requetes` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `reservation_cf`
    --

ALTER TABLE
    `reservation_cf` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_reservationCf_clients1`(`clients_idClient`);
    --

    -- Index pour la table `reservation_pouletabattage`
    --

ALTER TABLE
    `reservation_pouletabattage` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_entreePoulets_clients1`(`clients_idClient`);
    --

    -- Index pour la table `services`
    --

ALTER TABLE
    `services` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `sorties`
    --

ALTER TABLE
    `sorties` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_sorties_natures1`(`natures_idNature`),
    ADD KEY `fk_sorties_motifSorties1`(`motifSorties_idMotif`),
    ADD KEY `fk_sorties_agents1`(`agents_id`);
    --

    -- Index pour la table `sortie_aliments`
    --

ALTER TABLE
    `sortie_aliments` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_sortieAliments_sorties1`(`sorties_idSortie`),
    ADD KEY `fk_sortie_aliments_clients1`(`clients_id`);
    --

    -- Index pour la table `sortie_biogaz`
    --

ALTER TABLE
    `sortie_biogaz` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_sortieBiogaz_sorties1`(`sorties_idSortie`),
    ADD KEY `fk_sortie_biogaz_clients1`(`clients_id`);
    --

    -- Index pour la table `sortie_oeufs`
    --

ALTER TABLE
    `sortie_oeufs` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_sortieOeufs_sorties1`(`sorties_idSortie`),
    ADD KEY `fk_sortie_poussins_clients1`(`clients_id`);
    --

    -- Index pour la table `sortie_poussins`
    --

ALTER TABLE
    `sortie_poussins` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_sortiePoussinsOeufs_sorties1`(`sorties_idSortie`),
    ADD KEY `fk_sortie_poussins_clients1`(`clients_id`);
    --

    -- Index pour la table `status_commandes`
    --

ALTER TABLE
    `status_commandes` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `status_incubation`
    --

ALTER TABLE
    `status_incubation` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `status_rapport`
    --

ALTER TABLE
    `status_rapport` ADD PRIMARY KEY(`id`);
    --

    -- Index pour la table `stock_aliments`
    --

ALTER TABLE
    `stock_aliments` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_stock_Aliments_natures1`(`natures_idNature`);
    --

    -- Index pour la table `stock_biogaz`
    --

ALTER TABLE
    `stock_biogaz` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_stock_Biogaz_natures1`(`natures_idNature`);
    --

    -- Index pour la table `stock_oeufs`
    --

ALTER TABLE
    `stock_oeufs` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_stock_Oeufs_natures1`(`natures_idNature`);
    --

    -- Index pour la table `stock_poussins`
    --

ALTER TABLE
    `stock_poussins` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_stock_Poussins_natures1`(`natures_idNature`);
    --

    -- Index pour la table `vente_pouletabattage`
    --

ALTER TABLE
    `vente_pouletabattage` ADD PRIMARY KEY(`id`),
    ADD KEY `fk_vente_PouletAbattage_reservation_PouletAbattage1`(
        `reservation_PouletAbattage_idEntree`
    );
    --

    -- AUTO_INCREMENT pour les tables déchargées
    --

    --

    -- AUTO_INCREMENT pour la table `admins`
    --

ALTER TABLE
    `admins` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `adresses`
    --

ALTER TABLE
    `adresses` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `agents`
    --

ALTER TABLE
    `agents` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `categorie_admins`
    --

ALTER TABLE
    `categorie_admins` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `clients`
    --

ALTER TABLE
    `clients` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `commande_aliments`
    --

ALTER TABLE
    `commande_aliments` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `commande_biogaz`
    --

ALTER TABLE
    `commande_biogaz` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `commande_clients`
    --

ALTER TABLE
    `commande_clients` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `commande_fournisseurs`
    --

ALTER TABLE
    `commande_fournisseurs` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `commande_oeufs`
    --

ALTER TABLE
    `commande_oeufs` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `commande_poussins`
    --

ALTER TABLE
    `commande_poussins` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `entrees`
    --

ALTER TABLE
    `entrees` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `entree_aliments`
    --

ALTER TABLE
    `entree_aliments` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `entree_biogaz`
    --

ALTER TABLE
    `entree_biogaz` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `entree_oeufs`
    --

ALTER TABLE
    `entree_oeufs` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `entree_poussins`
    --

ALTER TABLE
    `entree_poussins` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `etat_rapport`
    --

ALTER TABLE
    `etat_rapport` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `fournisseurs`
    --

ALTER TABLE
    `fournisseurs` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `incubations`
    --

ALTER TABLE
    `incubations` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `log_activity`
    --

ALTER TABLE
    `log_activity` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
    --

    -- AUTO_INCREMENT pour la table `motif_sorties`
    --

ALTER TABLE
    `motif_sorties` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `natures`
    --

ALTER TABLE
    `natures` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `notifications`
    --

ALTER TABLE
    `notifications` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `personnes`
    --

ALTER TABLE
    `personnes` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `rapport_aliments`
    --

ALTER TABLE
    `rapport_aliments` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `rapport_biogaz`
    --

ALTER TABLE
    `rapport_biogaz` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `rapport_oeufs`
    --

ALTER TABLE
    `rapport_oeufs` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `rapport_poussins`
    --

ALTER TABLE
    `rapport_poussins` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `requetes`
    --

ALTER TABLE
    `requetes` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `reservation_cf`
    --

ALTER TABLE
    `reservation_cf` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `reservation_pouletabattage`
    --

ALTER TABLE
    `reservation_pouletabattage` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `services`
    --

ALTER TABLE
    `services` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `sorties`
    --

ALTER TABLE
    `sorties` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `sortie_aliments`
    --

ALTER TABLE
    `sortie_aliments` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `sortie_biogaz`
    --

ALTER TABLE
    `sortie_biogaz` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `sortie_oeufs`
    --

ALTER TABLE
    `sortie_oeufs` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `sortie_poussins`
    --

ALTER TABLE
    `sortie_poussins` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `status_commandes`
    --

ALTER TABLE
    `status_commandes` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `status_incubation`
    --

ALTER TABLE
    `status_incubation` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `status_rapport`
    --

ALTER TABLE
    `status_rapport` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `stock_aliments`
    --

ALTER TABLE
    `stock_aliments` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `stock_biogaz`
    --

ALTER TABLE
    `stock_biogaz` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `stock_oeufs`
    --

ALTER TABLE
    `stock_oeufs` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
    --

    -- AUTO_INCREMENT pour la table `stock_poussins`
    --

ALTER TABLE
    `stock_poussins` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT =1;
    --

    -- AUTO_INCREMENT pour la table `vente_pouletabattage`
    --

ALTER TABLE
    `vente_pouletabattage` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
    --

    -- Contraintes pour les tables déchargées
    --

    --

    -- Contraintes pour la table `admins`
    --

ALTER TABLE
    `admins` ADD CONSTRAINT `fk_admins_categorieAdmins1` FOREIGN KEY(`categorieAdmins_idCategorie`) REFERENCES `categorie_admins`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_admins_personnes` FOREIGN KEY(`personnes_idPersonne`) REFERENCES `personnes`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_admins_services1` FOREIGN KEY(`services_id`) REFERENCES `services`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `agents`
    --

ALTER TABLE
    `agents` ADD CONSTRAINT `fk_agents_personnes1` FOREIGN KEY(`personnes_idPersonne`) REFERENCES `personnes`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_agents_services1` FOREIGN KEY(`services_id`) REFERENCES `services`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `clients`
    --

ALTER TABLE
    `clients` ADD CONSTRAINT `fk_clients_personnes1` FOREIGN KEY(`personnes_idPersonne`) REFERENCES `personnes`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_clients_services1` FOREIGN KEY(`services_id`) REFERENCES `services`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `commande_aliments`
    --

ALTER TABLE
    `commande_aliments` ADD CONSTRAINT `fk_commandeAliments_commandeClients1` FOREIGN KEY(`commandeClients_idCommande`) REFERENCES `commande_clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `commande_biogaz`
    --

ALTER TABLE
    `commande_biogaz` ADD CONSTRAINT `fk_commandeBiogaz_commandeClients1` FOREIGN KEY(`commandeClients_idCommande`) REFERENCES `commande_clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `commande_clients`
    --

ALTER TABLE
    `commande_clients` ADD CONSTRAINT `fk_commandeClients_clients1` FOREIGN KEY(`clients_idClient`) REFERENCES `clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_commandeClients_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `commande_fournisseurs`
    --

ALTER TABLE
    `commande_fournisseurs` ADD CONSTRAINT `fk_commandeFournisseurs_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_commande_Fournisseurs_Fournisseurs1` FOREIGN KEY(`Fournisseurs_idFournisseur`) REFERENCES `fournisseurs`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `commande_oeufs`
    --

ALTER TABLE
    `commande_oeufs` ADD CONSTRAINT `fk_commandePoussinsOeufs_commandeClients1` FOREIGN KEY(`commandeClients_idCommande`) REFERENCES `commande_clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `commande_poussins`
    --

ALTER TABLE
    `commande_poussins` ADD CONSTRAINT `commande_poussins_ibfk_1` FOREIGN KEY(`commandeClients_idCommande`) REFERENCES `commande_clients`(`id`);
    --

    -- Contraintes pour la table `entrees`
    --

ALTER TABLE
    `entrees` ADD CONSTRAINT `fk_entrees_motifSorties1` FOREIGN KEY(`motifSorties_idMotif`) REFERENCES `motif_sorties`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_entrees_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `entree_aliments`
    --

ALTER TABLE
    `entree_aliments` ADD CONSTRAINT `fk_entree_Aliments_stock_Aliments1` FOREIGN KEY(`stock_Aliments_idStock`) REFERENCES `stock_aliments`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_entreesAliments_entrees1` FOREIGN KEY(`entrees_idEntree`) REFERENCES `entrees`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `entree_biogaz`
    --

ALTER TABLE
    `entree_biogaz` ADD CONSTRAINT `fk_entree_Biogaz_stock_Biogaz1` FOREIGN KEY(`stock_Biogaz_idStock`) REFERENCES `stock_biogaz`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_entreesBiogaz_entrees1` FOREIGN KEY(`entrees_idEntree`) REFERENCES `entrees`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `entree_oeufs`
    --

ALTER TABLE
    `entree_oeufs` ADD CONSTRAINT `fk_entree_Oeufs_entrees1` FOREIGN KEY(`entrees_idEntree`) REFERENCES `entrees`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_entree_Oeufs_stock_Oeufs1` FOREIGN KEY(`stock_Oeufs_idStock`) REFERENCES `stock_oeufs`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `entree_poussins`
    --

ALTER TABLE
    `entree_poussins` ADD CONSTRAINT `fk_entree_PoussinsOeufs_stock_Poussins1` FOREIGN KEY(`stock_Poussins_idStock`) REFERENCES `stock_poussins`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_entreesPoussinsOeufs_entrees1` FOREIGN KEY(`entrees_idEntree`) REFERENCES `entrees`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `fournisseurs`
    --

ALTER TABLE
    `fournisseurs` ADD CONSTRAINT `fk_Fornisseurs_personnes1` FOREIGN KEY(`personnes_idPersonne`) REFERENCES `personnes`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_Fournisseurs_services1` FOREIGN KEY(`services_id`) REFERENCES `services`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `incubations`
    --

ALTER TABLE
    `incubations` ADD CONSTRAINT `fk_incubations_agents1` FOREIGN KEY(`agents_idAgent`) REFERENCES `agents`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_incubations_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `personnes`
    --

ALTER TABLE
    `personnes` ADD CONSTRAINT `fk_personnes_adresses1` FOREIGN KEY(`adresses_idAdresse`) REFERENCES `adresses`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `rapport_aliments`
    --

ALTER TABLE
    `rapport_aliments` ADD CONSTRAINT `fk_rapportAliments_agents1` FOREIGN KEY(`agents_idAgent`) REFERENCES `agents`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_rapportAliments_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_rapportAliments_status_rapport1` FOREIGN KEY(`status_rapport_id`) REFERENCES `status_rapport`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `rapport_biogaz`
    --

ALTER TABLE
    `rapport_biogaz` ADD CONSTRAINT `fk_rapportBiogaz_agents1` FOREIGN KEY(`agents_idAgent`) REFERENCES `agents`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_rapport_Biogaz_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_rapport_Biogaz_status_rapport1` FOREIGN KEY(`status_rapport_id`) REFERENCES `status_rapport`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `rapport_oeufs`
    --

ALTER TABLE
    `rapport_oeufs` ADD CONSTRAINT `fk_rapport_Oeufs_agents1` FOREIGN KEY(`agents_idAgent`) REFERENCES `agents`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_rapport_Oeufs_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_rapport_Oeufs_status_rapport1` FOREIGN KEY(`status_rapport_id`) REFERENCES `status_rapport`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `rapport_poussins`
    --

ALTER TABLE
    `rapport_poussins` ADD CONSTRAINT `fk_rapportPoussinsOeufs_agents1` FOREIGN KEY(`agents_idAgent`) REFERENCES `agents`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_rapport_Poussins_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_rapport_Poussins_status_rapport1` FOREIGN KEY(`status_rapport_id`) REFERENCES `status_rapport`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `reservation_cf`
    --

ALTER TABLE
    `reservation_cf` ADD CONSTRAINT `fk_reservationCf_clients1` FOREIGN KEY(`clients_idClient`) REFERENCES `clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `reservation_pouletabattage`
    --

ALTER TABLE
    `reservation_pouletabattage` ADD CONSTRAINT `fk_entreePoulets_clients1` FOREIGN KEY(`clients_idClient`) REFERENCES `clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `sorties`
    --

ALTER TABLE
    `sorties` ADD CONSTRAINT `fk_sorties_agents1` FOREIGN KEY(`agents_id`) REFERENCES `agents`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_sorties_motifSorties1` FOREIGN KEY(`motifSorties_idMotif`) REFERENCES `motif_sorties`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_sorties_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `sortie_aliments`
    --

ALTER TABLE
    `sortie_aliments` ADD CONSTRAINT `fk_sortieAliments_sorties1` FOREIGN KEY(`sorties_idSortie`) REFERENCES `sorties`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_sortie_aliments_clients1` FOREIGN KEY(`clients_id`) REFERENCES `clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `sortie_biogaz`
    --

ALTER TABLE
    `sortie_biogaz` ADD CONSTRAINT `fk_sortieBiogaz_sorties1` FOREIGN KEY(`sorties_idSortie`) REFERENCES `sorties`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_sortie_biogaz_clients1` FOREIGN KEY(`clients_id`) REFERENCES `clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `sortie_oeufs`
    --

ALTER TABLE
    `sortie_oeufs` ADD CONSTRAINT `fk_sortieOeufs_sorties1` FOREIGN KEY(`sorties_idSortie`) REFERENCES `sorties`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_sortie_oeufs_clients1` FOREIGN KEY(`clients_id`) REFERENCES `clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `sortie_poussins`
    --

ALTER TABLE
    `sortie_poussins` ADD CONSTRAINT `fk_sortiePoussinsOeufs_sorties1` FOREIGN KEY(`sorties_idSortie`) REFERENCES `sorties`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_sortie_poussins_clients1` FOREIGN KEY(`clients_id`) REFERENCES `clients`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `stock_aliments`
    --

ALTER TABLE
    `stock_aliments` ADD CONSTRAINT `fk_stock_Aliments_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `stock_biogaz`
    --

ALTER TABLE
    `stock_biogaz` ADD CONSTRAINT `fk_stock_Biogaz_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `stock_oeufs`
    --

ALTER TABLE
    `stock_oeufs` ADD CONSTRAINT `fk_stock_Oeufs_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `stock_poussins`
    --

ALTER TABLE
    `stock_poussins` ADD CONSTRAINT `fk_stock_Poussins_natures1` FOREIGN KEY(`natures_idNature`) REFERENCES `natures`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    --

    -- Contraintes pour la table `vente_pouletabattage`
    --

ALTER TABLE
    `vente_pouletabattage` ADD CONSTRAINT `fk_vente_PouletAbattage_reservation_PouletAbattage1` FOREIGN KEY(
        `reservation_PouletAbattage_idEntree`
    ) REFERENCES `reservation_pouletabattage`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
