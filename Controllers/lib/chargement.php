<?php
#Admin
function chargementAdmin($adminData)
{
    chargementPersonne($adminData);
    if (empty(trim($adminData['email']))) {
        return error422("Veuillez completer votre email");
    } elseif (empty(trim($adminData['telephone']))) {
        return error422("Veuillez completer votre Numero de Telephone");
    } elseif (empty(trim($adminData['password']))) {
        return error422("Veuillez completer votre mot de passe");
    } elseif (empty(trim($adminData['services_id']))) {
        return error422("Veuillez completer votre Service");
    }
}
function chargementEntree($entreesData)
{
    if (empty(trim($entreesData['natures_idNature']))) {
        return error422("Veuillez renseigner la nature de l'operation");
    } elseif (empty(trim($entreesData['fournisseur_id']))) {
        return error422("Veuillez renseigner le fournisseur du produit");
    }
}
function chargementSorties($sortiesData)
{

    if (empty(trim($sortiesData['natures_idNature']))) {
        return error422("Veuillez renseigner la nature de l'operation");
    } elseif (empty(trim($sortiesData['agents_id']))) {
        return error422("Veuillez renseigner l'agent");
    }
}
function chargementSortieAliment($sortieAlimentData)
{
    chargementSorties($sortieAlimentData);
    if (empty(trim($sortieAlimentData['quantite']))) {
        return error422("Veuillez completer la quantite");
    } elseif (empty(trim($sortieAlimentData['clients_id']))) {
        return error422("Veuillez renseigner le client");
    } elseif (empty(trim($sortieAlimentData['natures_idNature']))) {
        return error422("Veuillez renseigner la Nature du Produit");
    } elseif (empty(trim($sortieAlimentData['agents_id']))) {
        return error422("Veuillez renseigner l'agent");
    }
}
function chargementStockAliments($stockAlimentData)
{

    if (empty(trim($stockAlimentData['designation_lot']))) {
        return error422("Veuillez completer Designation du Lot");
    } elseif (empty(trim($stockAlimentData['quantite']))) {
        return error422("Veuillez renseigner la quantite ");
    } elseif (empty(trim($stockAlimentData['natures_idNature']))) {
        return error422("Veuillez renseigner la nature du lot");
    }
}
function chargementEntreePoules($entreeAlimentData)
{
    chargementEntree($entreeAlimentData);
    chargementStockAliments($entreeAlimentData);
}
function chargementEntreeAliments($entreeAlimentData)
{
    chargementEntree($entreeAlimentData);
    chargementStockAliments($entreeAlimentData);
}
function chargementRapport($rapportData)
{

    if (empty(trim($rapportData['quantite']))) {
        return error422("Veuillez renseigner la quantite ");
    } elseif (empty(trim($rapportData['etat_rapportID']))) {
        return error422("Veuillez renseigner l'etat");
    } elseif (empty(trim($rapportData['commentaire']))) {
        return error422("Veuillezle commentaire");
    } elseif (empty(trim($rapportData['agents_idAgent']))) {
        return error422("Veuillez renseigner l'agent");
    } elseif (empty(trim($rapportData['natures_idNature']))) {
        return error422("Veuillez renseigner la nature du produit");
    }
}
function chargementEntreeBiogaz($entreeBiogazData)
{
    chargementEntree($entreeBiogazData);
    chargementStockAliments($entreeBiogazData);
}
function chargementEntreeOeufs($entreeOeufData)
{
    chargementEntree($entreeOeufData);
    chargementStockAliments($entreeOeufData);
}
function chargementEntreePoussins($entreePoussinsData)
{
    chargementEntree($entreePoussinsData);
    chargementStockAliments($entreePoussinsData);
}
function chargementCommandeClient($commandeClientData)
{
    if (empty(trim($commandeClientData['status']))) {
        return error422("Veuillez renseigner le status de la commande ");
    } elseif (empty(trim($commandeClientData['date']))) {
        return error422("Veuillez renseigner la date de la commande");
    } elseif (empty(trim($commandeClientData['natures_idNature']))) {
        return error422("Veuillez renseigner la nature du produit");
    } elseif (empty(trim($commandeClientData['clients_idClient']))) {
        return error422("Veuillez completer le Client");
    }
}
function chargementCommande($commandeData)
{
    if (empty(trim($commandeData['quantite']))) {
        return error422("Veuillez renseigner la quantite de la commande ");
    } elseif (empty(trim($commandeData['natures_idNature']))) {
        return error422("Veuillez renseigner la nature du produit");
    } elseif (empty(trim($commandeData['clients_idClient']))) {
        return error422("Veuillez renseigner l'ID du Client");
    }
}
function chargementRequetes($requetesData)
{
    if (empty(trim($requetesData['question']))) {
        return error422("Veuillez renseigner votre question ");
    } elseif (empty(trim($requetesData['destinateur']))) {
        return error422("Veuillez renseigner le destinateur");
    } elseif (empty(trim($requetesData['expediteur']))) {
        return error422("Veuillez renseigner l'expediteur'");
    }
}
function chargementIncubation($incubationsData)
{
    if (empty(trim($incubationsData['dateEntree']))) {
        return error422("Veuillez renseigner la date d'entree en incubation ");
    } elseif (empty(trim($incubationsData['quantite']))) {
        return error422("Veuillez renseigner la quantite d'oeuf dans l'incubateur");
    } elseif (empty(trim($incubationsData['agents_idAgent']))) {
        return error422("Veuillez renseigner l'agent ");
    } elseif (empty(trim($incubationsData['natures_idNature']))) {
        return error422("Veuillez renseigner la nature");
    }
}
function chargementCommandeFournisseur($commandeFournisseurData)
{
    if (empty(trim($commandeFournisseurData['status']))) {
        return error422("Veuillez renseigner le status de la commande ");
    } elseif (empty(trim($commandeFournisseurData['quantite']))) {
        return error422("Veuillez renseigner la quantite de la commande");
    } elseif (empty(trim($commandeFournisseurData['dateDebut']))) {
        return error422("Veuillez renseigner la date de la commande ");
    } elseif (empty(trim($commandeFournisseurData['dateFin']))) {
        return error422("Veuillez renseigner la date de la livraison de la commande");
    } elseif (empty(trim($commandeFournisseurData['natures_idNature']))) {
        return error422("Veuillez renseigner la nature du produit");
    } elseif (empty(trim($commandeFournisseurData['Fournisseurs_idFournisseur']))) {
        return error422("Veuillez completer le Fournisseur");
    }
}
function chargementReservationCF($reservationCFData)
{

    if (empty(trim($reservationCFData['dateEntree']))) {
        return error422("Veuillez renseigner la date Entree ");
    } elseif (empty(trim($reservationCFData['dateFin']))) {
        return error422("Veuillez renseigner la date Fin");
    } elseif (empty(trim($reservationCFData['dateSortie']))) {
        return error422("Veuillez renseigner la date de sortie");
    } elseif (empty(trim($reservationCFData['libelle']))) {
        return error422("Veuillez completer le libelle");
    } elseif (empty(trim($reservationCFData['clients_idClient']))) {
        return error422("Veuillez renseigner le client");
    }
}
function chargementReservationPoulet($reservationCFData)
{

    if (empty(trim($reservationCFData['quantite']))) {
        return error422("Veuillez renseigner la quantite ");
    } elseif (empty(trim($reservationCFData['clients_idClient']))) {
        return error422("Veuillez renseigner le client");
    }
}

