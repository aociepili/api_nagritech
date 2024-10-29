<?php

namespace App\Core;

const SECRET = '@n93361945ehula@Tech!';
# 21 Jours comme Delai d'incubation pour oeuf de Poule 
define("DELAI_INCUBATION_JR", 21);
define("DEFAULT_PWD", "1234");

# Multiplicateur 
# 60*60*24 nombre de seconde dans une journee
define("JOUR", 86400);
define("INCUBATION", 86400 * DELAI_INCUBATION_JR);
define("SEMAINE", 86400 * 7);
define("MOIS", 86400 * 30);

# Cryptage de mot de passe
define('COST', ['cost' => 10]);

# Validite Token Une heure = 3600 sec
# Validite Token 8 heures = 28800 sec
// define("TOKEN_VALIDITE", 3600);
define("TOKEN_VALIDITE", 28800);
define("PATH_IMG_DEFAUT", "..\public\img\fournisseur\Logo8176.jpg");


#Gestion de Status_Rapport
define("STATUS_RAPPORT_ETABLI", 1);
define("STATUS_RAPPORT_REVISE", 2);
define("STATUS_RAPPORT_ANNULE", 3);
define("STATUS_RAPPORT_VALIDE", 4);
define("STATUS_MODIFIABLE", array(STATUS_RAPPORT_ETABLI, STATUS_RAPPORT_REVISE));

#Gestion de Etat_rapport
define("ETAT_TRES_BON", 1);
define("ETAT_BON", 2);
define("ETAT_MAUVAIS", 3);
define("ETAT_TRES_MAUVAIS", 4);
define("ETAT_MORT", 5);

define("GOOD_PRODUCT", array(ETAT_TRES_BON, ETAT_BON));
define("BAD_PRODUCT", array(ETAT_MAUVAIS, ETAT_TRES_MAUVAIS, ETAT_MORT));


#GESTION DE STATUS_COMMANDE
#Commande
#La status commande par defaut est Encours avec l'ID 1
define("STATUS_CMD_DEFAUT", 1);
define("STATUS_CMD_ETABLI", 1);
define("STATUS_CMD_E_PAIEMENT", 2);
define("STATUS_CMD_RESERVE", 3);
define("STATUS_CMD_REGLE", 4);
define("STATUS_CMD_ANNULE", 5);
define("STATUS_CMD_E_DETTE", 6);
define("STATUS_CMD", array(STATUS_CMD_ETABLI, STATUS_CMD_E_PAIEMENT, STATUS_CMD_REGLE, STATUS_CMD_RESERVE, STATUS_CMD_ANNULE, STATUS_CMD_E_DETTE));
define("STATUS_CMD_IRREVERSIBLE", array(STATUS_CMD_REGLE, STATUS_CMD_ANNULE));
define("STATUS_CMD_NON_LIVRABLE", array(STATUS_CMD_ETABLI, STATUS_CMD_E_PAIEMENT, STATUS_CMD_RESERVE, STATUS_CMD_ANNULE));
define("STATUS_CMD_PAYABLE", array(STATUS_CMD_ETABLI, STATUS_CMD_E_PAIEMENT, STATUS_CMD_RESERVE,STATUS_CMD_E_DETTE));
define("STATUS_CMD_NO_STOCK_IMPACT", array( STATUS_CMD_E_DETTE));


#GESTION DE STATUS_INCUBATION
define("STATUS_INC_ENCOURS", 1);
define("STATUS_INC_BIENTOT", 2);
define("STATUS_INC_TERMINE", 3);
define("STATUS_INC_SORTIE", 4);
define("STATUS_INC_UPDATED", array(STATUS_INC_ENCOURS, STATUS_INC_BIENTOT, STATUS_INC_TERMINE));


#GESTION CATEGORIE PRODUIT
define('CAT_PRO_ALIMENT', 1);
define('CAT_PRO_BIOGAZ', 2);
define('CAT_PRO_OEUF', 3);
define('CAT_PRO_POUSSIN', 4);
define('CAT_PRO_POULE', 5);
define('CAT_PRO_POULET', 6);

#GESTION NATURE ALIMENT
define('DESIGN_ALIMENT', 'Aliment');

#GESTION NATURE BIOGAZ
define('DESIGN_BIOGAZ', 'Biogaz');

#GESTION NATURE OEUF
define('DESIGN_OEUF', 'Oeuf');

#GESTION NATURE POUSSIN
define('DESIGN_POUSSIN', 'Poussin');

#GESTION NATURE POULE
define('DESIGN_POULE', 'Poule');

#GESTION NATURE POULET
define('DESIGN_POULET', 'Poulet');

#GESTION MOTIF
define('MOTIF_SORTIE_CASH', 1);
define('MOTIF_SORTIE_CREDIT', 2);
define('MOTIF_ENTREE_CASH', 3);
define('MOTIF_ENTREE_CREDIT', 4);
define('MOTIF_SORTIE_ETAT_MAUVAIS', 5);
define('MOTIF_SORTIE_ETAT_TMAUVAIS', 6);
define('MOTIF_SORTIE_ETAT_MORT', 7);
define('MOTIF_SORTIE_INCUBATEUR', 8);
define('MOTIF_ENTREE_INCUBATEUR', 9);