function chargementAgent($agentData)
{
    chargementPersonne($agentData);
    if (empty(trim($agentData['email']))) {
        return error422("Veuillez completer votre email");
    } elseif (empty(trim($agentData['telephone']))) {
        return error422("Veuillez completer votre Numero de Telephone");
    } elseif (empty(trim($agentData['password']))) {
        return error422("Veuillez completer votre mot de passe");
    }
}
function chargementClient($clientData)
{
    chargementPersonne($clientData);
    if (empty(trim($clientData['telephone']))) {
        return error422("Veuillez completer votre Numero de Telephone");
    } elseif (empty(trim($clientData['password']))) {
        return error422("Veuillez completer votre mot de passe");
    }
}
function chargementClientMoral($clientData)
{
    chargementPersonneMorale($clientData);
    if (empty(trim($clientData['telephone']))) {
        return error422("Veuillez completer votre Numero de Telephone");
    } elseif (empty(trim($clientData['password']))) {
        return error422("Veuillez completer votre mot de passe");
    } elseif (empty(trim($clientData['email']))) {
        return error422("Veuillez completer votre adresse mail");
    }
}
function chargementFournisseurMoral($clientData)
{
    chargementPersonneMoraleFour($clientData);
    if (empty(trim($clientData['telephone']))) {
        return error422("Veuillez completer votre Numero de Telephone");
    } elseif (empty(trim($clientData['email']))) {
        return error422("Veuillez completer votre adresse mail");
    }
}
function chargementFournisseur($fournisseurData)
{
    chargementPersonne($fournisseurData);
    if (empty(trim($fournisseurData['email']))) {
        return error422("Veuillez completer votre email");
    } elseif (empty(trim($fournisseurData['telephone']))) {
        return error422("Veuillez completer votre Numero de Telephone");
    } elseif (empty(trim($fournisseurData['cat_produit_id']))) {
        return error422("Veuillez completer la categorie du produit ");
    }
}