#GESTION SYSTEME
define('ID_CLIENT_SYSTEME', 0);
define('ID_FOURNISSEUR_SYSTEME', 2);
define('ID_AGENT_SYSTEME', 2);
define('ID_ADMIN_SYSTEME', value: 2);


#GESTION SERVICE
define('FOUR_SERVICE', 4);
define('CLIENT_SERVICE', 3);
define('AGENT_SERVICE', 4);
define('ADMIN_SERVICE', 1);
define('ADMIN_ADMIN', 1);
define('ADMIN_LOG', 2);
define('ADMIN_FINANCIER', 3);
define('ADMIN_AGENT', 4);

#GESTION CATEGORIE ADMIN
define('CAT_ADMIN_GLOBAL', 1);

#GESTION FOURNISSEUR

#GESTION ROLE USER
define('IS_ADMIN', 'isAdmin');
define('IS_ADMIN_ID', 1);
define('IS_AGENT', 'isAgent');
define('IS_AGENT_ID', 2);
define('IS_CLIENT', 'isClient');
define('IS_CLIENT_ID', 3);
define("ADMIN_USER", array(IS_ADMIN));
define("AGENT_USER", array(IS_ADMIN, IS_AGENT));
define("CLIENT_USER", array(IS_ADMIN, IS_CLIENT));

#GESTION DE TABLE
define('TABLE_ADMIN', 1);
define('TABLE_AGENT', 2);
define('TABLE_CLIENT', 3);
define('TABLE_CAT_PROD', 4);
define('TABLE_CMD_ALIMENT', 5);
define('TABLE_CMD_BIOGAZ', 6);
define('TABLE_CMD_CLIENT', 7);
define('TABLE_CMD_FOUR', 8);
define('TABLE_CMD_OEUF', 9);
define('TABLE_CMD_POUSSIN', 10);
define('TABLE_CAT_ADMIN', 11);
define('TABLE_CMD_POULE', 12);
define('TABLE_CMD_POULET', 13);
define('TABLE_ENT_ALIMENT', 14);
define('TABLE_ENT_BIOGAZ', 15);
define('TABLE_ENT_OEUF', 16);
define('TABLE_ENT_POUSSIN', 17);
define('TABLE_ENT_POULE', 18);
define('TABLE_ENT_POULET', 19);
define('TABLE_ENTREE', 20);
define('TABLE_ETAT_RAPP', 21);
define('TABLE_FOURNISSEUR', 22);
define('TABLE_INCUB', 23);
define('TABLE_ACTIVITY', 24);
define('TABLE_MOTIF', 25);
define('TABLE_NATURE', 26);
define('TABLE_NOTIFICATION', 27);
define('TABLE_RAP_ALIMENT', 28);
define('TABLE_RAP_BIOGAZ', 29);
define('TABLE_RAP_OEUF', 30);
define('TABLE_RAP_POUSSIN', 31);
define('TABLE_RAP_POULE', 32);
define('TABLE_RAP_POULET', 33);
define('TABLE_RAPPORT', 34);
define('TABLE_REQUETE', 35);
define('TABLE_ROLE_USER', 36);
define('TABLE_SERVICE', 37);
define('TABLE_OUT_ALIMENT', 38);
define('TABLE_OUT_BIOGAZ', 39);
define('TABLE_OUT_OEUF', 40);
define('TABLE_OUT_POUSSIN', 41);
define('TABLE_OUT_POULE', 42);
define('TABLE_OUT_POULET', 43);
define('TABLE_SORTIE', 44);
define('TABLE_STATUS_CMD', 45);
define('TABLE_STATUS_INC', 46);
define('TABLE_STATUS_OPER', 47);
define('TABLE_STATUS_RAPP', 48);
define('TABLE_STOCK_ALIMENT', 49);
define('TABLE_STOCK_BIOGAZ', 50);
define('TABLE_STOCK_OEUF', 51);
define('TABLE_STOCK_POUSSIN', 52);
define('TABLE_STOCK_POULE', 53);
define('TABLE_STOCK_POULET', 54);
define('TABLE_OPERATION', 55);
define('TABLE_TRANCHE_AGE', 56);
define('TABLE_TYPE_OP', 57);


#GESTION STATUS OPERATION
define('STATUS_OP_OK', 1);
define('STATUS_OP_NOT', 2);

#GESTION TYPE OPERATION
define('TYPE_OP_CREATE', 1);
define('TYPE_OP_READ', 2);
define('TYPE_OP_READBY', 3);
define('TYPE_OP_UPDATE', 4);
define('TYPE_OP_DELETE', 5);
define('TYPE_OP_ARCHIVE', 6);
define('TYPE_OP_LOGIN', 7);
define('TYPE_OP_LOGOUT', 8);
define('TYPE_OP_UPDATE_STATUS', 9);
define('TYPE_OP_RESET_PWD', 10);
define('TYPE_OP_CHANGE_PWD', 11);
define('TYPE_OP_UP_PAIEMENT', 12);
define('TYPE_OP_UP_IMAGE', 13);
define('TYPE_OP_OUT_INCUB', 14);