function chargementPersonne($personneData)
{
    chargementAdresse($personneData);
    if (empty(trim($personneData['nom']))) {
        return error422("Veuillez completer votre Nom");
    } elseif (empty(trim($personneData['postnom']))) {
        return error422("Veuillez completer votre Postnom");
    } elseif (empty(trim($personneData['sexe']))) {
        return error422("Veuillez renseigner votre sexe");
    }
}
function chargementPersonneMorale($personneData)
{
    chargementAdresse($personneData);
    if (empty(trim($personneData['nom']))) {
        return error422("Veuillez completer votre Nom");
    } elseif (empty(trim($personneData['titre']))) {
        return error422("Veuillez completer votre titre");
    } elseif (empty(trim($personneData['sexe']))) {
        return error422("Veuillez renseigner votre sexe");
    } elseif (empty(trim($personneData['nom_entreprise']))) {
        return error422("Veuillez completer le nom de l'entreprise");
    } elseif (empty(trim($personneData['annee_existence']))) {
        return error422("Veuillez renseigner votre annee d'existence");
    }
}

function chargementAdresse($adresseData)
{
    if (empty(trim($adresseData['ville']))) {
        return error422("Veuillez completer votre Ville");
    } elseif (empty(trim($adresseData['commune']))) {
        return error422("Veuillez completer votre Commune");
    } elseif (empty(trim($adresseData['quartier']))) {
        return error422("Veuillez completer votre Quartier");
    } elseif (empty(trim($adresseData['avenue']))) {
        return error422("Veuillez completer votre Avenue");
    }
}

function chargementNature($natureData)
{
    if (empty(trim($natureData['designation']))) {
        return error422("Veuillez completer votre Designation");
    } elseif (empty(trim($natureData['type']))) {
        return error422("Veuillez completer le Type");
    } elseif (empty(trim($natureData['mode']))) {
        return error422("Veuillez completer le Mode de vente");
    } elseif (empty(trim($natureData['prixunitaire']))) {
        return error422("Veuillez completer le Prix Unitaire");
    } elseif (empty(trim($natureData['devise']))) {
        return error422("Veuillez completer la devise");
    } elseif (empty(trim($natureData['cat_produit_id']))) {
        return error422("Veuillez completer la categorie produit");
    }
}
function chargementService($serviceData)
{
    if (empty(trim($serviceData['designation']))) {
        return error422("Veuillez completer votre Designation");
    } elseif (empty(trim($serviceData['abrege']))) {
        return error422("Veuillez completer l'abreviation");
    }
}
function chargementNotification($notificationData)
{
    if (empty(trim($notificationData['titre']))) {
        return error422("Veuillez completer le titre");
    } elseif (empty(trim($notificationData['description']))) {
        return error422("Veuillez completer la description");
    }
}

function chargementAdresseFour($adresseData)
{
    if (empty(trim($adresseData['ville']))) {
        return error422("Veuillez completer votre Ville");
    } elseif (empty(trim($adresseData['pays']))) {
        return error422("Veuillez completer votre Pays");
    }
}

function chargementPersonneMoraleFour($personneData)
{
    chargementAdresseFour($personneData);
    if (empty(trim($personneData['nom']))) {
        return error422("Veuillez completer votre Nom");
    } elseif (empty(trim($personneData['titre']))) {
        return error422("Veuillez completer votre titre");
    } elseif (empty(trim($personneData['sexe']))) {
        return error422("Veuillez renseigner votre sexe");
    } elseif (empty(trim($personneData['nom_entreprise']))) {
        return error422("Veuillez completer le nom de l'entreprise");
    } elseif (empty(trim($personneData['annee_existence']))) {
        return error422("Veuillez renseigner votre annee d'existence");
    }
}