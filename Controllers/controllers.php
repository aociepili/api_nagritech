<?php
include_once '../core/Data.php';

use App\Models\AdressesModel;
use App\Models\PersonnesModel;
use App\Models\AdminsModel;
use App\Models\AgentsModel;
use App\Models\ClientsModel;
use App\Models\FournisseursModel;
use App\Models\EntreesModel;
use App\Models\NaturesModel;
use App\Models\Motif_sortiesModel;
use App\Models\Stock_alimentsModel;
use App\Models\Stock_biogazModel;
use App\Models\Stock_oeufsModel;
use App\Models\Stock_poussinsModel;
use App\Models\Entree_alimentsModel;
use App\Models\Entree_biogazModel;
use App\Models\Entree_oeufsModel;
use App\Models\Entree_poussinsModel;
use App\Models\Reservation_CFModel;
use App\Models\Reservation_pouletabattageModel;
use App\Models\Status_rapportsModel;
use App\Models\Status_commandesModel;
use App\Models\Rapport_alimentsModel;
use App\Models\Rapport_biogazModel;
use App\Models\Rapport_oeufsModel;
use App\Models\Rapport_poussinsModel;
use App\Models\Commande_clientsModel;
use App\Models\Commande_fournisseursModel;
use App\Models\Commande_alimentsModel;
use App\Models\Commande_biogazModel;
use App\Models\Commande_oeufsModel;
use App\Models\IncubationsModel;
use App\Models\SortiesModel;
use App\Models\Sortie_alimentsModel;
use App\Models\Sortie_biogazModel;
use App\Models\Sortie_oeufsModel;
use App\Models\Sortie_poussinsModel;
use App\Models\ServicesModel;
use App\Models\Categorie_adminsModel;
use App\Models\Etat_rapportsModel;
use App\Models\Commande_poussinsModel;
use App\Models\Status_incubationsModel;
use App\Models\Tranche_agesModel;
use App\Models\Categorie_produitModel;
use App\Models\Role_usersModel;
use App\Models\Journal_activitesModel;
use App\Models\Commande_poulesModel;
use App\Models\Commande_pouletsModel;
use App\Models\Stock_poulesModel;
use App\Models\Stock_pouletsModel;
use App\Models\Sortie_poulesModel;
use App\Models\Sortie_pouletsModel;
use App\Models\Entree_poulesModel;
use App\Models\Entree_pouletsModel;
use App\Models\Rapport_poulesModel;
use App\Models\Rapport_pouletsModel;
use App\Models\Type_operationsModel;
use App\Models\Status_operationsModel;
use App\Models\Table_operationsModel;
use App\Models\Rapport_incubationsModel;
use App\Models\Sortie_incubationsModel;


# Utilitaire
include('lib/utilitaire.php');

# Message d'erreur
include('lib/message.php');


# Test de validite
include('lib/validite.php');

#Test de chargement
include('lib/chargement.php');


#Modification
include("lib/modification.php");

# Test Existence 
function testEntreebyId($entreeID)
{
    $entreeModel = new EntreesModel();
    if ($entreeID !== null) {
        $data = $entreeModel->find($entreeID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "cette Entree renseignée n'existe pas";
            return success205($message);
        }
    } else {
        $message = "Veuillez renseignée l'Entree ";
        return success205($message);
    }
}
function testCommandeClientbyId($cmdClientID)
{
    $cmdClientModel = new Commande_clientsModel();
    if ($cmdClientID !== null) {
        $data = $cmdClientModel->find($cmdClientID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "cette Commande Client renseignée n'existe pas";
            return success205($message);
        }
    } else {
        $message = "Veuillez renseignée la Commande Client ";
        return success205($message);
    }
}
function  getPrixTotal($cmdClientID, $quantite)
{
    $cmdClientModel = new Commande_clientsModel();
    $natureModel = new NaturesModel();

    if ($cmdClientID !== null) {
        $data = $cmdClientModel->find($cmdClientID);
        if (!empty($data)) {
            $natureID = $data->natures_idNature;
            $natureData = $natureModel->find($natureID);
            $prixUnitaire = $natureData->prixunitaire;
            $prixTotal = (float)$quantite * (float)$prixUnitaire;


            return reduireChiffre($prixTotal);
        } else {
            $message = "cette Commande Client renseignée n'existe pas";
            return success205($message);
        }
    } else {
        $message = "Veuillez renseignée la Commande Client ";
        return success205($message);
    }
}
function testStatusCmdbyId($statusID)
{
    $statusCmdModel = new Status_commandesModel();
    if ($statusID !== null) {
        $data = $statusCmdModel->find($statusID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "ce Status Commande renseignée n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Status Commande ";
        return success205($message);
    }
}
function testTrancheAgebyId($statusID)
{
    $trancheAgeModel = new Tranche_agesModel();
    if ($statusID !== null) {
        $data = $trancheAgeModel->find($statusID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "cette tranche d'age renseignée n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée la tranche d'age ";
        return success205($message);
    }
}

function testCatAdminbyId($catAdminID)
{
    $catAdminModel = new Categorie_adminsModel();
    if ($catAdminID !== null) {
        $data = $catAdminModel->find($catAdminID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "cette Categorie Admin renseignée n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez  renseignée la Categorie Admin ";
        return success205($message);
    }
}

function testServicebyId($serviceID)
{
    $serviceModel = new ServicesModel();
    if ($serviceID !== null) {
        $data = $serviceModel->find($serviceID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "ce Service renseignée n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le service ";
        return success205($message);
    }
}
function testNaturebyId($natureID)
{
    $natureeModel = new NaturesModel();
    if ($natureID !== null) {
        $data = $natureeModel->find($natureID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "cette Nature renseignée n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée la nature ";
        return success205($message);
    }
}
function testFournisseurbyId($fournisseurID)
{
    $fournisseurModel = new FournisseursModel();
    if ($fournisseurID !== null) {
        $data = $fournisseurModel->find($fournisseurID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "ce Fournisseur renseignée n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Fournisseur ";
        return success205($message);
    }
}
function testMotifbyId($motifID)
{
    $motifModel = new Motif_sortiesModel();
    if ($motifID !== null) {
        $data = $motifModel->find($motifID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "ce Motif renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le motif ";
        return success205($message);
    }
}
function testStockAlimentbyId($stockAlimentID)
{
    $stockAlimentModel = new Stock_alimentsModel();
    if ($stockAlimentID !== null) {
        $data = $stockAlimentModel->find($stockAlimentID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "Stock Aliment renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Stock Aliment ";
        return success205($message);
    }
}

function testStockBiogazbyId($stockBGID)
{
    $stockBiogazModel = new Stock_biogazModel();
    if ($stockBGID !== null) {
        $data = $stockBiogazModel->find($stockBGID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "Stock Biogaz renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Stock Biogaz ";
        return success205($message);
    }
}
function testStockOeufbyId($stockOeufID)
{
    $stockoeufsModel = new Stock_oeufsModel();
    if ($stockOeufID !== null) {
        $data = $stockoeufsModel->find($stockOeufID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "Stock Oeuf renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Stock Oeuf ";
        return success205($message);
    }
}
function testStockPoussinbyId($stockPoussinID)
{
    $stockPoussinModel = new Stock_poussinsModel();
    if ($stockPoussinID !== null) {
        $data = $stockPoussinModel->find($stockPoussinID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "Stock Poussin renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Stock Poussin ";
        return success205($message);
    }
}
function testClientbyId($clientID)
{
    $clientModel = new ClientsModel();
    if ($clientID !== null) {
        $data = $clientModel->find($clientID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "Client renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Client ";
        return success205($message);
    }
}
function testSortiebyId($sortieID)
{
    $sortieModel = new SortiesModel();
    if ($sortieID !== null) {
        $data = $sortieModel->find($sortieID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "Sortie renseignée n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée la Sortie ";
        return success205($message);
    }
}
function testStatutRapportbyId($statutRapportID)
{
    $statutRapportModel = new Status_rapportsModel();
    if ($statutRapportID !== null) {
        $data = $statutRapportModel->find($statutRapportID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "Statut du Rapport renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Statut du Rapport ";
        return success205($message);
    }
}
function testIncubationbyId($statutRapportID)
{
    $incubationModel = new IncubationsModel();
    if ($statutRapportID !== null) {
        $data = $incubationModel->find($statutRapportID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "le produit renseigné n'existe pas dans l'incubateur ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée l'ID du produit dans l'incubateur ";
        return success205($message);
    }
}

function testEtatRapportbyId($etatRapportID)
{
    $etatRapportModel = new Etat_rapportsModel();
    if ($etatRapportID !== null) {
        $data = $etatRapportModel->find($etatRapportID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "Etat du Rapport renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Etat du Rapport ";
        return success205($message);
    }
}
function testCatProduitbyId($etatRapportID)
{
    $catProduitModel = new Categorie_produitModel();
    if ($etatRapportID !== null) {
        $data = $catProduitModel->find($etatRapportID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "Categorie de Produit renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée la Categorie de Produit ";
        return success205($message);
    }
}
function isGoodProduct($etatRapportID)
{

    if ($etatRapportID !== null) {

        if ($etatRapportID == ETAT_TRES_BON || $etatRapportID == ETAT_BON) {
            $test = true;
            return $test;
        } else {
            $message = "Etat du Produit n'est pas bon ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée le Etat du Rapport ";
        return success205($message);
    }
}
function testAgentbyId($agentID)
{
    $agentModel = new AgentsModel();
    if ($agentID !== null) {
        $data = $agentModel->find($agentID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "agent renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée l'agent ";
        return success205($message);
    }
}
function testAdminbyId($adminID)
{
    $adminModel = new AdminsModel();
    if ($adminID !== null) {
        $data = $adminModel->find($adminID);
        if (!empty($data)) {
            $test = true;
            return $test;
        } else {
            $message = "admin renseigné n'existe pas ";
            return success205($message);
        }
    } {
        $message = "Veuillez renseignée l'admin ";
        return success205($message);
    }
}



#Creation des objets
#adresse
function createAdresse($adresseData)
{
    $adresseModel = new AdressesModel();
    $adresse = $adresseModel;
    #Processus de Creation Adresse

    #Recuperation Adresse
    $pays = $adresseData["pays"];
    $ville = $adresseData["ville"];
    $commune = $adresseData["commune"];
    $quartier = $adresseData["quartier"];
    $avenue = $adresseData["avenue"];

    #Chargement de l'adresse
    $adresse->setPays($pays);
    $adresse->setVille($ville);
    $adresse->setCommune($commune);
    $adresse->setQuartier($quartier);
    $adresse->setAvenue($avenue);
    $adresseModel->create($adresse);
}

#Personne
function createPersonne($personneData, $idAdresse)
{
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();
    $adresse = $adresseModel;
    $personne = $personneModel;
    #Personne
    $nom = $personneData["nom"];
    $postnom = $personneData["postnom"];
    $prenom = $personneData["prenom"];
    $sexe = $personneData["sexe"];
    $PersonIdadresse = $idAdresse;

    $personne->setNom($nom);
    $personne->setPostnom($postnom);
    $personne->setPrenom($prenom);
    $personne->setSexe($sexe);
    $personne->setAdresses_idAdresse($PersonIdadresse);
    $personneModel->create($personne);
}
function createPersonneMorale($personneData, $idAdresse)
{
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();
    $adresse = $adresseModel;
    $personne = $personneModel;
    #Personne
    $nom = $personneData["nom"];
    $titre = $personneData["titre"];
    $nom_entreprise = $personneData["nom_entreprise"];
    $annee_existence = $personneData["annee_existence"];
    $postnom = "no define";
    $prenom = "no define";
    $sexe = $personneData["sexe"];
    $PersonIdadresse = $idAdresse;

    $personne->setNom($nom);
    $personne->setTitre($titre);
    $personne->setNom_entreprise($nom_entreprise);
    $personne->setAnnee_existence($annee_existence);
    $personne->setPostnom($postnom);
    $personne->setPrenom($prenom);
    $personne->setSexe($sexe);
    $personne->setAdresses_idAdresse($PersonIdadresse);
    $personneModel->create($personne);
}

#Commande Client
function createCommandeClient($commandeClientsData)
{

    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;

    # Le status par defaut est Encours
    // $status = STATUS_CMD_DEFAUT;
    $status = $commandeClientsData["statusCmd_id"];
    $date = $commandeClientsData["date"];
    $natureID = $commandeClientsData["natures_idNature"];
    $clientID = $commandeClientsData["clients_idClient"];
    $today = getSiku();

    $commandeClients->setStatusCmd_id($status);
    $commandeClients->setIs_delivered(false);
    $commandeClients->setDate($date);
    $commandeClients->setNatures_idNature($natureID);
    $commandeClients->setClients_idClient($clientID);
    $commandeClients->setCreated_at($today);

    $commandeClientsModel->create($commandeClients);
}

function createEntree($entreesData)
{

    $entreesModel = new EntreesModel();
    $entrees = $entreesModel;

    $date = $entreesData["date"];
    $natureID = $entreesData['natures_idNature'];
    $motifID = $entreesData['motifSorties_idMotif'];
    $today = getSiku();

    $entrees->setDate($date);
    $entrees->setNatures_idNature($natureID);
    $entrees->setMotifSorties_idMotif($motifID);
    $entrees->setCreated_at($today);

    # On ajoute la Designation dans la BD
    $entreesModel->create($entrees);
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ENTREE);
}
function updateEntree($entreesData, $entreeID)
{

    $entreesModel = new EntreesModel();
    $entrees = $entreesModel;

    $date = $entreesData["date"];
    $natureID = $entreesData['natures_idNature'];
    $motifID = $entreesData['motifSorties_idMotif'];
    $today = getSiku();
    $Dataentree = $entreesModel->find($entreeID);
    if ($Dataentree->id == $entreeID) {
        $entrees->setDate($date);
        $entrees->setNatures_idNature($natureID);
        $entrees->setMotifSorties_idMotif($motifID);
        $entrees->setUpdated_at($today);

        # On ajoute la Designation dans la BD
        $entreesModel->update($entreeID, $entrees);
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ENTREE);
    }
}
function createStockPoulet($StockPouletData)
{
    $StockPouletModel = new Stock_pouletsModel();
    $StockPoulets = $StockPouletModel;

    $designation = $StockPouletData['designation_lot'];
    $quantite = $StockPouletData['quantite'];
    $date = $StockPouletData["date"];
    $etat = $StockPouletData["etat"];
    $natureID = $StockPouletData['natures_idNature'];
    $fournisseurID = $StockPouletData['fournisseur_id'];
    $today = getSiku();

    $StockPoulets->setDesignation_lot($designation);
    $StockPoulets->setQuantite($quantite);
    $StockPoulets->setDate($date);
    $StockPoulets->setEtat($etat);
    $StockPoulets->setNatures_idNature($natureID);
    $StockPoulets->setFournisseur_id($fournisseurID);
    $StockPoulets->setCreated_at($today);

    # On ajoute la Designation dans la BD
    $StockPouletModel->create($StockPoulets);
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_POULET);
}
function createStockPoule($StockPouleData)
{
    $StockPouleModel = new Stock_poulesModel();
    $StockPoules = $StockPouleModel;

    $designation = $StockPouleData['designation_lot'];
    $quantite = $StockPouleData['quantite'];
    $date = $StockPouleData["date"];
    $etat = $StockPouleData["etat"];
    $natureID = $StockPouleData['natures_idNature'];
    $fournisseurID = $StockPouleData['fournisseur_id'];
    $today = getSiku();

    $StockPoules->setDesignation_lot($designation);
    $StockPoules->setQuantite($quantite);
    $StockPoules->setDate($date);
    $StockPoules->setEtat($etat);
    $StockPoules->setNatures_idNature($natureID);
    $StockPoules->setFournisseur_id($fournisseurID);
    $StockPoules->setCreated_at($today);

    # On ajoute la Designation dans la BD
    $StockPouleModel->create($StockPoules);
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_POULE);
}
function createStockAliment($StockAlimentsData)
{
    $StockAlimentsModel = new Stock_alimentsModel();
    $StockAliments = $StockAlimentsModel;

    $designation = $StockAlimentsData['designation_lot'];
    $quantite = $StockAlimentsData['quantite'];
    $date = $StockAlimentsData["date"];
    $etat = $StockAlimentsData["etat"];
    $natureID = $StockAlimentsData['natures_idNature'];
    $fournisseurID = $StockAlimentsData['fournisseur_id'];
    $today = getSiku();

    $StockAliments->setDesignation_lot($designation);
    $StockAliments->setQuantite($quantite);
    $StockAliments->setDate($date);
    $StockAliments->setEtat($etat);
    $StockAliments->setNatures_idNature($natureID);
    $StockAliments->setFournisseur_id($fournisseurID);
    $StockAliments->setCreated_at($today);

    # On ajoute la Designation dans la BD
    $StockAlimentsModel->create($StockAliments);
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_ALIMENT);
}
function updateStockAliment($StockAlimentsData, $stockAlimentID)
{
    $StockAlimentsModel = new Stock_alimentsModel();
    $StockAliments = $StockAlimentsModel;

    $designation = $StockAlimentsData['designation_lot'];
    $quantite = $StockAlimentsData['quantite'];
    $date = $StockAlimentsData["date"];
    $etat = $StockAlimentsData["etat"];
    $natureID = $StockAlimentsData['natures_idNature'];
    $today = getSiku();

    $dataStockAliment = $StockAlimentsModel->find($stockAlimentID);

    if ($dataStockAliment->id == $stockAlimentID) {
        $StockAliments->setDesignation_lot($designation);
        $StockAliments->setQuantite($quantite);
        $StockAliments->setDate($date);
        $StockAliments->setEtat($etat);
        $StockAliments->setNatures_idNature($natureID);
        $StockAliments->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $StockAlimentsModel->update($stockAlimentID, $StockAliments);
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_ALIMENT);
    }
}
function updateStockPoulet($StockPouletsData, $stockPouletID)
{
    $StockPouletsModel = new Stock_pouletsModel();
    $StockPoulets = $StockPouletsModel;

    $designation = $StockPouletsData['designation_lot'];
    $quantite = $StockPouletsData['quantite'];
    $date = $StockPouletsData["date"];
    $etat = $StockPouletsData["etat"];
    $natureID = $StockPouletsData['natures_idNature'];
    $today = getSiku();

    $dataStockAliment = $StockPouletsModel->find($stockPouletID);

    if ($dataStockAliment->id == $stockPouletID) {
        $StockPoulets->setDesignation_lot($designation);
        $StockPoulets->setQuantite($quantite);
        $StockPoulets->setDate($date);
        $StockPoulets->setEtat($etat);
        $StockPoulets->setNatures_idNature($natureID);
        $StockPoulets->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $StockPouletsModel->update($stockPouletID, $StockPoulets);
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_POULET);
    }
}
function updateStockPoule($StockPoulesData, $stockPouleID)
{
    $StockPoulesModel = new Stock_poulesModel();
    $StockPoules = $StockPoulesModel;

    $designation = $StockPoulesData['designation_lot'];
    $quantite = $StockPoulesData['quantite'];
    $date = $StockPoulesData["date"];
    $etat = $StockPoulesData["etat"];
    $natureID = $StockPoulesData['natures_idNature'];
    $today = getSiku();

    $dataStockAliment = $StockPoulesModel->find($stockPouleID);

    if ($dataStockAliment->id == $stockPouleID) {
        $StockPoules->setDesignation_lot($designation);
        $StockPoules->setQuantite($quantite);
        $StockPoules->setDate($date);
        $StockPoules->setEtat($etat);
        $StockPoules->setNatures_idNature($natureID);
        $StockPoules->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $StockPoulesModel->update($stockPouleID, $StockPoules);
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_POULE);
    }
}
function updateStockBiogaz($StockBiogazData, $stockBiogazID)
{
    $StockBiogazModel = new Stock_biogazModel();
    $StockBiogaz = $StockBiogazModel;

    $designation = $StockBiogazData['designation_lot'];
    $quantite = $StockBiogazData['quantite'];
    $date = $StockBiogazData["date"];
    $etat = $StockBiogazData["etat"];
    $natureID = $StockBiogazData['natures_idNature'];
    $today = getSiku();

    $dataStockAliment = $StockBiogazModel->find($stockBiogazID);

    if ($dataStockAliment->id == $stockBiogazID) {
        $StockBiogaz->setDesignation_lot($designation);
        $StockBiogaz->setQuantite($quantite);
        $StockBiogaz->setDate($date);
        $StockBiogaz->setEtat($etat);
        $StockBiogaz->setNatures_idNature($natureID);
        $StockBiogaz->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $StockBiogazModel->update($stockBiogazID, $StockBiogaz);
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_BIOGAZ);
    }
}
function updateStockOeuf($StockOeufData, $stockOeufID)
{
    $StockOeufModel = new Stock_oeufsModel();
    $StockOeuf = $StockOeufModel;

    $designation = $StockOeufData['designation_lot'];
    $quantite = $StockOeufData['quantite'];
    $date = $StockOeufData["date"];
    $etat = $StockOeufData["etat"];
    $natureID = $StockOeufData['natures_idNature'];
    $today = getSiku();

    $dataStockOeuf = $StockOeufModel->find($stockOeufID);

    if ($dataStockOeuf->id == $stockOeufID) {
        $StockOeuf->setDesignation_lot($designation);
        $StockOeuf->setQuantite($quantite);
        $StockOeuf->setDate($date);
        $StockOeuf->setEtat($etat);
        $StockOeuf->setNatures_idNature($natureID);
        $StockOeuf->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $StockOeufModel->update($stockOeufID, $StockOeuf);
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_OEUF);
    }
}
function updateStockPoussin($StockPoussinData, $stockPoussinID)
{
    $StockPoussinModel = new Stock_poussinsModel();
    $StockPoussin = $StockPoussinModel;

    $designation = $StockPoussinData['designation_lot'];
    $quantite = $StockPoussinData['quantite'];
    $date = $StockPoussinData["date"];
    $etat = $StockPoussinData["etat"];
    $natureID = $StockPoussinData['natures_idNature'];
    $today = getSiku();

    $dataStockPoussin = $StockPoussinModel->find($stockPoussinID);

    if ($dataStockPoussin->id == $stockPoussinID) {
        $StockPoussin->setDesignation_lot($designation);
        $StockPoussin->setQuantite($quantite);
        $StockPoussin->setDate($date);
        $StockPoussin->setEtat($etat);
        $StockPoussin->setNatures_idNature($natureID);
        $StockPoussin->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $StockPoussinModel->update($stockPoussinID, $StockPoussin);
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_POUSSIN);
    }
}


function createStockBiogaz($StockBiogazData)
{
    $stockBiogazModel = new Stock_biogazModel();
    $stockBiogaz = $stockBiogazModel;

    $designation = $StockBiogazData['designation_lot'];
    $quantite = $StockBiogazData['quantite'];
    $date = $StockBiogazData["date"];
    $etat = $StockBiogazData["etat"];
    $natureID = $StockBiogazData['natures_idNature'];
    $fournisseurID = $StockBiogazData['fournisseur_id'];
    $today = getSiku();

    $stockBiogaz->setDesignation_lot($designation);
    $stockBiogaz->setQuantite($quantite);
    $stockBiogaz->setDate($date);
    $stockBiogaz->setEtat($etat);
    $stockBiogaz->setNatures_idNature($natureID);
    $stockBiogaz->setFournisseur_id($fournisseurID);
    $stockBiogaz->setCreated_at($today);

    # On ajoute la Designation dans la BD
    $stockBiogazModel->create($stockBiogaz);
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_BIOGAZ);
}
function createStockOeuf($StockoeufData)
{
    $stockOeufsModel = new Stock_oeufsModel();
    $stockOeufs = $stockOeufsModel;

    $designation = $StockoeufData['designation_lot'];
    $quantite = $StockoeufData['quantite'];
    $date = $StockoeufData["date"];
    $etat = $StockoeufData["etat"];
    $natureID = $StockoeufData['natures_idNature'];
    $fournisseurID = $StockoeufData['fournisseur_id'];
    $today = getSiku();

    $stockOeufs->setDesignation_lot($designation);
    $stockOeufs->setQuantite($quantite);
    $stockOeufs->setDate($date);
    $stockOeufs->setEtat($etat);
    $stockOeufs->setNatures_idNature($natureID);
    $stockOeufs->setFournisseur_id($fournisseurID);
    $stockOeufs->setCreated_at($today);

    # On ajoute la Designation dans la BD
    $stockOeufsModel->create($stockOeufs);
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_OEUF);
}
function createStockPoussin($StockPoussinData)
{
    $stockPoussinsModel = new Stock_poussinsModel();
    $stockPoussins = $stockPoussinsModel;

    $designation = $StockPoussinData['designation_lot'];
    $quantite = $StockPoussinData['quantite'];
    $date = $StockPoussinData["date"];
    $etat = $StockPoussinData["etat"];
    $natureID = $StockPoussinData['natures_idNature'];
    $fournisseurID = $StockPoussinData['fournisseur_id'];
    $today = getSiku();

    $stockPoussins->setDesignation_lot($designation);
    $stockPoussins->setQuantite($quantite);
    $stockPoussins->setDate($date);
    $stockPoussins->setEtat($etat);
    $stockPoussins->setNatures_idNature($natureID);
    $stockPoussins->setFournisseur_id($fournisseurID);
    $stockPoussins->setCreated_at($today);

    # On ajoute la Designation dans la BD
    $stockPoussinsModel->create($stockPoussins);
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_POUSSIN);
}
function createSortie($sortiesData)
{
    $sortiesModel = new SortiesModel();
    $sorties = $sortiesModel;

    $date = $sortiesData["date"];
    $natureID = $sortiesData['natures_idNature'];
    $motifID = $sortiesData['motifSorties_idMotif'];
    $adminID = $sortiesData['admins_id'];
    $agentID = $sortiesData['agents_id'];
    $roleID = $sortiesData['role_id'];
    $today = getSiku();

    if ($sortiesData['role'] == IS_ADMIN) {
        $sorties->setAdmins_id($adminID);
        $sorties->setRole_id($roleID);
    } else if ($sortiesData['role'] == IS_AGENT) {
        $sorties->setRole_id($roleID);
    }

    $sorties->setDate($date);
    $sorties->setNatures_idNature($natureID);
    $sorties->setMotifSorties_idMotif($motifID);
    $sorties->setAgents_id($agentID);
    $sorties->setCreated_at($today);

    # On ajoute la Designation dans la BD
    $sortiesModel->create($sorties);
}

# Recuperation derniere Enregistrement
function getLastSortie($sortiesData)
{
    $sortiesModel = new SortiesModel();

    $date = $sortiesData['date'];
    $motifID = $sortiesData['motifSorties_idMotif'];
    $agentID = $sortiesData["agents_id"];
    $natureID = $sortiesData['natures_idNature'];

    $dataSortie = array(
        "date" => $date,
        "natures_idNature" => $natureID,
        "motifSorties_idMotif" => $motifID,
        "agents_id" => $agentID
    );
    $dataS = (object)$sortiesModel->findBy($dataSortie);
    $lastData = end($dataS);

    return $lastData;
}
function getLastStockPoussin($StockPoussinData)
{
    $stockPoussinsModel = new Stock_poussinsModel();

    $designation = $StockPoussinData['designation_lot'];
    $quantite = $StockPoussinData['quantite'];
    $date = $StockPoussinData["date"];
    $etat = $StockPoussinData["etat"];
    $natureID = $StockPoussinData['natures_idNature'];

    $dataStockPoussin = array(
        "date" => $date,
        "natures_idNature" => $natureID,
        "designation_lot" => $designation,
        "etat" => $etat,
        "quantite" => $quantite
    );
    $dataSP = (object)$stockPoussinsModel->findBy($dataStockPoussin);
    $lastData = end($dataSP);

    return $lastData;
}
function getLastStockPoulet($StockPouletsData)
{
    $StockPouletsModel = new Stock_pouletsModel();

    $designation = $StockPouletsData['designation_lot'];
    $quantite = $StockPouletsData['quantite'];
    $date = $StockPouletsData["date"];
    $etat = $StockPouletsData["etat"];
    $natureID = $StockPouletsData['natures_idNature'];

    $dataStockAliment = array(
        "date" => $date,
        "natures_idNature" => $natureID,
        "designation_lot" => $designation,
        "etat" => $etat,
        "quantite" => $quantite
    );
    $dataSA = (object)$StockPouletsModel->findBy($dataStockAliment);
    $lastData = end($dataSA);

    return $lastData;
}
function getLastStockPoule($StockPoulesData)
{
    $StockPoulesModel = new Stock_poulesModel();

    $designation = $StockPoulesData['designation_lot'];
    $quantite = $StockPoulesData['quantite'];
    $date = $StockPoulesData["date"];
    $etat = $StockPoulesData["etat"];
    $natureID = $StockPoulesData['natures_idNature'];

    $dataStockAliment = array(
        "date" => $date,
        "natures_idNature" => $natureID,
        "designation_lot" => $designation,
        "etat" => $etat,
        "quantite" => $quantite
    );
    $dataSA = (object)$StockPoulesModel->findBy($dataStockAliment);
    $lastData = end($dataSA);

    return $lastData;
}
/**
 * Summary of getLastStockAliment
 * @param mixed $StockAlimentsData
 * @return mixed
 */
function getLastStockAliment($StockAlimentsData)
{
    $StockAlimentsModel = new Stock_alimentsModel();

    $designation = $StockAlimentsData['designation_lot'];
    $quantite = $StockAlimentsData['quantite'];
    $date = $StockAlimentsData["date"];
    $etat = $StockAlimentsData["etat"];
    $natureID = $StockAlimentsData['natures_idNature'];

    $dataStockAliment = array(
        "date" => $date,
        "natures_idNature" => $natureID,
        "designation_lot" => $designation,
        "etat" => $etat,
        "quantite" => $quantite
    );
    $dataSA = (object)$StockAlimentsModel->findBy($dataStockAliment);
    $lastData = end($dataSA);

    return $lastData;
}
function getLastStockOeuf($StockoeufData)
{
    $stockOeufsModel = new Stock_oeufsModel();

    $designation = $StockoeufData['designation_lot'];
    $quantite = $StockoeufData['quantite'];
    $date = $StockoeufData["date"];
    $etat = $StockoeufData["etat"];
    $natureID = $StockoeufData['natures_idNature'];

    $dataStockOeuf = array(
        "date" => $date,
        "natures_idNature" => $natureID,
        "designation_lot" => $designation,
        "etat" => $etat,
        "quantite" => $quantite
    );
    $dataSO = (object)$stockOeufsModel->findBy($dataStockOeuf);
    $lastData = end($dataSO);

    return $lastData;
}
function getLastStockBiogaz($StockBiogazData)
{
    $stockBiogazModel = new Stock_biogazModel();

    $designation = $StockBiogazData['designation_lot'];
    $quantite = $StockBiogazData['quantite'];
    $date = $StockBiogazData["date"];
    $etat = $StockBiogazData["etat"];
    $natureID = $StockBiogazData['natures_idNature'];

    $dataStockBiogaz = array(
        "date" => $date,
        "natures_idNature" => $natureID,
        "designation_lot" => $designation,
        "etat" => $etat,
        "quantite" => $quantite
    );
    $dataSBZ = (object)$stockBiogazModel->findBy($dataStockBiogaz);
    $lastData = end($dataSBZ);

    return $lastData;
}
function getLastEntree($entreesData)
{
    $entreesModel = new EntreesModel();

    $date = $entreesData["date"];
    $natureID = $entreesData['natures_idNature'];
    $motifID = $entreesData['motifSorties_idMotif'];


    $dataEntree = array(
        "date" => $date,
        "natures_idNature" => $natureID,
        "motifSorties_idMotif" => $motifID
    );
    $dataE = (object)$entreesModel->findBy($dataEntree);
    $lastData = end($dataE);

    return $lastData;
}
function getLastCommandeClient($commandeClientsData)
{
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    #Processus de Creation Adresse



    #Recuperation Adresse
    $statutID = $commandeClientsData["statusCmd_id"];
    $date = $commandeClientsData["date"];
    $natureID = $commandeClientsData["natures_idNature"];
    $clientID = $commandeClientsData["clients_idClient"];



    $dataCmdClient = array(
        "statusCmd_id" => $statutID,
        "date" => $date,
        "natures_idNature" => $natureID,
        "clients_idClient" => $clientID
    );
    $dataC = (object)$commandeClientsModel->findBy($dataCmdClient);
    $lastData = end($dataC);

    return $lastData;
}
function getLastAdresse($adresseData)
{
    $adresseModel = new AdressesModel();
    $adresse = $adresseModel;
    #Processus de Creation Adresse

    #Recuperation Adresse
    // $pays = $adresseData["pays"];
    $ville = $adresseData["ville"];
    $commune = $adresseData["commune"];
    $quartier = $adresseData["quartier"];
    $avenue = $adresseData["avenue"];

    $Dataadresse = array(
        // "pays" => $pays,
        "ville" => $ville,
        "commune" => $commune,
        "quartier" => $quartier,
        "avenue" => $avenue,
    );
    $dataA = (object)$adresseModel->findBy($Dataadresse);
    $lastData = end($dataA);

    return $lastData;
}

function getLastPersonne($personneData, $idAdresse)
{
    $nom = $personneData["nom"];
    $postnom = $personneData["postnom"];
    $prenom = $personneData["prenom"];
    $sexe = $personneData["sexe"];
    $PersonIdadresse = $idAdresse;

    $Datapersonne = array(
        "nom" => $nom,
        "postnom" => $postnom,
        "prenom" => $prenom,
        "sexe" => $sexe,
        "adresses_idAdresse" => $PersonIdadresse,
    );
    $personneModel = new PersonnesModel();
    $dataP = (object)$personneModel->findBy($Datapersonne);
    $lastDataPersonne = end($dataP);

    return $lastDataPersonne;
}
function getLastPersonneMorale($personneData, $idAdresse)
{
    $nom = $personneData["nom"];
    $titre = $personneData["titre"];
    $sexe = $personneData["sexe"];
    $nom_entreprise = $personneData["nom_entreprise"];
    $annee_existence = $personneData["annee_existence"];
    $PersonIdadresse = $idAdresse;

    $Datapersonne = array(
        "nom" => $nom,
        "nom_entreprise" => $nom_entreprise,
        "titre" => $titre,
        "sexe" => $sexe,
        "annee_existence" => $annee_existence,
        "adresses_idAdresse" => $PersonIdadresse,
    );
    $personneModel = new PersonnesModel();
    $dataP = (object)$personneModel->findBy($Datapersonne);
    $lastDataPersonne = end($dataP);

    return $lastDataPersonne;
}
function getRoleId($designation)
{
    $roleData = array(
        "designation" => $designation,
    );

    $roleUserModel = new Role_usersModel();
    $dataRoles = (object)$roleUserModel->findBy($roleData);
    $dataRole = end($dataRoles);
    $roleID = $dataRole->id;

    return $roleID;
}


#SUPPRESSION
function deleteCmdClientData($cmdClientID)
{
    $test = false;
    $commandeClientsModel = new Commande_clientsModel();

    #Verification de l'ID 
    $cmdClientData = $commandeClientsModel->find($cmdClientID);

    if ($cmdClientID == $cmdClientData->id) {
        #Suppression de l'ID personne et son Adresse
        $commandeClientsModel->delete($cmdClientID);
        $test = true;
    }
    return $test;
}
function deletePersonneData($personneID)
{
    $test = false;
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();

    #Verification de l'ID de la personne
    $personneData = $personneModel->find($personneID);
    $idAdresse = $personneData->adresses_idAdresse;

    if ($personneID == $personneData->id) {
        #Suppression de l'ID personne et son Adresse
        $personneModel->delete($personneID);
        $adresseModel->delete($idAdresse);
        $test = true;
    }
    return $test;
}
function getstatusCommandeById($statusIncID)
{
    $statusIncubationModel = new Status_commandesModel();
    $dataStatusInc = $statusIncubationModel->find($statusIncID);
    $dataSC = changeAttribut((array)$dataStatusInc, "status_cmd");
    return $dataSC;
}
function getstatusIncById($statusIncID)
{
    $statusIncubationModel = new Status_incubationsModel();
    $dataStatusInc = $statusIncubationModel->find($statusIncID);
    $dataSI = changeAttribut((array)$dataStatusInc, "status_inc");
    return $dataSI;
}
function getStatusRapportById($statusRapportID)
{
    $statusRapportModel = new Status_rapportsModel();
    $dataStatusRapport = $statusRapportModel->find($statusRapportID);
    $dataSR = changeAttribut((array)$dataStatusRapport, "status_rapport");
    return $dataSR;
}
function getNatureById($natureID)
{
    $natureModel = new NaturesModel();
    $dataNature = $natureModel->find($natureID);
    $dataN = changeAttribut((array)$dataNature, "nature");
    return $dataN;
}
function getCategorieAdminById($catAdminID)
{
    $catAdminModel = new Categorie_adminsModel();
    $dataCA = $catAdminModel->find($catAdminID);
    $dataCatAdmin = changeAttribut((array)$dataCA, "categorie_admin");
    return $dataCatAdmin;
}
function getDataMotifById($motifID)
{
    $motifModel = new Motif_sortiesModel();
    $dataMotif = $motifModel->find($motifID);
    $dataM = changeAttribut((array)$dataMotif, "motif");
    return $dataM;
}

function getDesignationTypeServiceById($serviceID)
{
    $servicesModel = new ServicesModel();
    $dataTypeService = $servicesModel->find($serviceID);
    // $designation = $dataTypeService->designation;
    $code = $dataTypeService->abrege;
    return $code;
}
function getDesignationRoleById($roleID)
{
    $roleUsersModel = new Role_usersModel();
    $dataRole = $roleUsersModel->find($roleID);
    $designation = $dataRole->designation;
    return $designation;
}
function getDesignationTypeOpById($typeOpID)
{
    $typeOperationsModel = new Type_operationsModel();
    $dataTypeOp = $typeOperationsModel->find($typeOpID);
    $designation = $dataTypeOp->designation;
    return $designation;
}
function getDesignationStatusOpById($StatusOpID)
{
    $typeOperationsModel = new Status_operationsModel();
    $dataStatusOp = $typeOperationsModel->find($StatusOpID);
    $designation = $dataStatusOp->designation;
    return $designation;
}
function getDesignationTableOpById($StatusOpID)
{
    $tableOperationsModel = new Table_operationsModel();
    $dataStatusOp = $tableOperationsModel->find($StatusOpID);
    $designation = $dataStatusOp->designation;
    return $designation;
}
function getNamePersonneById($personneID)
{
    $personnesModel = new PersonnesModel();
    $dataPersonne = $personnesModel->find($personneID);
    $name = $dataPersonne->nom . "-" . $dataPersonne->postnom . "-" . $dataPersonne->prenom;
    return $name;
}

function getNameUserId($datActivite)
{
    $userID = $datActivite->user_id;
    $roleID = $datActivite->role_id;
    $data = array();

    if (IS_ADMIN == getDesignationRoleById($roleID)) {
        $adminsModel = new AdminsModel();
        $dataAdmin = $adminsModel->find($userID);
        $data['name'] = getNamePersonneById($dataAdmin->personnes_idPersonne);
        $data['role'] = IS_ADMIN;
    } elseif (IS_AGENT == getDesignationRoleById($roleID)) {
        $agentsModel = new AgentsModel();
        $dataAgent = $agentsModel->find($userID);
        $data['name'] = getNamePersonneById($dataAgent->personnes_idPersonne);
        $data['role'] = IS_AGENT;
    } elseif (IS_CLIENT == getDesignationRoleById($roleID)) {
        $clientsModel = new ClientsModel();
        $dataClient = $clientsModel->find($userID);
        $data['name'] = getNamePersonneById($dataClient->personnes_idPersonne);
        $data['role'] = IS_CLIENT;
    } else {
        $data['name'] = "No defini";
        $data['role'] = "No defini";
    }
    return $data;
}


function getJournalActivityDataById($activityID)
{
    $journalActivityModel = new Journal_activitesModel();
    $datActivite = $journalActivityModel->find($activityID);

    $dataA = changeAttribut((array)$datActivite, "activity");
    $userData = getNameUserId($datActivite);
    $typeOp = getDesignationTypeOpById($datActivite->type_op_id);
    $statusOp = getDesignationStatusOpById($datActivite->status_op_id);
    $tableOp = getDesignationTableOpById($datActivite->table_id);
    $otherData = array(
        "type_op" =>  $typeOp,
        "status_op" =>  $statusOp,
        "table_op" =>  $tableOp
    );
    $data = array_merge((array)$userData, (array)$otherData, (array)$dataA);
    return $data;
}
function getAdminDataById($adminID)
{
    $adminModel = new AdminsModel();
    $dataAdmin = $adminModel->find($adminID);
    $dataA = changeAttribut((array)$dataAdmin, "admin");

    $personneID = $dataAdmin->personnes_idPersonne;
    $dataPersonne = getPersonneDataById($personneID);
    $dataService = getServiceDataById($dataAdmin->services_id);

    $data = array_merge((array)$dataPersonne, (array)$dataService, (array)$dataA);
    return $data;
}
function getEntreeDataById($entreeID)
{
    $entreesModel = new EntreesModel();
    $data = array();

    $dataEntree = $entreesModel->find($entreeID);
    $dataE = changeAttribut((array)$dataEntree, "entree");

    $natureID = $dataEntree->natures_idNature;
    $motifID = $dataEntree->motifSorties_idMotif;

    $dataNature = getNatureById($natureID);
    $dataMotif =  getDataMotifById($motifID);
    $data = array_merge((array)$dataMotif, (array)$dataNature, (array)$dataE);

    return $data;
}
function getEntreePouletDataById($entreeAlimentID)
{

    $entreePouletModel = new Entree_pouletsModel();

    $dataEntreePoulet = $entreePouletModel->find($entreeAlimentID);
    $dataEPt = changeAttribut((array)$dataEntreePoulet, "entree_Poulet");
    $entreeID = $dataEntreePoulet->entrees_idEntree;
    $stockPouletID = $dataEntreePoulet->stock_Poulets_idStock;

    $dataEntree =  getEntreeDataById($entreeID);
    $dataStockPoulet = getStockPouleDataById($stockPouletID);

    $data = array_merge((array)$dataStockPoulet, (array)$dataEntree, (array)$dataEPt);
    return $data;
}
function getEntreePouleDataById($entreeAlimentID)
{

    $entreePouleModel = new Entree_poulesModel();

    $dataEntreePoule = $entreePouleModel->find($entreeAlimentID);
    $dataEP = changeAttribut((array)$dataEntreePoule, "entree_Poule");
    $entreeID = $dataEntreePoule->entrees_idEntree;
    $stockPouleID = $dataEntreePoule->stock_Poules_idStock;

    $dataEntree =  getEntreeDataById($entreeID);
    $dataStockPoule = getStockPouleDataById($stockPouleID);

    $data = array_merge((array)$dataStockPoule, (array)$dataEntree, (array)$dataEP);
    return $data;
}
function getEntreeAlimentDataById($entreeAlimentID)
{

    $entreeAlimentModel = new Entree_alimentsModel();

    $dataEntreeAliment = $entreeAlimentModel->find($entreeAlimentID);
    $dataEA = changeAttribut((array)$dataEntreeAliment, "entree_aliment");
    $entreeID = $dataEntreeAliment->entrees_idEntree;
    $stockAlimentID = $dataEntreeAliment->stock_Aliments_idStock;

    $dataEntree =  getEntreeDataById($entreeID);
    $dataStockAliment = getStockAlimentDataById($stockAlimentID);

    $data = array_merge((array)$dataStockAliment, (array)$dataEntree, (array)$dataEA);
    return $data;
}
function getEntreeBiogazDataById($entreeBiogazID)
{
    $entreeBiogazModel = new Entree_biogazModel();

    $dataEntreeBiogaz = $entreeBiogazModel->find($entreeBiogazID);
    $dataEB = changeAttribut((array)$dataEntreeBiogaz, "entree_biogaz");
    $entreeID = $dataEntreeBiogaz->entrees_idEntree;
    $stockBiogazID = $dataEntreeBiogaz->stock_Biogaz_idStock;

    $dataEntree =  getEntreeDataById($entreeID);
    $dataStockBiogaz = getStockBiogazDataById($stockBiogazID);

    $data = array_merge((array)$dataStockBiogaz, (array)$dataEntree, (array)$dataEB);
    return $data;
}
function getEntreeOeufDataById($entreeOeufID)
{
    $entreeoeufsModel = new Entree_oeufsModel();
    $dataEntreeOeuf = $entreeoeufsModel->find($entreeOeufID);
    $dataEO = changeAttribut((array)$dataEntreeOeuf, "entree_oeuf");
    $entreeID = $dataEntreeOeuf->entrees_idEntree;
    $stockOeufID = $dataEntreeOeuf->stock_Oeufs_idStock;

    $dataEntree =  getEntreeDataById($entreeID);
    $dataStockOeuf = getStockOeufDataById($stockOeufID);

    $data = array_merge((array)$dataStockOeuf, (array)$dataEntree, (array)$dataEO);
    return $data;
}
function getEntreePoussinDataById($entreePoussinID)
{

    $entreePoussinModel = new Entree_poussinsModel();

    $dataEntreePoussin = $entreePoussinModel->find($entreePoussinID);
    $dataEP = changeAttribut((array)$dataEntreePoussin, "entree_poussin");
    $entreeID = $dataEntreePoussin->entrees_idEntree;
    $stockPoussinID = $dataEntreePoussin->stock_Poussins_idStock;

    $dataEntree =  getEntreeDataById($entreeID);
    $dataStockPoussin = getStockPoussinDataById($stockPoussinID);

    $data = array_merge((array)$dataStockPoussin, (array)$dataEntree, (array)$dataEP);
    return $data;
}
function getReservationCFDataById($reservationCFID)
{
    $reservationCFModel = new Reservation_CFModel();
    $dataReservationCF = $reservationCFModel->find($reservationCFID);
    $dataRCF = changeAttribut((array)$dataReservationCF, "reservation_CF");
    $clientID = $dataReservationCF->clients_idClient;

    $dataClient =  getClientDataById($clientID);
    $data = array_merge((array)$dataClient, (array)$dataRCF);
    return $data;
}
function getReservationPDataById($reservationPID)
{
    $reservationPModel = new Reservation_pouletabattageModel();
    $dataReservationP = $reservationPModel->find($reservationPID);
    $dataRP = changeAttribut((array)$dataReservationP, "reservation_p");
    $clientID = $dataReservationP->clients_idClient;

    $dataClient =  getClientDataById($clientID);
    $data = array_merge((array)$dataClient, (array)$dataRP);
    return $data;
}
function getRapportPouletDataById($rapportAlimentID)
{
    $rapportPouletModel = new Rapport_pouletsModel();

    $dataRapportPoulet = $rapportPouletModel->find($rapportAlimentID);
    $dataRPt = changeAttributRapport((array)$dataRapportPoulet, "rapport_poulet");

    $agentID = $dataRapportPoulet->agents_idAgent;
    $natureID = $dataRapportPoulet->natures_idNature;
    $statutID = $dataRapportPoulet->status_rapport_id;

    $dataAgent =  getAgentDataById($agentID);
    $dataNature = getNatureById($natureID);
    $dataStatutR = getStatusRapportById($statutID);

    $data = array_merge((array)$dataAgent, (array)$dataNature, (array)$dataStatutR, (array)$dataRPt);
    return $data;
}
function getRapportPouleDataById($rapportAlimentID)
{
    $rapportPouleModel = new Rapport_poulesModel();

    $dataRapportPoule = $rapportPouleModel->find($rapportAlimentID);
    $dataRP = changeAttributRapport((array)$dataRapportPoule, "rapport_poule");

    $agentID = $dataRapportPoule->agents_idAgent;
    $natureID = $dataRapportPoule->natures_idNature;
    $statutID = $dataRapportPoule->status_rapport_id;

    $dataAgent =  getAgentDataById($agentID);
    $dataNature = getNatureById($natureID);
    $dataStatutR = getStatusRapportById($statutID);

    $data = array_merge((array)$dataAgent, (array)$dataNature, (array)$dataStatutR, (array)$dataRP);
    return $data;
}
function getRapportAlimentDataById($rapportAlimentID)
{
    $rapportAlimentModel = new Rapport_alimentsModel();

    $dataRapportAliment = $rapportAlimentModel->find($rapportAlimentID);
    // $dataRA = changeAttribut((array)$dataRapportAliment, "rapport_aliment");
    $dataRA = changeAttributRapport((array)$dataRapportAliment, "rapport_aliment");


    $agentID = $dataRapportAliment->agents_idAgent;
    $natureID = $dataRapportAliment->natures_idNature;
    $statutID = $dataRapportAliment->status_rapport_id;

    $dataAgent =  getAgentDataById($agentID);
    $dataNature = getNatureById($natureID);
    $dataStatutR = getStatusRapportById($statutID);

    $data = array_merge((array)$dataAgent, (array)$dataNature, (array)$dataStatutR, (array)$dataRA);
    return $data;
}
function getRapportBiogazDataById($rapportAlimentID)
{
    $rapportBiogazModel = new Rapport_biogazModel();

    $dataRapportBiogaz = $rapportBiogazModel->find($rapportAlimentID);
    $dataRB = changeAttributRapport((array)$dataRapportBiogaz, "rapport_biogaz");
    $agentID = $dataRapportBiogaz->agents_idAgent;
    $natureID = $dataRapportBiogaz->natures_idNature;
    $statutID = $dataRapportBiogaz->status_rapport_id;


    $dataAgent =  getAgentDataById($agentID);
    $dataNature = getNatureById($natureID);
    $dataStatutR = getStatusRapportById($statutID);
    $data = array_merge((array)$dataAgent, (array)$dataNature, (array)$dataStatutR, (array)$dataRB);
    return $data;
}
function getRapportIncDataById($rapportAlimentID)
{
    $rapportIncubationsModel = new Rapport_incubationsModel();

    $dataRapportOeuf = $rapportIncubationsModel->find($rapportAlimentID);
    $dataRO = changeAttributRapport((array)$dataRapportOeuf, "rapport_Inc");
    $agentID = $dataRapportOeuf->agents_idAgent;
    $natureID = $dataRapportOeuf->natures_idNature;
    $statutID = $dataRapportOeuf->status_rapport_id;


    $dataAgent =  getAgentDataById($agentID);
    $dataNature = getNatureById($natureID);
    $dataStatutR = getStatusRapportById($statutID);
    $data = array_merge((array)$dataAgent, (array)$dataNature, (array)$dataStatutR, (array)$dataRO);
    return $data;
}
function getRapportOeufDataById($rapportAlimentID)
{
    $rapportOeufModel = new Rapport_oeufsModel();

    $dataRapportOeuf = $rapportOeufModel->find($rapportAlimentID);
    $dataRO = changeAttributRapport((array)$dataRapportOeuf, "rapport_oeuf");
    $agentID = $dataRapportOeuf->agents_idAgent;
    $natureID = $dataRapportOeuf->natures_idNature;
    $statutID = $dataRapportOeuf->status_rapport_id;


    $dataAgent =  getAgentDataById($agentID);
    $dataNature = getNatureById($natureID);
    $dataStatutR = getStatusRapportById($statutID);
    $data = array_merge((array)$dataAgent, (array)$dataNature, (array)$dataStatutR, (array)$dataRO);
    return $data;
}
function getRapportPoussinDataById($rapportPoussinID)
{
    $rapportPoussinModel = new Rapport_poussinsModel();

    $dataRapportPoussin = $rapportPoussinModel->find($rapportPoussinID);
    $dataRP = changeAttributRapport((array)$dataRapportPoussin, "rapport_poussin");

    $agentID = $dataRapportPoussin->agents_idAgent;
    $natureID = $dataRapportPoussin->natures_idNature;
    $statutID = $dataRapportPoussin->status_rapport_id;

    $dataAgent =  getAgentDataById($agentID);
    $dataNature = getNatureById($natureID);
    $dataStatutR = getStatusRapportById($statutID);
    $data = array_merge((array)$dataAgent, (array)$dataNature, (array)$dataStatutR, (array)$dataRP);
    return $data;
}
function getCommandeClientDataById($cmdClientID)
{
    $commandeClientModel = new Commande_clientsModel();

    $datacommandeClient = $commandeClientModel->find($cmdClientID);
    $dataCC = changeAttribut((array)$datacommandeClient, "cmd_client");
    $clientID = $datacommandeClient->clients_idClient;
    $natureID = $datacommandeClient->natures_idNature;
    $statusCmdID = $datacommandeClient->statusCmd_id;
    $dataSC = getstatusCommandeById($statusCmdID);

    $dataClient = getClientDataById($clientID);
    $dataNature = getNatureById($natureID);
    $dataCmdProduit = getCommandeProduit($datacommandeClient->id);



    $data = array_merge($dataClient, (array)$dataCmdProduit, (array)$dataSC, (array)$dataNature, (array)$dataCC);
    return $data;
}
function getIncubationDataById($cmdClientID)
{
    $incubationModel = new IncubationsModel();

    $dataIncubation = $incubationModel->find($cmdClientID);
    $dataInc = changeAttribut((array)$dataIncubation, "incubation");
    $agentID = $dataIncubation->agents_idAgent;
    $natureID = $dataIncubation->natures_idNature;
    $statusIncID = $dataIncubation->status_id;

    $dataClient =  getAgentDataById($agentID);
    $dataNature = getNatureById($natureID);
    $dataStatusInc = getstatusIncById($statusIncID);

    $data = array_merge((array)$dataClient, (array)$dataNature, (array)$dataStatusInc, (array)$dataInc);
    return $data;
}
function getCommandeAlimentDataById($cmdAlimentID)
{
    $commandeAlimentModel = new Commande_alimentsModel();
    $dataCmdAliment = $commandeAlimentModel->find($cmdAlimentID);
    $dataCA = changeAttribut((array)$dataCmdAliment, "cmd_aliment");
    $cmdClientID = $dataCmdAliment->commandeClients_idCommande;

    $dataCmdClient =  getCommandeClientDataById($cmdClientID);
    $data = array_merge($dataCmdClient, (array)$dataCA);
    return $data;
}
function getCommandePouleDataById($cmdAlimentID)
{
    $commandePouleModel = new Commande_poulesModel();
    $dataCmdPoule = $commandePouleModel->find($cmdAlimentID);
    $dataCP = changeAttribut((array)$dataCmdPoule, "cmd_poule");
    $cmdClientID = $dataCmdPoule->commandeClients_idCommande;

    $dataCmdClient =  getCommandeClientDataById($cmdClientID);
    $data = array_merge($dataCmdClient, (array)$dataCP);
    return $data;
}
function getCommandePouletDataById($cmdAlimentID)
{
    $commandePouletModel = new Commande_pouletsModel();
    $dataCmdPoulet = $commandePouletModel->find($cmdAlimentID);
    $dataCPT = changeAttribut((array)$dataCmdPoulet, "cmd_poulet");
    $cmdClientID = $dataCmdPoulet->commandeClients_idCommande;

    $dataCmdClient =  getCommandeClientDataById($cmdClientID);
    $data = array_merge($dataCmdClient, (array)$dataCPT);
    return $data;
}
function getCommandeBiogazDataById($cmdBiogazID)
{
    $commandeBiogazModel = new Commande_biogazModel();

    $dataCmdBiogaz = $commandeBiogazModel->find($cmdBiogazID);
    $dataCB = changeAttribut((array)$dataCmdBiogaz, "cmd_biogaz");
    $cmdClientID = $dataCmdBiogaz->commandeClients_idCommande;


    $dataCmdClient =  getCommandeClientDataById($cmdClientID);
    $data = array_merge($dataCmdClient, (array)$dataCB);
    return $data;
}
function getCommandeOeufDataById($cmdOeufID)
{
    $commandeOeufModel = new Commande_oeufsModel();

    $dataCmdOeuf = $commandeOeufModel->find($cmdOeufID);
    $dataCO = changeAttribut((array)$dataCmdOeuf, "cmd_oeuf");
    $cmdClientID = $dataCmdOeuf->commandeClients_idCommande;

    $dataCmdClient =  getCommandeClientDataById($cmdClientID);
    $data = array_merge($dataCmdClient, (array)$dataCO);
    return $data;
}
function getCommandePoussinDataById($cmdOeufID)
{
    $commandePoussinModel = new Commande_poussinsModel();

    $dataCmdPoussin = $commandePoussinModel->find($cmdOeufID);
    $dataCP = changeAttribut((array)$dataCmdPoussin, "cmd_poussin");
    $cmdClientID = $dataCmdPoussin->commandeClients_idCommande;

    $dataCmdClient =  getCommandeClientDataById($cmdClientID);
    $data = array_merge($dataCmdClient, (array)$dataCP);
    return $data;
}

function getSortieDataById($sortieID)
{
    $sortieModel = new SortiesModel();

    $dataSortie = $sortieModel->find($sortieID);
    $dataS = changeAttribut((array)$dataSortie, "sortie");
    $agentID = $dataSortie->agents_id;
    $natureID = $dataSortie->natures_idNature;
    $motifID = $dataSortie->motifSorties_idMotif;

    $dataAgent =  getAgentDataById($agentID);
    $dataNature = getNatureById($natureID);
    $dataMotif = getDataMotifById($motifID);

    $data = array_merge((array)$dataAgent, (array)$dataNature, (array)$dataMotif,  (array)$dataS);
    return $data;
}
function getSortiePouletDataById($sortieAlimentID)
{
    $sortiePouletsModel = new Sortie_pouletsModel();

    $dataSortiePoulet = $sortiePouletsModel->find($sortieAlimentID);
    $dataSPt = changeAttribut((array)$dataSortiePoulet, "sortie_Poulet");
    $clientID = $dataSortiePoulet->clients_id;
    $sortieID = $dataSortiePoulet->sorties_idSortie;

    $dataClient =  getClientDataById($clientID);
    $dataSortie = getSortieDataById($sortieID);

    $data = array_merge((array)$dataClient, (array)$dataSortie,  (array)$dataSPt);
    return $data;
}
function getSortiePouleDataById($sortieAlimentID)
{
    $sortiePoulesModel = new Sortie_poulesModel();

    $dataSortiePoule = $sortiePoulesModel->find($sortieAlimentID);
    $dataSP = changeAttribut((array)$dataSortiePoule, "sortie_Poule");
    $clientID = $dataSortiePoule->clients_id;
    $sortieID = $dataSortiePoule->sorties_idSortie;

    $dataClient =  getClientDataById($clientID);
    $dataSortie = getSortieDataById($sortieID);

    $data = array_merge((array)$dataClient, (array)$dataSortie,  (array)$dataSP);
    return $data;
}
function getSortieAlimentDataById($sortieAlimentID)
{
    $sortieAlimentModel = new Sortie_alimentsModel();

    $dataSortieAliment = $sortieAlimentModel->find($sortieAlimentID);
    $dataSA = changeAttribut((array)$dataSortieAliment, "sortie_aliment");
    $clientID = $dataSortieAliment->clients_id;
    $sortieID = $dataSortieAliment->sorties_idSortie;


    $dataClient =  getClientDataById($clientID);
    $dataSortie = getSortieDataById($sortieID);

    $data = array_merge((array)$dataClient, (array)$dataSortie,  (array)$dataSA);
    return $data;
}
function getSortieBiogazDataById($sortieBiogazID)
{
    $sortieBiogazModel = new Sortie_biogazModel();

    $dataSortieBiogaz = $sortieBiogazModel->find($sortieBiogazID);
    $dataSB = changeAttribut((array)$dataSortieBiogaz, "sortie_biogaz");
    $clientID = $dataSortieBiogaz->clients_id;
    $sortieID = $dataSortieBiogaz->sorties_idSortie;


    $dataClient =  getClientDataById($clientID);
    $dataSortie = getSortieDataById($sortieID);

    $data = array_merge((array)$dataClient, (array)$dataSortie,  (array)$dataSB);
    return $data;
}
function getSortieOeufDataById($sortieBiogazID)
{
    $sortieOeufsModel = new Sortie_oeufsModel();

    $dataSortieOeuf = $sortieOeufsModel->find($sortieBiogazID);
    $dataSO = changeAttribut((array)$dataSortieOeuf, "sortie_oeuf");
    $clientID = $dataSortieOeuf->clients_id;
    $sortieID = $dataSortieOeuf->sorties_idSortie;

    $dataClient =  getClientDataById($clientID);
    $dataSortie = getSortieDataById($sortieID);

    $data = array_merge((array)$dataClient, (array)$dataSortie,  (array)$dataSO);
    return $data;
}
function getSortieIncDataById($sortieBiogazID)
{
    $sortieIncubationsModel = new Sortie_incubationsModel();

    $dataSortieInc = $sortieIncubationsModel->find($sortieBiogazID);
    $dataSI = changeAttribut((array)$dataSortieInc, "sortie_Inc");
    $clientID = $dataSortieInc->clients_id;
    $sortieID = $dataSortieInc->sorties_idSortie;

    $dataClient =  getClientDataById($clientID);
    $dataSortie = getSortieDataById($sortieID);

    $data = array_merge((array)$dataClient, (array)$dataSortie,  (array)$dataSI);
    return $data;
}
function getSortiePoussinDataById($sortiePoussinID)
{
    $sortiePoussinModel = new Sortie_poussinsModel();

    $dataSortiePoussin = $sortiePoussinModel->find($sortiePoussinID);
    $dataSP = changeAttribut((array)$dataSortiePoussin, "sortie_poussin");
    $clientID = $dataSortiePoussin->clients_id;
    $sortieID = $dataSortiePoussin->sorties_idSortie;

    $dataClient =  getClientDataById($clientID);
    $dataSortie = getSortieDataById($sortieID);

    $data = array_merge((array)$dataClient, (array)$dataSortie,  (array)$dataSP);
    return $data;
}
function getCommandeFournisseurDataById($cmdClientID)
{
    $commandeFournisseurModel = new Commande_fournisseursModel();
    $natureModel = new NaturesModel();

    $datacommandeFournisseur = $commandeFournisseurModel->find($cmdClientID);
    $dataCF = changeAttribut((array)$datacommandeFournisseur, "cmd_fournisseur");
    $fournisseurID = $datacommandeFournisseur->Fournisseurs_idFournisseur;
    $natureID = $datacommandeFournisseur->natures_idNature;

    $dataFournisseur =  getFournisseurDataById($fournisseurID);
    $dataNature = getNatureById($natureID);
    $data = array_merge($dataFournisseur, (array)$dataNature,  (array)$dataCF);
    return $data;
}

function getStockAlimentDataById($stockAlimentID)
{
    $stockAlimentModel = new Stock_alimentsModel;

    $dataStockAliment = $stockAlimentModel->find($stockAlimentID);
    $dataSA = changeAttribut((array)$dataStockAliment, "stock_Aliment");;

    $natureID = $dataStockAliment->natures_idNature;
    $fournisseurID = $dataStockAliment->fournisseur_id;
    $dataNature = getNatureById($natureID);
    $dataFournisseur = getFournisseurdataById($fournisseurID);

    $data = array_merge((array)$dataNature, (array)$dataFournisseur, (array)$dataSA);

    return $data;
}
function getStockPouletDataById($stockAlimentID)
{
    $stockPouletModel = new Stock_pouletsModel;

    $dataStockPoulet = $stockPouletModel->find($stockAlimentID);
    $dataSP = changeAttribut((array)$dataStockPoulet, "stock_Poulet");;

    $natureID = $dataStockPoulet->natures_idNature;
    $fournisseurID = $dataStockPoulet->fournisseur_id;
    $dataNature = getNatureById($natureID);
    $dataFournisseur = getFournisseurdataById($fournisseurID);

    $data = array_merge((array)$dataNature, (array)$dataFournisseur, (array)$dataSP);

    return $data;
}
function getStockPouleDataById($stockAlimentID)
{
    $stockPouleModel = new Stock_poulesModel;


    $dataStockPoule = $stockPouleModel->find($stockAlimentID);
    $dataSP = changeAttribut((array)$dataStockPoule, "stock_Poule");;

    $natureID = $dataStockPoule->natures_idNature;
    $fournisseurID = $dataStockPoule->fournisseur_id;
    $dataNature = getNatureById($natureID);
    $dataFournisseur = getFournisseurdataById($fournisseurID);

    $data = array_merge((array)$dataNature, (array)$dataFournisseur, (array)$dataSP);

    return $data;
}
function getStockBiogazDataById($stockBiogazID)
{
    $stockBiogazModel = new Stock_biogazModel;


    $dataStockBiogaz = $stockBiogazModel->find($stockBiogazID);
    $dataSB = changeAttribut((array)$dataStockBiogaz, "stock_biogaz");

    $natureID = $dataStockBiogaz->natures_idNature;
    $fournisseurID = $dataStockBiogaz->fournisseur_id;
    $dataNature = getNatureById($natureID);
    $dataFournisseur = getFournisseurdataById($fournisseurID);

    $data = array_merge((array)$dataNature, (array)$dataFournisseur, (array)$dataSB);

    return $data;
}
function getStockOeufDataById($stockOeufID)
{
    $stockOeufsModel = new Stock_oeufsModel;

    $dataStockOeuf = $stockOeufsModel->find($stockOeufID);
    $dataSO = changeAttribut((array)$dataStockOeuf, "stock_oeuf");
    $natureID = $dataStockOeuf->natures_idNature;
    $fournisseurID = $dataStockOeuf->fournisseur_id;
    $dataNature = getNatureById($natureID);
    $dataFournisseur = getFournisseurdataById($fournisseurID);

    $data = array_merge((array)$dataNature, (array)$dataFournisseur, (array)$dataSO);
    return $data;
}
function getStockPoussinDataById($stockPoussinID)
{
    $stockpoussinsModel = new Stock_poussinsModel;

    $dataStockpoussin = $stockpoussinsModel->find($stockPoussinID);
    $dataSP = changeAttribut((array)$dataStockpoussin, "stock_poussin");
    $natureID = $dataStockpoussin->natures_idNature;
    $fournisseurID = $dataStockpoussin->fournisseur_id;
    $dataNature = getNatureById($natureID);
    $dataFournisseur = getFournisseurdataById($fournisseurID);

    $data = array_merge((array)$dataNature, (array)$dataFournisseur, (array)$dataSP);
    return $data;
}


function getAgentDataById($agentID)
{
    $agentModel = new AgentsModel();
    $serviceModel = new ServicesModel();

    $dataAgent = $agentModel->find($agentID);
    $dataA = changeAttribut((array)$dataAgent, "agent");

    $personneID = $dataAgent->personnes_idPersonne;
    $serviceID = $dataAgent->services_id;
    $dataPersonne = getPersonneDataById($personneID);
    // $dataService = $serviceModel->find($serviceID);
    $dataService = getServiceDataById($serviceID);

    $data = array_merge((array)$dataPersonne, (array)$dataService, (array)$dataA);
    return $data;
}
function getClientDataById($agentID)
{
    $clientModel = new ClientsModel();
    $dataClient = $clientModel->find($agentID);
    $dataC = changeAttribut((array)$dataClient, "client");

    $personneID = $dataClient->personnes_idPersonne;
    $trancheID = $dataClient->tranche_age_id;
    $dataPersonne = getPersonneDataById($personneID);
    $dataTranche = getTrancheAgeDataById($trancheID);

    $data = array_merge((array)$dataPersonne, (array)$dataTranche, (array)$dataC);
    return $data;
}
function getTrancheAgeDataById($serviceID)
{
    $trancheAgeModel = new Tranche_agesModel();
    $dataTranche = $trancheAgeModel->find($serviceID);
    $dataA = changeAttribut((array)$dataTranche, "Tranche_age");

    $data = array_merge((array) $dataA);
    return $data;
}
function getServiceDataById($serviceID)
{
    $serviceModel = new ServicesModel();
    $dataService = $serviceModel->find($serviceID);
    $dataS = changeAttribut((array)$dataService, "service");

    $data = array_merge((array) $dataS);
    return $data;
}
function getCategorieProduitDataById($catProduitID)
{
    $catProduitModel = new Categorie_produitModel();
    $dataCatProduit =  $catProduitModel->find($catProduitID);
    $data = changeAttribut((array)$dataCatProduit, "categorie_produit");

    // $data = array_merge((array)$dataP, (array)$dataA);
    return $data;
}
function getPersonneDataById($personneID)
{
    $personneModel = new PersonnesModel();
    $adresseModel = new AdressesModel();

    $dataPersonne = $personneModel->find($personneID);
    $dataP = changeAttribut((array)$dataPersonne, "personne");
    // debug400('Test', $personneID);
    $adresseID = $dataPersonne->adresses_idAdresse;
    $dataAdresse = $adresseModel->find($adresseID);
    $dataA = changeAttribut((array)$dataAdresse, "adresse");

    $data = array_merge((array)$dataP, (array)$dataA);
    return $data;
}
function getFournisseurDataById($fournisseurID)
{
    $fournisseurModel = new FournisseursModel();
    $dataFournisseur = $fournisseurModel->find($fournisseurID);
    $dataF = changeAttribut((array)$dataFournisseur, "fournisseur");
    $personneID = $dataFournisseur->personnes_idPersonne;
    $catProdID = $dataFournisseur->cat_produit_id;

    $dataPersonne = getPersonneDataById($personneID);
    $dataCatProduit = getCategorieProduitDataById($catProdID);

    $data = array_merge((array)$dataPersonne, (array)$dataCatProduit, (array)$dataF);

    return $data;
}
function getListJournalActivityDataById($listAdmin)
{
    $dataAll = array();

    for ($i = 0, $size = count($listAdmin); $i < $size; ++$i) {
        $dataAll[$i] = getJournalActivityDataById($listAdmin[$i]->id);;
    }
    return $dataAll;
}
function getListAdminDataById($listAdmin)
{
    $dataAll = array();

    for ($i = 0, $size = count($listAdmin); $i < $size; ++$i) {
        $dataAll[$i] = getAdminDataById($listAdmin[$i]->id);;
    }
    return $dataAll;
}
function getListAgentDataById($listAgent)
{
    $dataAll = array();

    for ($i = 0, $size = count($listAgent); $i < $size; ++$i) {
        $dataAll[$i] = getAgentDataById($listAgent[$i]->id);;
    }
    return $dataAll;
}
function getListClientDataById($listClient)
{
    $dataAll = array();

    for ($i = 0, $size = count($listClient); $i < $size; ++$i) {
        $dataAll[$i] = getClientDataById($listClient[$i]->id);;
    }
    return $dataAll;
}
function getListFournisseurDataById($listFournisseur)
{
    $dataAll = array();

    for ($i = 0, $size = count($listFournisseur); $i < $size; ++$i) {
        $dataAll[$i] = getFournisseurDataById($listFournisseur[$i]->id);;
    }
    return $dataAll;
}
function getListEntreesDataById($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $dataAll[$i] = getEntreeDataById($listEntrees[$i]->id);;
    }
    return $dataAll;
}
function getListStockPouletDataById($listStockA)
{
    $dataAll = array();

    for ($i = 0, $size = count($listStockA); $i < $size; ++$i) {
        $dataAll[$i] = getStockPouletDataById($listStockA[$i]->id);;
    }
    return $dataAll;
}
function getListStockPouleDataById($listStockA)
{
    $dataAll = array();

    for ($i = 0, $size = count($listStockA); $i < $size; ++$i) {
        $dataAll[$i] = getStockPouleDataById($listStockA[$i]->id);;
    }
    return $dataAll;
}
function getListStockAlimentDataById($listStockA)
{
    $dataAll = array();

    for ($i = 0, $size = count($listStockA); $i < $size; ++$i) {
        $dataAll[$i] = getStockAlimentDataById($listStockA[$i]->id);;
    }
    return $dataAll;
}
function getListStockBiogazData($listStockB)
{
    $dataAll = array();

    for ($i = 0, $size = count($listStockB); $i < $size; ++$i) {
        $dataAll[$i] = getStockBiogazDataById($listStockB[$i]->id);;
    }
    return $dataAll;
}
function getListStockOeufData($listStockOeuf)
{
    $dataAll = array();

    for ($i = 0, $size = count($listStockOeuf); $i < $size; ++$i) {
        $dataAll[$i] = getStockOeufDataById($listStockOeuf[$i]->id);;
    }
    return $dataAll;
}
function getListStockPoussinData($listStockPoussin)
{
    $dataAll = array();
    for ($i = 0, $size = count($listStockPoussin); $i < $size; ++$i) {
        $dataAll[$i] = getStockPoussinDataById($listStockPoussin[$i]->id);;
    }
    return $dataAll;
}
function getListEntreePouletData($listEntreePoulet)
{
    $dataAll = array();
    for ($i = 0, $size = count($listEntreePoulet); $i < $size; ++$i) {
        $dataAll[$i] = getEntreePouletDataById($listEntreePoulet[$i]->id);;
    }
    return $dataAll;
}
function getListEntreePouleData($listEntreePoule)
{
    $dataAll = array();
    for ($i = 0, $size = count($listEntreePoule); $i < $size; ++$i) {
        $dataAll[$i] = getEntreePouleDataById($listEntreePoule[$i]->id);;
    }
    return $dataAll;
}
function getListEntreeAlimentData($listEntreeAliment)
{
    $dataAll = array();
    for ($i = 0, $size = count($listEntreeAliment); $i < $size; ++$i) {
        $dataAll[$i] = getEntreeAlimentDataById($listEntreeAliment[$i]->id);;
    }
    return $dataAll;
}
function getListEntreeBiogazData($listEntreeBiogaz)
{
    $dataAll = array();
    for ($i = 0, $size = count($listEntreeBiogaz); $i < $size; ++$i) {
        $dataAll[$i] = getEntreeBiogazDataById($listEntreeBiogaz[$i]->id);;
    }
    return $dataAll;
}
function getListEntreeOeufData($listEntreeOeuf)
{
    $dataAll = array();
    for ($i = 0, $size = count($listEntreeOeuf); $i < $size; ++$i) {
        $dataAll[$i] = getEntreeOeufDataById($listEntreeOeuf[$i]->id);;
    }
    return $dataAll;
}
function getListEntreePoussinData($listEntreePoussin)
{
    $dataAll = array();
    for ($i = 0, $size = count($listEntreePoussin); $i < $size; ++$i) {
        $dataAll[$i] = getEntreePoussinDataById($listEntreePoussin[$i]->id);;
    }
    return $dataAll;
}
function getListReservationCFData($listReservationCF)
{
    $dataAll = array();
    for ($i = 0, $size = count($listReservationCF); $i < $size; ++$i) {
        $dataAll[$i] = getReservationCFDataById($listReservationCF[$i]->id);;
    }
    return $dataAll;
}
function getListReservationPouletData($listReservationP)
{
    $dataAll = array();
    for ($i = 0, $size = count($listReservationP); $i < $size; ++$i) {
        $dataAll[$i] = getReservationPDataById($listReservationP[$i]->id);;
    }
    return $dataAll;
}
function getListRapportPouletData($listRapportPoulet)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportPoulet); $i < $size; ++$i) {
        $dataAll[$i] = getRapportPouletDataById($listRapportPoulet[$i]->id);;
    }
    return $dataAll;
}
function getListRapportPouleData($listRapportPoule)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportPoule); $i < $size; ++$i) {
        $dataAll[$i] = getRapportPouleDataById($listRapportPoule[$i]->id);;
    }
    return $dataAll;
}
function getListRapportAlimentData($listRapportAliment)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportAliment); $i < $size; ++$i) {
        $dataAll[$i] = getRapportAlimentDataById($listRapportAliment[$i]->id);;
    }
    return $dataAll;
}
function getListRapportBiogazData($listRapportBiogaz)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportBiogaz); $i < $size; ++$i) {
        $dataAll[$i] = getRapportBiogazDataById($listRapportBiogaz[$i]->id);;
    }
    return $dataAll;
}
function getListRapportOeufData($listRapportOeuf)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportOeuf); $i < $size; ++$i) {
        $dataAll[$i] = getRapportOeufDataById($listRapportOeuf[$i]->id);;
    }
    return $dataAll;
}
function getListRapportIncData($listRapportOeuf)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportOeuf); $i < $size; ++$i) {
        $dataAll[$i] = getRapportIncDataById($listRapportOeuf[$i]->id);;
    }
    return $dataAll;
}
function getListRapportPoussinData($listRapportP)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportP); $i < $size; ++$i) {
        $dataAll[$i] = getRapportPoussinDataById($listRapportP[$i]->id);;
    }
    return $dataAll;
}
function getListCommandeClientData($cmdClient)
{
    $dataAll = array();
    for ($i = 0, $size = count($cmdClient); $i < $size; ++$i) {
        $dataAll[$i] = getCommandeClientDataById($cmdClient[$i]->id);;
    }
    return $dataAll;
}
function getListCommandeFournisseurData($cmdFournisseur)
{
    $dataAll = array();
    for ($i = 0, $size = count($cmdFournisseur); $i < $size; ++$i) {
        $dataAll[$i] = getCommandeFournisseurDataById($cmdFournisseur[$i]->id);;
    }
    return $dataAll;
}
function getListCommandeAlimentData($cmdAliment)
{
    $dataAll = array();
    for ($i = 0, $size = count($cmdAliment); $i < $size; ++$i) {
        $dataAll[$i] = getCommandeAlimentDataById($cmdAliment[$i]->id);;
    }
    return $dataAll;
}
function getListCommandePouleData($cmdPoule)
{
    $dataAll = array();
    for ($i = 0, $size = count($cmdPoule); $i < $size; ++$i) {
        $dataAll[$i] = getCommandePouleDataById($cmdPoule[$i]->id);;
    }
    return $dataAll;
}
function getListCommandePouletData($cmdPoulet)
{
    $dataAll = array();
    for ($i = 0, $size = count($cmdPoulet); $i < $size; ++$i) {
        $dataAll[$i] = getCommandePouletDataById($cmdPoulet[$i]->id);;
    }
    return $dataAll;
}
function getListCommandeBiogazData($cmdBiogaz)
{
    $dataAll = array();
    for ($i = 0, $size = count($cmdBiogaz); $i < $size; ++$i) {
        $dataAll[$i] = getCommandeBiogazDataById($cmdBiogaz[$i]->id);;
    }
    return $dataAll;
}
function getListCommandeOeufData($cmdoeuf)
{
    $dataAll = array();
    for ($i = 0, $size = count($cmdoeuf); $i < $size; ++$i) {
        $dataAll[$i] = getCommandeOeufDataById($cmdoeuf[$i]->id);;
    }
    return $dataAll;
}
function getListCommandePoussinData($cmdPoussin)
{
    $dataAll = array();
    for ($i = 0, $size = count($cmdPoussin); $i < $size; ++$i) {
        $dataAll[$i] = getCommandePoussinDataById($cmdPoussin[$i]->id);;
    }
    return $dataAll;
}
function getListIncubationData($incubation)
{
    $dataAll = array();
    for ($i = 0, $size = count($incubation); $i < $size; ++$i) {
        $dataAll[$i] = getIncubationDataById($incubation[$i]->id);;
    }
    return $dataAll;
}
function getListSortieData($sortie)
{
    $dataAll = array();
    for ($i = 0, $size = count($sortie); $i < $size; ++$i) {
        $dataAll[$i] = getSortieDataById($sortie[$i]->id);;
    }
    return $dataAll;
}
function getListSortiePouletData($sortieAliment)
{
    $dataAll = array();
    for ($i = 0, $size = count($sortieAliment); $i < $size; ++$i) {
        $dataAll[$i] = getSortiePouletDataById($sortieAliment[$i]->id);;
    }
    return $dataAll;
}
function getListSortiePouleData($sortieAliment)
{
    $dataAll = array();
    for ($i = 0, $size = count($sortieAliment); $i < $size; ++$i) {
        $dataAll[$i] = getSortiePouleDataById($sortieAliment[$i]->id);;
    }
    return $dataAll;
}
function getListSortieAlimentData($sortieAliment)
{
    $dataAll = array();
    for ($i = 0, $size = count($sortieAliment); $i < $size; ++$i) {
        $dataAll[$i] = getSortieAlimentDataById($sortieAliment[$i]->id);;
    }
    return $dataAll;
}
function getListSortieBiogazData($sortieBiogaz)
{
    $dataAll = array();
    for ($i = 0, $size = count($sortieBiogaz); $i < $size; ++$i) {
        $dataAll[$i] = getSortieBiogazDataById($sortieBiogaz[$i]->id);;
    }
    return $dataAll;
}
function getListSortieOeufData($sortieBiogaz)
{
    $dataAll = array();
    for ($i = 0, $size = count($sortieBiogaz); $i < $size; ++$i) {
        $dataAll[$i] = getSortieOeufDataById($sortieBiogaz[$i]->id);;
    }
    return $dataAll;
}
function getListSortieIncData($sortieBiogaz)
{
    $dataAll = array();
    for ($i = 0, $size = count($sortieBiogaz); $i < $size; ++$i) {
        $dataAll[$i] = getSortieIncDataById($sortieBiogaz[$i]->id);;
    }
    return $dataAll;
}
function getListSortiePoussinData($sortiePoussin)
{
    $dataAll = array();
    for ($i = 0, $size = count($sortiePoussin); $i < $size; ++$i) {
        $dataAll[$i] = getSortiePoussinDataById($sortiePoussin[$i]->id);;
    }
    return $dataAll;
}
function getListCatAdminData($catAdmin)
{
    $dataAll = array();
    for ($i = 0, $size = count($catAdmin); $i < $size; ++$i) {
        $dataAll[$i] = getCategorieAdminById($catAdmin[$i]->id);;
    }
    return $dataAll;
}
function getListMotifData($catAdmin)
{
    $dataAll = array();
    for ($i = 0, $size = count($catAdmin); $i < $size; ++$i) {
        $dataAll[$i] = getDataMotifById($catAdmin[$i]->id);;
    }
    return $dataAll;
}
function getListPersonneData($personnes)
{
    $dataAll = array();
    for ($i = 0, $size = count($personnes); $i < $size; ++$i) {
        $dataAll[$i] = getPersonneDataById($personnes[$i]->id);;
    }
    return $dataAll;
}


#Specification Affiche 

#Year
function getListEntreesDataByIdYear($listEntrees, $year)
{
    $dataAll = array();
    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $annee =  date('Y', strtotime($date));

        if ($year == $annee) {
            $dataAll[] = getEntreeDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}



// $dateFinIncubation = date('Y-m-d H:i:s', $formatFinIncubation);
#week

function getListEntreesDataByIdMonth($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finMois = moisFin();

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportAlimentDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}


function getListEntreesDataByIdWeek($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finSemaine = semaineFin();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getEntreeDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}



#Day
function getListEntreesDataByIdDay($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finJourne = journee();

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getEntreeDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}



# SORTIES LISTES
function getListSortiesDataByIdYear($listSorties, $year)
{
    $dataAll = array();
    for ($i = 0, $size = count($listSorties); $i < $size; ++$i) {
        $date = $listSorties[$i]->date;
        $annee =  date('Y', strtotime($date));

        if ($year == $annee) {
            $dataAll[] = getSortieDataById($listSorties[$i]->id);
        }
    }
    return $dataAll;
}

function getListSortiesDataByIdMonth($listSorties)
{
    $dataAll = array();
    for ($i = 0, $size = count($listSorties); $i < $size; ++$i) {
        $date = $listSorties[$i]->date;
        $finMois = moisFin();

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getSortieDataById($listSorties[$i]->id);
        }
    }
    return $dataAll;
}
function getListSortiesDataByIdWeek($listSorties)
{
    $dataAll = array();
    for ($i = 0, $size = count($listSorties); $i < $size; ++$i) {
        $date = $listSorties[$i]->date;
        $finSemaine = semaineFin();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getSortieDataById($listSorties[$i]->id);
        }
    }
    return $dataAll;
}
function getListSortiesDataByIdDay($listSorties)
{
    $dataAll = array();
    for ($i = 0, $size = count($listSorties); $i < $size; ++$i) {
        $date = $listSorties[$i]->date;
        $finSemaine = journee();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getSortieDataById($listSorties[$i]->id);
        }
    }
    return $dataAll;
}


#DESTOCKAGE
function reduireStockPoulet($stockPouletData)
{

    $stockPouletModel = new Stock_pouletsModel();
    $stockPoulet = $stockPouletModel;
    $natureID = $stockPouletData['natures_idNature'];
    $quantite = (float)$stockPouletData['quantite'];
    $idStock = null;
    $qteStock = null;
    $pouletNatures = getIdNaturePoulet();
    if (in_array($natureID, $pouletNatures)) {

        if ($quantite !== null && $quantite > 0) {
            $DataStockQte = array(
                "natures_idNature" => $natureID,
            );
            $dataStock = $stockPouletModel->findBy($DataStockQte);
            $maxQte = quantiteStockMax($dataStock);
            if ($quantite < $maxQte) {
                for ($i = 0, $size = count($dataStock); $i < $size; $i++) {


                    if ($dataStock[$i]->quantite == $quantite) {
                        # si la qte demande correspond a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockPoulet->setQuantite($NewQteStock);
                            $stockPoulet->setUpdated_at(getSiku());
                            $stockPouletModel->update((int)$idStock, $stockPoulet);
                        }
                        break;
                    } elseif ($dataStock[$i]->quantite >= $quantite) {
                        # si la qte demandee est inferieur a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockPoulet->setQuantite($NewQteStock);
                            $stockPoulet->setUpdated_at(getSiku());
                            $stockPouletModel->update((int)$idStock, $stockPoulet);
                        }
                        break;
                    } elseif ($maxQte >= $quantite) {
                        # si la qte demandee est inferieur a la quantite max disponible en stock
                        do {
                            $idStock = $dataStock[$i]->id;
                            $qteStock = $dataStock[$i]->quantite;
                            if ($qteStock < $quantite) {
                                $quantite = $quantite - $qteStock;
                                #Reduction Stock
                                $NewQteStock = 0;
                                if ($NewQteStock >= 0) {
                                    $stockPoulet->setQuantite($NewQteStock);
                                    $stockPoulet->setUpdated_at(getSiku());
                                    $stockPouletModel->update((int)$idStock, $stockPoulet);
                                }
                            } elseif ($qteStock >= $quantite) {
                                #Reduction Stock
                                $NewQteStock = $qteStock - $quantite;
                                $quantite = 0;
                                if ($NewQteStock >= 0) {
                                    $stockPoulet->setQuantite($NewQteStock);
                                    $stockPoulet->setUpdated_at(getSiku());
                                    $stockPouletModel->update((int)$idStock, $stockPoulet);
                                }
                            }
                        } while ($quantite == 0);
                    }
                }
            } else {
                $message = "La quantite renseignée n'est pas disponible en stock";
                return success205($message);
            }
        } else {
            $message = " Verifier la valeur de la quantite ";
            return success205($message);
        }
    } else {
        $message = "La nature du produit n'est pas " . DESIGN_POULET;
        return success205($message);
    }
}
function reduireStockPoule($stockPouleData)
{

    $stockPouleModel = new Stock_poulesModel();
    $stockPoule = $stockPouleModel;
    $natureID = $stockPouleData['natures_idNature'];
    $quantite = (float)$stockPouleData['quantite'];
    $idStock = null;
    $qteStock = null;
    $pouleNatures = getIdNaturePoule();
    if (in_array($natureID, $pouleNatures)) {

        if ($quantite !== null && $quantite > 0) {
            $DataStockQte = array(
                "natures_idNature" => $natureID,
            );
            $dataStock = $stockPouleModel->findBy($DataStockQte);
            $maxQte = quantiteStockMax($dataStock);
            if ($quantite < $maxQte) {
                for ($i = 0, $size = count($dataStock); $i < $size; $i++) {


                    if ($dataStock[$i]->quantite == $quantite) {
                        # si la qte demande correspond a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockPoule->setQuantite($NewQteStock);
                            $stockPoule->setUpdated_at(getSiku());
                            $stockPouleModel->update((int)$idStock, $stockPoule);
                        }
                        break;
                    } elseif ($dataStock[$i]->quantite >= $quantite) {
                        # si la qte demandee est inferieur a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockPoule->setQuantite($NewQteStock);
                            $stockPoule->setUpdated_at(getSiku());
                            $stockPouleModel->update((int)$idStock, $stockPoule);
                        }
                        break;
                    } elseif ($maxQte >= $quantite) {
                        # si la qte demandee est inferieur a la quantite max disponible en stock
                        do {
                            $idStock = $dataStock[$i]->id;
                            $qteStock = $dataStock[$i]->quantite;
                            if ($qteStock < $quantite) {
                                $quantite = $quantite - $qteStock;
                                #Reduction Stock
                                $NewQteStock = 0;
                                if ($NewQteStock >= 0) {
                                    $stockPoule->setQuantite($NewQteStock);
                                    $stockPoule->setUpdated_at(getSiku());
                                    $stockPouleModel->update((int)$idStock, $stockPoule);
                                }
                            } elseif ($qteStock >= $quantite) {
                                #Reduction Stock
                                $NewQteStock = $qteStock - $quantite;
                                $quantite = 0;
                                if ($NewQteStock >= 0) {
                                    $stockPoule->setQuantite($NewQteStock);
                                    $stockPoule->setUpdated_at(getSiku());
                                    $stockPouleModel->update((int)$idStock, $stockPoule);
                                }
                            }
                        } while ($quantite == 0);
                    }
                }
            } else {
                $message = "La quantite renseignée n'est pas disponible en stock";
                return success205($message);
            }
            for ($i = 0, $size = count($dataStock); $i < $size; $i++) {


                if ($dataStock[$i]->quantite == $quantite) {
                    # si la qte demande correspond a une quantite dans le stock
                    $idStock = $dataStock[$i]->id;
                    $qteStock = $dataStock[$i]->quantite;
                    #Reduction Stock
                    $NewQteStock = $qteStock - $quantite;
                    if ($NewQteStock >= 0) {

                        $stockPoule->setQuantite($NewQteStock);
                        $stockPoule->setUpdated_at(getSiku());
                        $stockPouleModel->update((int)$idStock, $stockPoule);
                    }
                    break;
                } elseif ($dataStock[$i]->quantite >= $quantite) {
                    # si la qte demandee est inferieur a une quantite dans le stock
                    $idStock = $dataStock[$i]->id;
                    $qteStock = $dataStock[$i]->quantite;
                    #Reduction Stock
                    $NewQteStock = $qteStock - $quantite;
                    if ($NewQteStock >= 0) {

                        $stockPoule->setQuantite($NewQteStock);
                        $stockPoule->setUpdated_at(getSiku());
                        $stockPouleModel->update((int)$idStock, $stockPoule);
                    }
                    break;
                } elseif ($maxQte >= $quantite) {
                    # si la qte demandee est inferieur a la quantite max disponible en stock
                    do {
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        if ($qteStock < $quantite) {
                            $quantite = $quantite - $qteStock;
                            #Reduction Stock
                            $NewQteStock = 0;
                            if ($NewQteStock >= 0) {
                                $stockPoule->setQuantite($NewQteStock);
                                $stockPoule->setUpdated_at(getSiku());
                                $stockPouleModel->update((int)$idStock, $stockPoule);
                            }
                        } elseif ($qteStock >= $quantite) {
                            #Reduction Stock
                            $NewQteStock = $qteStock - $quantite;
                            $quantite = 0;
                            if ($NewQteStock >= 0) {
                                $stockPoule->setQuantite($NewQteStock);
                                $stockPoule->setUpdated_at(getSiku());
                                $stockPouleModel->update((int)$idStock, $stockPoule);
                            }
                        }
                    } while ($quantite == 0);
                }
            }
        } else {
            $message = " Verifier la valeur de la quantite ";
            return success205($message);
        }
    } else {
        $message = "La nature du produit n'est pas " . DESIGN_POULE;
        return success205($message);
    }
}
function reduireStockAliment($stockAlimentData)
{

    $stockAlimentModel = new Stock_alimentsModel();
    $stockAliment = $stockAlimentModel;
    $natureID = $stockAlimentData['natures_idNature'];
    $quantite = (float)$stockAlimentData['quantite'];
    $idStock = null;
    $qteStock = null;
    $alimentNatures = getIdNatureAliment();
    if (in_array($natureID, $alimentNatures)) {

        if ($quantite !== null && $quantite > 0) {
            $DataStockQte = array(
                "natures_idNature" => $natureID,
            );
            $dataStock = $stockAlimentModel->findBy($DataStockQte);
            $maxQte = quantiteStockMax($dataStock);
            if ($quantite < $maxQte) {
                for ($i = 0, $size = count($dataStock); $i < $size; $i++) {


                    if ($dataStock[$i]->quantite == $quantite) {
                        # si la qte demande correspond a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockAliment->setQuantite($NewQteStock);
                            $stockAliment->setUpdated_at(getSiku());
                            $stockAlimentModel->update((int)$idStock, $stockAliment);
                        }
                        break;
                    } elseif ($dataStock[$i]->quantite >= $quantite) {
                        # si la qte demandee est inferieur a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockAliment->setQuantite($NewQteStock);
                            $stockAliment->setUpdated_at(getSiku());
                            $stockAlimentModel->update((int)$idStock, $stockAliment);
                        }
                        break;
                    } elseif ($maxQte >= $quantite) {
                        # si la qte demandee est inferieur a la quantite max disponible en stock
                        do {
                            $idStock = $dataStock[$i]->id;
                            $qteStock = $dataStock[$i]->quantite;
                            if ($qteStock < $quantite) {
                                $quantite = $quantite - $qteStock;
                                #Reduction Stock
                                $NewQteStock = 0;
                                if ($NewQteStock >= 0) {
                                    $stockAliment->setQuantite($NewQteStock);
                                    $stockAliment->setUpdated_at(getSiku());
                                    $stockAlimentModel->update((int)$idStock, $stockAliment);
                                }
                            } elseif ($qteStock >= $quantite) {
                                #Reduction Stock
                                $NewQteStock = $qteStock - $quantite;
                                $quantite = 0;
                                if ($NewQteStock >= 0) {
                                    $stockAliment->setQuantite($NewQteStock);
                                    $stockAliment->setUpdated_at(getSiku());
                                    $stockAlimentModel->update((int)$idStock, $stockAliment);
                                }
                            }
                        } while ($quantite == 0);
                    }
                }
            } else {
                $message = "La quantite renseignée n'est pas disponible en stock";
                return success205($message);
            }
        } else {
            $message = " Verifier la valeur de la quantite ";
            return success205($message);
        }
    } else {
        $message = "La nature du produit n'est pas un Aliment ";
        return success205($message);
    }
}
function reduireStockBiogaz($stockBiogazData)
{
    $stockBiogazModel = new Stock_biogazModel();
    $stockBiogaz = $stockBiogazModel;
    $natureID = $stockBiogazData['natures_idNature'];
    $quantite = (float)$stockBiogazData['quantite'];
    $idStock = null;
    $qteStock = null;
    $biogazNatures = getIdNatureBiogaz();
    if (in_array($natureID, $biogazNatures)) {

        if ($quantite !== null && $quantite > 0) {

            $DataStockQte = array(
                "natures_idNature" => $natureID,
            );
            $dataStock = $stockBiogazModel->findBy($DataStockQte);
            $maxQte = quantiteStockMax($dataStock);
            if ($quantite < $maxQte) {
                for ($i = 0, $size = count($dataStock); $i < $size; $i++) {

                    if ($dataStock[$i]->quantite == $quantite) {
                        # si la qte demande correspond a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockBiogaz->setQuantite($NewQteStock);
                            $stockBiogaz->setUpdated_at(getSiku());
                            $stockBiogazModel->update((int)$idStock, $stockBiogaz);
                        }
                        break;
                    } elseif ($dataStock[$i]->quantite >= $quantite) {
                        # si la qte demandee est inferieur a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockBiogaz->setQuantite($NewQteStock);
                            $stockBiogaz->setUpdated_at(getSiku());
                            $stockBiogazModel->update((int)$idStock, $stockBiogaz);
                        }
                        break;
                    } elseif ($maxQte >= $quantite) {
                        # si la qte demandee est inferieur a la quantite max disponible en stock
                        do {
                            $idStock = $dataStock[$i]->id;
                            $qteStock = $dataStock[$i]->quantite;
                            if ($qteStock < $quantite) {
                                $quantite = $quantite - $qteStock;
                                #Reduction Stock
                                $NewQteStock = 0;
                                if ($NewQteStock >= 0) {
                                    $stockBiogaz->setQuantite($NewQteStock);
                                    $stockBiogaz->setUpdated_at(getSiku());
                                    $stockBiogazModel->update((int)$idStock, $stockBiogaz);
                                }
                            } elseif ($qteStock >= $quantite) {
                                #Reduction Stock
                                $NewQteStock = $qteStock - $quantite;
                                $quantite = 0;
                                if ($NewQteStock >= 0) {
                                    $stockBiogaz->setQuantite($NewQteStock);
                                    $stockBiogaz->setUpdated_at(getSiku());
                                    $stockBiogazModel->update((int)$idStock, $stockBiogaz);
                                }
                            }
                        } while ($quantite == 0);
                    }
                }
            } else {
                $message = "La quantite renseignée n'est pas disponible en stock";
                return success205($message);
            }
        } else {

            $message = " Verifier la valeur de la quantite ";
            return success205($message);
        }
    } else {
        $message = "La nature du produit n'est pas un Biogaz ";
        return success205($message);
    }
}

function reduireStockOeuf($stockBiogazData)
{
    $stockOeufModel = new Stock_oeufsModel();
    $stockOeuf = $stockOeufModel;
    $natureID = $stockBiogazData['natures_idNature'];
    $quantite = (float)$stockBiogazData['quantite'];
    $idStock = null;
    $qteStock = null;
    $oeufNatures = getIdNatureOeuf();
    if (in_array($natureID,  $oeufNatures)) {

        if ($quantite !== null && $quantite > 0) {

            $DataStockQte = array(
                "natures_idNature" => $natureID,
            );
            $dataStock = $stockOeufModel->findBy($DataStockQte);
            $maxQte = quantiteStockMax($dataStock);
            if ($quantite < $maxQte) {
                for ($i = 0, $size = count($dataStock); $i < $size; $i++) {

                    if ($dataStock[$i]->quantite == $quantite) {
                        # si la qte demande correspond a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockOeuf->setQuantite($NewQteStock);
                            $stockOeuf->setUpdated_at(getSiku());
                            $stockOeufModel->update((int)$idStock, $stockOeuf);
                        }
                        break;
                    } elseif ($dataStock[$i]->quantite >= $quantite) {
                        # si la qte demandee est inferieur a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockOeuf->setQuantite($NewQteStock);
                            $stockOeuf->setUpdated_at(getSiku());
                            $stockOeufModel->update((int)$idStock, $stockOeuf);
                        }
                        break;
                    } elseif ($maxQte >= $quantite) {
                        # si la qte demandee est inferieur a la quantite max disponible en stock
                        do {
                            $idStock = $dataStock[$i]->id;
                            $qteStock = $dataStock[$i]->quantite;
                            if ($qteStock < $quantite) {
                                $quantite = $quantite - $qteStock;
                                #Reduction Stock
                                $NewQteStock = 0;
                                if ($NewQteStock >= 0) {
                                    $stockOeuf->setQuantite($NewQteStock);
                                    $stockOeuf->setUpdated_at(getSiku());
                                    $stockOeufModel->update((int)$idStock, $stockOeuf);
                                }
                            } elseif ($qteStock >= $quantite) {
                                #Reduction Stock
                                $NewQteStock = $qteStock - $quantite;
                                $quantite = 0;
                                if ($NewQteStock >= 0) {
                                    $stockOeuf->setQuantite($NewQteStock);
                                    $stockOeuf->setUpdated_at(getSiku());
                                    $stockOeufModel->update((int)$idStock, $stockOeuf);
                                }
                            }
                        } while ($quantite == 0);
                    }
                }
            } else {
                $message = "La quantite renseignée n'est pas disponible en stock";
                return success205($message);
            }
        } else {

            $message = "  Verifier la valeur de la quantite ";
            return success205($message);
        }
    } else {
        $message = "La nature du produit n'est pas un Oeuf ";
        return success205($message);
    }
}
function reduireStockPoussin($sortiePoussinsData)
{
    $stockPoussinsModel = new Stock_poussinsModel();
    $stockPoussins = $stockPoussinsModel;
    $natureID = $sortiePoussinsData['natures_idNature'];
    $quantite = (float)$sortiePoussinsData['quantite'];
    $idStock = null;
    $qteStock = null;
    $poussinNatures = getIdNaturePoussin();
    if (in_array($natureID, $poussinNatures)) {

        if ($quantite !== null && $quantite > 0) {

            $DataStockQte = array(
                "natures_idNature" => $natureID,
            );
            $dataStock = $stockPoussinsModel->findBy($DataStockQte);
            $maxQte = quantiteStockMax($dataStock);
            if ($quantite < $maxQte) {
                for ($i = 0, $size = count($dataStock); $i < $size; $i++) {

                    if ($dataStock[$i]->quantite == $quantite) {
                        # si la qte demande correspond a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockPoussins->setQuantite($NewQteStock);
                            $stockPoussins->setUpdated_at(getSiku());
                            $stockPoussinsModel->update((int)$idStock, $stockPoussins);
                        }
                        break;
                    } elseif ($dataStock[$i]->quantite >= $quantite) {
                        # si la qte demandee est inferieur a une quantite dans le stock
                        $idStock = $dataStock[$i]->id;
                        $qteStock = $dataStock[$i]->quantite;
                        #Reduction Stock
                        $NewQteStock = $qteStock - $quantite;
                        if ($NewQteStock >= 0) {

                            $stockPoussins->setQuantite($NewQteStock);
                            $stockPoussins->setUpdated_at(getSiku());
                            $stockPoussinsModel->update((int)$idStock, $stockPoussins);
                        }
                        break;
                    } elseif ($maxQte >= $quantite) {
                        # si la qte demandee est inferieur a la quantite max disponible en stock
                        do {
                            $idStock = $dataStock[$i]->id;
                            $qteStock = $dataStock[$i]->quantite;
                            if ($qteStock < $quantite) {
                                $quantite = $quantite - $qteStock;
                                #Reduction Stock
                                $NewQteStock = 0;
                                if ($NewQteStock >= 0) {
                                    $stockPoussins->setQuantite($NewQteStock);
                                    $stockPoussins->setUpdated_at(getSiku());
                                    $stockPoussinsModel->update((int)$idStock, $stockPoussins);
                                    //createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_POUSSIN);
                                }
                            } elseif ($qteStock >= $quantite) {
                                #Reduction Stock
                                $NewQteStock = $qteStock - $quantite;
                                $quantite = 0;
                                if ($NewQteStock >= 0) {
                                    $stockPoussins->setQuantite($NewQteStock);
                                    $stockPoussins->setUpdated_at(getSiku());
                                    $stockPoussinsModel->update((int)$idStock, $stockPoussins);
                                    //createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_POUSSIN);
                                }
                            }
                        } while ($quantite == 0);
                    }
                }
            } else {
                $message = "La quantite renseignée n'est pas disponible en stock";
                //createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_STOCK_POUSSIN);
                return success205($message);
            }
        } else {
            $message = " Verifier la valeur de la quantite ";
            //createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_STOCK_POUSSIN);
            return success205($message);
        }
    } else {
        $message = "La nature du produit n'est pas un Poussin ";
        //createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_STOCK_POUSSIN);
        return success205($message);
    }
}

#SORTIE

function sortiePoulet($sortiePouletsData)
{
    $sortiePouletsModel = new Sortie_pouletsModel();
    $sortiePoulets = $sortiePouletsModel;

    $natureID = $sortiePouletsData['natures_idNature'];
    $etatRID = $sortiePouletsData['etat_rapportID'];
    $adminID = $sortiePouletsData['admins_idAdmin'];
    $agentID = $sortiePouletsData['agents_idAgent'];
    $quantite = $sortiePouletsData["quantite"];
    $clientID = null;
    $motifID = null;
    $today = $sortiePouletsData["siku"];


    if ($etatRID == ETAT_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_MAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_TRES_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_TMAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_MORT) {
        $motifID = MOTIF_SORTIE_ETAT_MORT;
        $clientID = ID_CLIENT_SYSTEME;
    } else {
        $motifID = $sortiePouletsData['motifSorties_idMotif'];
        $clientID = $sortiePouletsData['clients_idClient'];
    }

    $sortiePouletsData['motifSorties_idMotif'] = $motifID;
    $sortiePouletsData['clients_idClient'] = $clientID;
    $sortiePouletsData['agents_id'] = $agentID;
    $testClient = testClientbyId($clientID);
    // $testAdmin = testAdminbyId($adminID);
    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);


    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Aliment
        reduireStockPoulet($sortiePouletsData);

        #creer la sortie
        createSortie($sortiePouletsData);
        $dataSortie = getLastSortie($sortiePouletsData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Poulet");
        } else {

            $sortiePoulets->setQuantite($quantite);
            $sortiePoulets->setSorties_idSortie($sortieID);
            $sortiePoulets->setClient_id($clientID);
            $sortiePoulets->setCreated_at($today);
            # On ajoute la Designation dans la BD
            $sortiePouletsModel->create($sortiePoulets);
            //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_ALIMENT);
        }
    }
}
function sortiePoule($sortiePoulesData)
{
    $sortiePoulesModel = new Sortie_poulesModel();
    $sortiePoules = $sortiePoulesModel;

    $natureID = $sortiePoulesData['natures_idNature'];
    $etatRID = $sortiePoulesData['etat_rapportID'];
    $adminID = $sortiePoulesData['admins_idAdmin'];
    $agentID = $sortiePoulesData['agents_idAgent'];
    $quantite = $sortiePoulesData["quantite"];
    $clientID = null;
    $motifID = null;
    $today = $sortiePoulesData["siku"];


    if ($etatRID == ETAT_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_MAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_TRES_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_TMAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_MORT) {
        $motifID = MOTIF_SORTIE_ETAT_MORT;
        $clientID = ID_CLIENT_SYSTEME;
    } else {
        $motifID = $sortiePoulesData['motifSorties_idMotif'];
        $clientID = $sortiePoulesData['clients_idClient'];
    }

    $sortiePoulesData['motifSorties_idMotif'] = $motifID;
    $sortiePoulesData['clients_idClient'] = $clientID;
    $sortiePoulesData['agents_id'] = $adminID;
    $testClient = testClientbyId($clientID);
    $testAgent = testAgentbyId($agentID);
    // $testAdmin = testAdminbyId($adminID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);


    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Aliment
        reduireStockPoule($sortiePoulesData);

        #creer la sortie
        createSortie($sortiePoulesData);
        $dataSortie = getLastSortie($sortiePoulesData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Poulet");
        } else {

            $sortiePoules->setQuantite($quantite);
            $sortiePoules->setSorties_idSortie($sortieID);
            $sortiePoules->setClient_id($clientID);
            $sortiePoules->setCreated_at($today);
            # On ajoute la Designation dans la BD
            $sortiePoulesModel->create($sortiePoules);
            //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_ALIMENT);
        }
    }
}
function sortieAliment($sortieAlimentsData)
{
    $sortieAlimentsModel = new Sortie_alimentsModel();
    $sortieAliments = $sortieAlimentsModel;

    $natureID = $sortieAlimentsData['natures_idNature'];
    $etatRID = $sortieAlimentsData['etat_rapportID'];
    $adminID = $sortieAlimentsData['admins_idAdmin'];
    $agentID = $sortieAlimentsData['agents_idAgent'];
    $quantite = $sortieAlimentsData["quantite"];
    $clientID = null;
    $motifID = null;
    $today = $sortieAlimentsData["siku"];



    if ($etatRID == ETAT_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_MAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_TRES_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_TMAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_MORT) {
        $motifID = MOTIF_SORTIE_ETAT_MORT;
        $clientID = ID_CLIENT_SYSTEME;
    } else {
        $motifID = $sortieAlimentsData['motifSorties_idMotif'];
        $clientID = $sortieAlimentsData['clients_idClient'];
    }

    $sortieAlimentsData['motifSorties_idMotif'] = $motifID;
    $sortieAlimentsData['clients_idClient'] = $clientID;
    $sortieAlimentsData['agents_id'] = $agentID;
    $testClient = testClientbyId($clientID);
    //$testAdmin = testAdminbyId($adminID);
    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);


    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Aliment
        reduireStockAliment($sortieAlimentsData);

        #creer la sortie
        createSortie($sortieAlimentsData);
        $dataSortie = getLastSortie($sortieAlimentsData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Aliment");
        } else {

            $sortieAliments->setQuantite($quantite);
            $sortieAliments->setSorties_idSortie($sortieID);
            $sortieAliments->setClient_id($clientID);
            $sortieAliments->setCreated_at($today);
            # On ajoute la Designation dans la BD
            $sortieAlimentsModel->create($sortieAliments);
            //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_ALIMENT);
        }
    }
}
function sortieBiogaz($sortieBiogazData)
{
    $sortieBiogazModel = new Sortie_oeufsModel();
    $sortieBiogaz = $sortieBiogazModel;

    // debug400('qualite MBOVU',  $sortieAlimentsData);

    $natureID = $sortieBiogazData['natures_idNature'];
    $etatRID = $sortieBiogazData['etat_rapportID'];
    $adminID = $sortieBiogazData['admins_idAdmin'];
    $agentID = $sortieBiogazData['agents_idAgent'];
    $quantite = $sortieBiogazData["quantite"];
    $clientID = null;
    $motifID = null;
    $today =  $sortieBiogazData["siku"];;

    if ($etatRID == ETAT_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_MAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_TRES_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_TMAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_MORT) {
        $motifID = MOTIF_SORTIE_ETAT_MORT;
        $clientID = ID_CLIENT_SYSTEME;
    } else {
        $clientID = $sortieBiogazData['clients_idClient'];
        $motifID  = $sortieBiogazData['motifSorties_idMotif'];
    }


    $sortieBiogazData['motifSorties_idMotif'] = $motifID;
    $sortieBiogazData['clients_idClient'] = $clientID;
    $sortieBiogazData['admins_id'] = $adminID;
    $testClient = testClientbyId($clientID);
    // $testAdmin = testAdminbyId($adminID);
    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);


    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Aliment
        reduireStockBiogaz($sortieBiogazData);

        #creer la sortie
        createSortie($sortieBiogazData);
        $dataSortie = getLastSortie($sortieBiogazData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Biogaz");
        } else {
            $sortieBiogaz->setQuantite($quantite);
            $sortieBiogaz->setSorties_idSortie($sortieID);
            $sortieBiogaz->setClient_id($clientID);
            $sortieBiogaz->setCreated_at($today);
            # On ajoute la Designation dans la BD
            $sortieBiogazModel->create($sortieBiogaz);
            //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_BIOGAZ);
        }
    }
}
function sortieOeuf($sortieOeufsData)
{
    $sortieOeufModel = new Sortie_oeufsModel();
    $sortieOeufs = $sortieOeufModel;

    $natureID = $sortieOeufsData['natures_idNature'];
    $etatRID = $sortieOeufsData['etat_rapportID'];
    $agentID = $sortieOeufsData['agents_idAgent'];
    $adminID = $sortieOeufsData['admins_idAdmin'];
    $quantite = $sortieOeufsData["quantite"];
    $clientID = null;
    $motifID = null;
    $today =  $sortieOeufsData["siku"];;

    if ($etatRID == ETAT_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_MAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_TRES_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_TMAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_MORT) {
        $motifID = MOTIF_SORTIE_ETAT_MORT;
        $clientID = ID_CLIENT_SYSTEME;
    } else {
        $motifID = $sortieOeufsData['motifSorties_idMotif'];
        $clientID = $sortieOeufsData['clients_idClient'];
    }

    $sortieOeufsData['motifSorties_idMotif'] = $motifID;
    $sortieOeufsData['clients_idClient'] = $clientID;
    $sortieOeufsData['admins_id'] = $adminID;
    $testClient = testClientbyId($clientID);
    $testAgent = testAgentbyId($agentID);
    // $testAdmin = testAdminbyId($adminID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);


    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Aliment
        reduireStockOeuf($sortieOeufsData);

        #creer la sortie
        createSortie($sortieOeufsData);
        $dataSortie = getLastSortie($sortieOeufsData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Oeufs");
        } else {

            $sortieOeufs->setQuantite($quantite);
            $sortieOeufs->setSorties_idSortie($sortieID);
            $sortieOeufs->setClient_id($clientID);
            $sortieOeufs->setCreated_at($today);
            # On ajoute la Designation dans la BD
            $sortieOeufModel->create($sortieOeufs);
            //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_OEUF);
        }
    }
}
function sortieInc($sortieIncData)
{
    $sortieIncModel = new Sortie_incubationsModel();
    $sortieIncubation = $sortieIncModel;

    $natureID = $sortieIncData['natures_idNature'];
    $etatRID = $sortieIncData['etat_rapportID'];
    $agentID = $sortieIncData['agents_idAgent'];
    $adminID = $sortieIncData['admins_idAdmin'];
    $quantite = $sortieIncData["quantite"];
    $clientID = null;
    $motifID = null;
    $today =  $sortieIncData["siku"];;

    if ($etatRID == ETAT_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_MAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_TRES_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_TMAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_MORT) {
        $motifID = MOTIF_SORTIE_ETAT_MORT;
        $clientID = ID_CLIENT_SYSTEME;
    } else {
        $motifID = $sortieIncData['motifSorties_idMotif'];
        $clientID = $sortieIncData['clients_idClient'];
    }

    $sortieIncData['motifSorties_idMotif'] = $motifID;
    $sortieIncData['clients_idClient'] = $clientID;
    $sortieIncData['admins_id'] = $adminID;
    $testClient = testClientbyId($clientID);
    $testAgent = testAgentbyId($agentID);
    // $testAdmin = testAdminbyId($adminID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);


    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Aliment
        // reduireStockOeuf($sortieIncData);
        reduireStockOeuf($sortieIncData);

        #creer la sortie
        createSortie($sortieIncData);
        $dataSortie = getLastSortie($sortieIncData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Incubation");
        } else {
            $sortieIncubation->setQuantite($quantite);
            $sortieIncubation->setSorties_idSortie($sortieID);
            $sortieIncubation->setClient_id($clientID);
            $sortieIncubation->setCreated_at($today);
            # On ajoute la Designation dans la BD
            $sortieIncModel->create($sortieIncubation);
            //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_OEUF);
        }
    }
}

function sortiePoussin($sortiePoussinsData)
{
    $sortiePoussinModel = new Sortie_poussinsModel();
    $sortiePoussins = $sortiePoussinModel;

    $natureID = $sortiePoussinsData['natures_idNature'];
    $etatRID = $sortiePoussinsData['etat_rapportID'];
    $agentID = $sortiePoussinsData['agents_idAgent'];
    $adminID = $sortiePoussinsData['admins_idAdmin'];
    $quantite = $sortiePoussinsData["quantite"];
    $clientID = null;
    $motifID = null;
    $today = $sortiePoussinsData['siku'];;

    if ($etatRID == ETAT_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_MAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_TRES_MAUVAIS) {
        $motifID = MOTIF_SORTIE_ETAT_TMAUVAIS;
        $clientID = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_MORT) {
        $motifID = MOTIF_SORTIE_ETAT_MORT;
        $clientID = ID_CLIENT_SYSTEME;
    } else {
        $motifID = $sortiePoussinsData['motifSorties_idMotif'];
        $clientID = $sortiePoussinsData['clients_idClient'];
    }


    $sortiePoussinsData['motifSorties_idMotif'] = $motifID;
    $sortiePoussinsData['agents_id'] = $adminID;
    $testClient = testClientbyId($clientID);
    // $testAdmin = testAdminbyId($adminID);
    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);


    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Aliment
        reduireStockPoussin($sortiePoussinsData);

        #creer la sortie
        createSortie($sortiePoussinsData);
        $dataSortie = getLastSortie($sortiePoussinsData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Poussin");
        } else {
            $sortiePoussins->setQuantite($quantite);
            $sortiePoussins->setSorties_idSortie($sortieID);
            $sortiePoussins->setClient_id($clientID);
            $sortiePoussins->setCreated_at($today);
            # On ajoute la Designation dans la BD
            $sortiePoussinModel->create($sortiePoussins);
            //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_POUSSIN);
        }
    }
}

function changeStatusReglement($sortieID)
{
    $sortiesModel = new SortiesModel();
    $sortie = $sortiesModel;

    $sortie->setMotifSorties_idMotif(MOTIF_SORTIE_CASH);
    $sortie->setUpdated_at(getSiku());

    $sortiesModel->update($sortieID, $sortie);
}

function getMotifCmdSortie($data, $newStatusCmdID)
{
    $etatRID = $data['etat_rapportID'];
    $montant = $data['montant'];
    $prixtotal = $data['prixtotal'];

    $response[] = null;
    if ($etatRID == ETAT_MAUVAIS) {
        $response['motifID'] = MOTIF_SORTIE_ETAT_MAUVAIS;
        $response['clientID'] = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_TRES_MAUVAIS) {
        $response['motifID'] = MOTIF_SORTIE_ETAT_TMAUVAIS;
        $response['clientID'] = ID_CLIENT_SYSTEME;
    } elseif ($etatRID == ETAT_MORT) {
        $response['motifID'] = MOTIF_SORTIE_ETAT_MORT;
        $response['clientID'] = ID_CLIENT_SYSTEME;
    } elseif (($prixtotal == $montant) && ($newStatusCmdID == STATUS_CMD_REGLE)) {
        $response['motifID'] = MOTIF_SORTIE_CASH;
        $response['clientID'] = $data['clients_idClient'];
    }

    // elseif (($newStatusCmdID == STATUS_CMD_REGLE) || (($newStatusCmdID == STATUS_CMD_PART_REGLE))) {
    //     $response['motifID'] = MOTIF_SORTIE_CREDIT;
    //     $response['clientID'] = $data['clients_idClient'];
    // }
    return $response;
}

function entreePoussin($entreePoussinsData)
{
    $entreePoussinsModel = new Entree_poussinsModel();
    $entreePoussins = $entreePoussinsModel;
    // debug400('Binakatal', $entreePoussinsData);
    # On recupere les informations venues de POST
    chargementEntreePoussins($entreePoussinsData);

    $quantite = $entreePoussinsData['quantite'];
    // $entreeID = $entreePoussinsData["entrees_idEntree"];
    // $stockPoussinID = $entreePoussinsData['stock_Poussins_idStock'];
    $today = getSiku();
    $natureID = $entreePoussinsData['natures_idNature'];
    $motifID = $entreePoussinsData['motifSorties_idMotif'];
    $date = $entreePoussinsData["date"];
    $etat = $entreePoussinsData["etat"];


    $testNature = testNaturebyId($natureID);
    $testMotif = testMotifbyId($motifID);
    $testEtat = testEtatRapportbyId($etat) && isGoodProduct($etat);

    if ($testNature && $testMotif && $testEtat) {
        #creer Entree
        createEntree($entreePoussinsData);
        $entreeData = getLastEntree($entreePoussinsData);
        $entreeID = $entreeData->id;
        if (empty($entreeData)) {
            return success205("Pas d'enregistrement de l'Entree");
        } else {
            createStockPoussin($entreePoussinsData);
            $stockPoussinData = getLastStockPoussin($entreePoussinsData);
            $stockPoussinID = $stockPoussinData->id;
            if (empty($stockPoussinData)) {
                return success205("Pas d'enregistrement du Stock Poussin");
            } else {
                $entreePoussins->setQuantite($quantite);
                $entreePoussins->setEntrees_idEntree($entreeID);
                $entreePoussins->setStock_Poussins_idStock($stockPoussinID);
                $entreePoussins->setCreated_at($today);

                # On ajoute la Designation dans la BD
                $entreePoussinsModel->create($entreePoussins);
                //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ENT_POUSSIN);
            }
        }
    }
}

function heritageRace($natureID)
{
    $heritageNature = null;
    switch ($natureID) {
        case 4: {
                $heritageNature = 7;
                break;
            }
        case 5: {
                $heritageNature = 8;
                break;
            }
        case 6: {
                $heritageNature = 9;
                break;
            }
    }
    return $heritageNature;
}



function changeStatusInc($incubateur, $statusInc)
{
    $incubationsModel = new IncubationsModel();
    $incubations = $incubationsModel;

    $incubationsID = $incubateur->id;
    $incubations->setStatus_id($statusInc);
    $incubations->setUpdated_at(getSiku());
    $incubationsModel->update($incubationsID, $incubations);
    //createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_INCUB);
}


#FILTRE RAPPORT 

#DAY FILTRE
function getListRapportPouletDataDay($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finJourne = journee();

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportPouletDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportPouleDataDay($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finJourne = journee();

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportPouleDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportAlimentDataDay($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finJourne = journee();

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportAlimentDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportBiogazDataDay($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finJourne = journee();

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportBiogazDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportIncDataDay($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finJourne = journee();

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportIncDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportOeufDataDay($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finJourne = journee();

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportOeufDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportPoussinDataDay($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finJourne = journee();

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportPoussinDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}

#MONTH FILTER
function getListRapportPouletDataMonth($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finMois = moisFin();

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportPouletDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportPouleDataMonth($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finMois = moisFin();

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportPouleDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportAlimentDataMonth($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finMois = moisFin();

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportAlimentDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportBiogazDataMonth($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finMois = moisFin();

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportBiogazDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportIncDataMonth($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finMois = moisFin();

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportIncDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportOeufDataMonth($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finMois = moisFin();

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportOeufDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportPoussinDataMonth($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finMois = moisFin();

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportPoussinDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}


#WEEK FILTER
function getListRapportPouletDataWeek($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finSemaine = semaineFin();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportPouletDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportPouleDataWeek($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finSemaine = semaineFin();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportPouleDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportAlimentDataWeek($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finSemaine = semaineFin();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportAlimentDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}

function getListRapportBiogazDataWeek($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finSemaine = semaineFin();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportBiogazDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportIncDataWeek($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finSemaine = semaineFin();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportIncDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportOeufDataWeek($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finSemaine = semaineFin();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportOeufDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportPoussinDataWeek($listEntrees)
{
    $dataAll = array();

    for ($i = 0, $size = count($listEntrees); $i < $size; ++$i) {
        $date = $listEntrees[$i]->date;
        $finSemaine = semaineFin();

        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            $dataAll[] = getRapportPoussinDataById($listEntrees[$i]->id);
        }
    }
    return $dataAll;
}

#YEAR FILTER
function getListRapportPouletDataYear($listRapportAliment, $year)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportAliment); $i < $size; ++$i) {
        $date = $listRapportAliment[$i]->date;
        $annee =  date('Y', strtotime($date));

        if ($year == $annee) {
            $dataAll[] = getRapportPouletDataById($listRapportAliment[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportPouleDataYear($listRapportAliment, $year)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportAliment); $i < $size; ++$i) {
        $date = $listRapportAliment[$i]->date;
        $annee =  date('Y', strtotime($date));

        if ($year == $annee) {
            $dataAll[] = getRapportPouleDataById($listRapportAliment[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportAlimentDataYear($listRapportAliment, $year)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportAliment); $i < $size; ++$i) {
        $date = $listRapportAliment[$i]->date;
        $annee =  date('Y', strtotime($date));


        if ($year == $annee) {
            $dataAll[] = getRapportAlimentDataById($listRapportAliment[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportBiogazDataYear($listRapportAliment, $year)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportAliment); $i < $size; ++$i) {
        $date = $listRapportAliment[$i]->date;
        $annee =  date('Y', strtotime($date));


        if ($year == $annee) {
            $dataAll[] = getRapportBiogazDataById($listRapportAliment[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportIncDataYear($listRapportAliment, $year)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportAliment); $i < $size; ++$i) {
        $date = $listRapportAliment[$i]->date;
        $annee =  date('Y', strtotime($date));


        if ($year == $annee) {
            $dataAll[] = getRapportIncDataById($listRapportAliment[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportOeufDataYear($listRapportAliment, $year)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportAliment); $i < $size; ++$i) {
        $date = $listRapportAliment[$i]->date;
        $annee =  date('Y', strtotime($date));


        if ($year == $annee) {
            $dataAll[] = getRapportOeufDataById($listRapportAliment[$i]->id);
        }
    }
    return $dataAll;
}
function getListRapportPoussinDataYear($listRapportAliment, $year)
{
    $dataAll = array();
    for ($i = 0, $size = count($listRapportAliment); $i < $size; ++$i) {
        $date = $listRapportAliment[$i]->date;
        $annee =  date('Y', strtotime($date));


        if ($year == $annee) {
            $dataAll[] = getRapportPoussinDataById($listRapportAliment[$i]->id);
        }
    }
    return $dataAll;
}

function getRapport()
{
    $rapport = array();
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = (array)$rapportAlimentsModel->findAll();

    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = (array)$rapportBiogazModel->findAll();

    $rapportOeufModel = new Rapport_oeufsModel();
    $rapportOeuf = (array)$rapportOeufModel->findAll();

    $rapportPoussinModel = new Rapport_poussinsModel();
    $rapportPoussin = (array)$rapportPoussinModel->findAll();
    $rapport['aliment'] = $rapportAliments;
    $rapport['biogaz'] = $rapportBiogaz;
    $rapport['oeuf'] = $rapportOeuf;
    $rapport['poussin'] = $rapportPoussin;

    return $rapport;
}
function getRapportByAgentID($idAgent)
{
    $agentID = array(
        "agents_idAgent" => $idAgent,
    );

    $rapport = array();
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = (array)$rapportAlimentsModel->findBy($agentID);

    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = (array)$rapportBiogazModel->findBy($agentID);

    $rapportOeufModel = new Rapport_oeufsModel();
    $rapportOeuf = (array)$rapportOeufModel->findBy($agentID);

    $rapportPoussinModel = new Rapport_poussinsModel();
    $rapportPoussin = (array)$rapportPoussinModel->findBy($agentID);
    $rapport['aliment'] = $rapportAliments;
    $rapport['biogaz'] = $rapportBiogaz;
    $rapport['oeuf'] = $rapportOeuf;
    $rapport['poussin'] = $rapportPoussin;

    return $rapport;
}
function getRapportData()
{
    $dataAll = array();
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = (array)$rapportAlimentsModel->findAll();

    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = (array)$rapportBiogazModel->findAll();

    $rapportOeufModel = new Rapport_oeufsModel();
    $rapportOeuf = (array)$rapportOeufModel->findAll();

    $rapportPoussinModel = new Rapport_poussinsModel();
    $rapportPoussin = (array)$rapportPoussinModel->findAll();

    for ($i = 0, $size = count($rapportAliments); $i < $size; ++$i) {
        array_push($dataAll, getRapportAlimentDataById($rapportAliments[$i]->id));
    }
    for ($i = 0, $size = count($rapportBiogaz); $i < $size; ++$i) {
        array_push($dataAll, getRapportBiogazDataById($rapportBiogaz[$i]->id));
    }
    for ($i = 0, $size = count($rapportOeuf); $i < $size; ++$i) {
        array_push($dataAll, getRapportOeufDataById($rapportOeuf[$i]->id));
    }
    for ($i = 0, $size = count($rapportPoussin); $i < $size; ++$i) {
        array_push($dataAll, getRapportPoussinDataById($rapportPoussin[$i]->id));
    }

    return $dataAll;
}
function getRapportDataByAgentID($idAgent)
{
    $dataAll = array();
    $agentID = array(
        "agents_idAgent" => $idAgent,
    );

    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = (array)$rapportAlimentsModel->findBy($agentID);

    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = (array)$rapportBiogazModel->findBy($agentID);

    $rapportOeufModel = new Rapport_oeufsModel();
    $rapportOeuf = (array)$rapportOeufModel->findBy($agentID);

    $rapportPoussinModel = new Rapport_poussinsModel();
    $rapportPoussin = (array)$rapportPoussinModel->findBy($agentID);

    for ($i = 0, $size = count($rapportAliments); $i < $size; ++$i) {
        array_push($dataAll, getRapportAlimentDataById($rapportAliments[$i]->id));
    }
    for ($i = 0, $size = count($rapportBiogaz); $i < $size; ++$i) {
        array_push($dataAll, getRapportBiogazDataById($rapportBiogaz[$i]->id));
    }
    for ($i = 0, $size = count($rapportOeuf); $i < $size; ++$i) {
        array_push($dataAll, getRapportOeufDataById($rapportOeuf[$i]->id));
    }
    for ($i = 0, $size = count($rapportPoussin); $i < $size; ++$i) {
        array_push($dataAll, getRapportPoussinDataById($rapportPoussin[$i]->id));
    }

    return $dataAll;
}


function getListRapportDataYear($rapportAlimentsParams)
{
    $rapportData = getRapport();
    $aliments = $rapportData['aliment'];
    $biogaz = $rapportData['biogaz'];
    $oeufs = $rapportData['oeuf'];
    $poussins = $rapportData['poussin'];

    $year = $rapportAlimentsParams['year'];

    $dataAll = array();

    $size = count((array)$aliments);

    #Aliment
    for ($i = 0; $i < $size; $i++) {
        # code...
        $date = $aliments[$i]->date;
        $annee =  date('Y', strtotime($date));
        if ($year == $annee) {
            array_push($dataAll, getRapportAlimentDataById($aliments[$i]->id));
        }
    }

    $size1 = count((array)$biogaz);
    #Biogaz
    for ($i = 0; $i < $size1; $i++) {
        # code...
        $date = $biogaz[$i]->date;
        $annee =  date('Y', strtotime($date));
        if ($year == $annee) {
            array_push($dataAll, getRapportBiogazDataById($biogaz[$i]->id));
        }
    }

    $size2 = count((array)$oeufs);
    #Oeuf
    for ($i = 0; $i < $size2; $i++) {
        # code...
        $date = $oeufs[$i]->date;
        $annee =  date('Y', strtotime($date));
        if ($year == $annee) {
            array_push($dataAll, getRapportOeufDataById($oeufs[$i]->id));
        }
    }


    $size3 = count((array)$poussins);
    #Poussin
    for ($i = 0; $i < $size3; $i++) {
        # code...
        $date = $poussins[$i]->date;
        $annee =  date('Y', strtotime($date));
        if ($year == $annee) {
            array_push($dataAll, getRapportPoussinDataById($poussins[$i]->id));
        }
    }
    return $dataAll;
}
function getListRapportDataYearByAgentID($rapportAlimentsParams, $idAgent)
{
    $rapportData = getRapportByAgentID($idAgent);
    $aliments = $rapportData['aliment'];
    $biogaz = $rapportData['biogaz'];
    $oeufs = $rapportData['oeuf'];
    $poussins = $rapportData['poussin'];

    $year = $rapportAlimentsParams['year'];

    $dataAll = array();

    $size = count((array)$aliments);

    #Aliment
    for ($i = 0; $i < $size; $i++) {
        # code...
        $date = $aliments[$i]->date;
        $annee =  date('Y', strtotime($date));
        if ($year == $annee) {
            array_push($dataAll, getRapportAlimentDataById($aliments[$i]->id));
        }
    }

    $size1 = count((array)$biogaz);
    #Biogaz
    for ($i = 0; $i < $size1; $i++) {
        # code...
        $date = $biogaz[$i]->date;
        $annee =  date('Y', strtotime($date));
        if ($year == $annee) {
            array_push($dataAll, getRapportBiogazDataById($biogaz[$i]->id));
        }
    }

    $size2 = count((array)$oeufs);
    #Oeuf
    for ($i = 0; $i < $size2; $i++) {
        # code...
        $date = $oeufs[$i]->date;
        $annee =  date('Y', strtotime($date));
        if ($year == $annee) {
            array_push($dataAll, getRapportOeufDataById($oeufs[$i]->id));
        }
    }


    $size3 = count((array)$poussins);
    #Poussin
    for ($i = 0; $i < $size3; $i++) {
        # code...
        $date = $poussins[$i]->date;
        $annee =  date('Y', strtotime($date));
        if ($year == $annee) {
            array_push($dataAll, getRapportPoussinDataById($poussins[$i]->id));
        }
    }
    return $dataAll;
}

function getListRapportDataWeek()
{
    $dataAll = array();
    $finSemaine = semaineFin();
    $rapportData = getRapport();
    $aliments = $rapportData['aliment'];
    $biogaz = $rapportData['biogaz'];
    $oeufs = $rapportData['oeuf'];
    $poussins = $rapportData['poussin'];

    #aliment
    for ($i = 0, $size = count($aliments); $i < $size; ++$i) {
        $date = $aliments[$i]->date;
        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportAlimentDataById($aliments[$i]->id));
        }
    }

    #Biogaz
    for ($i = 0, $size = count($biogaz); $i < $size; ++$i) {
        $date = $biogaz[$i]->date;
        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportBiogazDataById($biogaz[$i]->id));
        }
    }
    #Oeuf
    for ($i = 0, $size = count($oeufs); $i < $size; ++$i) {
        $date = $oeufs[$i]->date;
        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportOeufDataById($oeufs[$i]->id));
        }
    }
    #Poussin
    for ($i = 0, $size = count($poussins); $i < $size; ++$i) {
        $date = $poussins[$i]->date;
        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportPoussinDataById($poussins[$i]->id));
        }
    }
    return $dataAll;
}
function getListRapportDataWeekByAgentID($idAgent)
{
    $dataAll = array();
    $finSemaine = semaineFin();
    $rapportData = getRapportByAgentID($idAgent);
    $aliments = $rapportData['aliment'];
    $biogaz = $rapportData['biogaz'];
    $oeufs = $rapportData['oeuf'];
    $poussins = $rapportData['poussin'];

    #aliment
    for ($i = 0, $size = count($aliments); $i < $size; ++$i) {
        $date = $aliments[$i]->date;
        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportAlimentDataById($aliments[$i]->id));
        }
    }

    #Biogaz
    for ($i = 0, $size = count($biogaz); $i < $size; ++$i) {
        $date = $biogaz[$i]->date;
        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportBiogazDataById($biogaz[$i]->id));
        }
    }
    #Oeuf
    for ($i = 0, $size = count($oeufs); $i < $size; ++$i) {
        $date = $oeufs[$i]->date;
        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportOeufDataById($oeufs[$i]->id));
        }
    }
    #Poussin
    for ($i = 0, $size = count($poussins); $i < $size; ++$i) {
        $date = $poussins[$i]->date;
        if ((strtotime($date) >= $finSemaine) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportPoussinDataById($poussins[$i]->id));
        }
    }
    return $dataAll;
}

function getListRapportDataMonth()
{
    $dataAll = array();
    $finMois = moisFin();
    $rapportData = getRapport();
    $aliments = $rapportData['aliment'];
    $biogaz = $rapportData['biogaz'];
    $oeufs = $rapportData['oeuf'];
    $poussins = $rapportData['poussin'];

    #Aliment
    for ($i = 0, $size = count($aliments); $i < $size; ++$i) {
        $date = $aliments[$i]->date;

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportAlimentDataById($aliments[$i]->id));
        }
    }

    #Biogaz
    for ($i = 0, $size = count($biogaz); $i < $size; ++$i) {
        $date = $biogaz[$i]->date;

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportBiogazDataById($biogaz[$i]->id));
        }
    }

    #Oeuf
    for ($i = 0, $size = count($oeufs); $i < $size; ++$i) {
        $date = $oeufs[$i]->date;

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportOeufDataById($oeufs[$i]->id));
        }
    }

    #Poussin
    for ($i = 0, $size = count($poussins); $i < $size; ++$i) {
        $date = $poussins[$i]->date;

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportPoussinDataById($poussins[$i]->id));
        }
    }
    return $dataAll;
}
function getListRapportDataMonthByAgentID($idAgent)
{
    $dataAll = array();
    $finMois = moisFin();
    $rapportData = getRapportByAgentID($idAgent);
    $aliments = $rapportData['aliment'];
    $biogaz = $rapportData['biogaz'];
    $oeufs = $rapportData['oeuf'];
    $poussins = $rapportData['poussin'];

    #Aliment
    for ($i = 0, $size = count($aliments); $i < $size; ++$i) {
        $date = $aliments[$i]->date;

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportAlimentDataById($aliments[$i]->id));
        }
    }

    #Biogaz
    for ($i = 0, $size = count($biogaz); $i < $size; ++$i) {
        $date = $biogaz[$i]->date;

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportBiogazDataById($biogaz[$i]->id));
        }
    }

    #Oeuf
    for ($i = 0, $size = count($oeufs); $i < $size; ++$i) {
        $date = $oeufs[$i]->date;

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportOeufDataById($oeufs[$i]->id));
        }
    }

    #Poussin
    for ($i = 0, $size = count($poussins); $i < $size; ++$i) {
        $date = $poussins[$i]->date;

        if ((strtotime($date) >= $finMois) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportPoussinDataById($poussins[$i]->id));
        }
    }
    return $dataAll;
}

function getListRapportDataDay()
{
    $dataAll = array();
    $finJourne = journee();
    $rapportData = getRapport();
    $aliments = $rapportData['aliment'];
    $biogaz = $rapportData['biogaz'];
    $oeufs = $rapportData['oeuf'];
    $poussins = $rapportData['poussin'];

    for ($i = 0, $size = count($aliments); $i < $size; ++$i) {
        $date = $aliments[$i]->date;

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportAlimentDataById($aliments[$i]->id));
        }
    }

    for ($i = 0, $size = count($biogaz); $i < $size; ++$i) {
        $date = $biogaz[$i]->date;

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportBiogazDataById($biogaz[$i]->id));
        }
    }

    for ($i = 0, $size = count($oeufs); $i < $size; ++$i) {
        $date = $oeufs[$i]->date;

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportOeufDataById($oeufs[$i]->id));
        }
    }

    for ($i = 0, $size = count($poussins); $i < $size; ++$i) {
        $date = $poussins[$i]->date;

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportPoussinDataById($poussins[$i]->id));
        }
    }
    return $dataAll;
}
function getListRapportDataDayByAgentID($idAgent)
{
    $dataAll = array();
    $finJourne = journee();
    $rapportData = getRapportByAgentID($idAgent);
    $aliments = $rapportData['aliment'];
    $biogaz = $rapportData['biogaz'];
    $oeufs = $rapportData['oeuf'];
    $poussins = $rapportData['poussin'];

    for ($i = 0, $size = count($aliments); $i < $size; ++$i) {
        $date = $aliments[$i]->date;

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportAlimentDataById($aliments[$i]->id));
        }
    }

    for ($i = 0, $size = count($biogaz); $i < $size; ++$i) {
        $date = $biogaz[$i]->date;

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportBiogazDataById($biogaz[$i]->id));
        }
    }

    for ($i = 0, $size = count($oeufs); $i < $size; ++$i) {
        $date = $oeufs[$i]->date;

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportOeufDataById($oeufs[$i]->id));
        }
    }

    for ($i = 0, $size = count($poussins); $i < $size; ++$i) {
        $date = $poussins[$i]->date;

        if ((strtotime($date) >= $finJourne) && (strtotime($date) <= formatToday())) {
            array_push($dataAll, getRapportPoussinDataById($poussins[$i]->id));
        }
    }
    return $dataAll;
}

function quantiteStockMax($stockProduit)
{
    $qteMax = 0;
    for ($i = 0, $size = count($stockProduit); $i < $size; $i++) {
        # code...
        $qteMax = $qteMax + $stockProduit[$i]->quantite;
    }
    return $qteMax;
}


function getUserOnline()
{
    require 'php-jwt/authentification.php';
    require_once 'php-jwt/classes/JWT.php';
    $jwt = new JWT();


    $payload = authentification();
    $user = (array)json_decode($payload);
    // $id = $user['id'];
    // $role = $user['role'];
    return $user;
}


function createActivity($typeOpId, $statusOpId, $tableId)
{
    $payload = authentification();
    $user = (array)json_decode($payload);
    $id = $user['id'];
    $role = $user['role'];

    $journalActivitesModel = new Journal_activitesModel();
    $journalActivite = $journalActivitesModel;

    $userId = $id;
    $roleId = getRoleId($role);

    # On recupere les informations venues de POST
    $journalActivite->setUser_id($userId);
    $journalActivite->setRole_id($roleId);
    $journalActivite->setType_op_id($typeOpId);
    $journalActivite->setStatus_op_id($statusOpId);
    $journalActivite->setTable_id($tableId);
    $journalActivite->setCreated_at(getSiku());

    # On ajoute la Designation dans la BD
    $journalActivitesModel->create($journalActivite);
    //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ACTIVITY);
}
function logActivity($user, $typeOpId, $statusOpId, $tableId)
{

    $id = $user['id'];
    $role = $user['role'];
    $journalActivitesModel = new Journal_activitesModel();
    $journalActivite = $journalActivitesModel;

    $userId = $id;
    $roleId = getRoleId($role);

    # On recupere les informations venues de POST
    $journalActivite->setUser_id($userId);
    $journalActivite->setRole_id($roleId);
    $journalActivite->setType_op_id($typeOpId);
    $journalActivite->setStatus_op_id($statusOpId);
    $journalActivite->setTable_id($tableId);
    $journalActivite->setCreated_at(getSiku());

    # On ajoute la Designation dans la BD
    $journalActivitesModel->create($journalActivite);
    //createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ACTIVITY);
}

function getIdNatureAliment()
{
    $naturesModel = new NaturesModel();
    $data = array(
        "cat_produit_id	" => CAT_PRO_ALIMENT,
    );
    $naturesAliment = (array)$naturesModel->findBy($data);

    $dataAll = array();

    for ($i = 0, $size = count($naturesAliment); $i < $size; ++$i) {
        $dataAll[$i] = $naturesAliment[$i]->id;
    }
    return $dataAll;
}
function getIdNatureBiogaz()
{
    $naturesModel = new NaturesModel();
    $data = array(
        "cat_produit_id	" => CAT_PRO_BIOGAZ,
    );
    $naturesAliment = (array)$naturesModel->findBy($data);

    $dataAll = array();

    for ($i = 0, $size = count($naturesAliment); $i < $size; ++$i) {
        $dataAll[$i] = $naturesAliment[$i]->id;
    }
    return $dataAll;
}
function getIdNatureOeuf()
{
    $naturesModel = new NaturesModel();
    $data = array(
        "cat_produit_id	" => CAT_PRO_OEUF,
    );
    $naturesAliment = (array)$naturesModel->findBy($data);

    $dataAll = array();

    for ($i = 0, $size = count($naturesAliment); $i < $size; ++$i) {
        $dataAll[$i] = $naturesAliment[$i]->id;
    }
    return $dataAll;
}
function getIdNaturePoussin()
{
    $naturesModel = new NaturesModel();
    $data = array(
        "cat_produit_id	" => CAT_PRO_POUSSIN,
    );
    $naturesAliment = (array)$naturesModel->findBy($data);

    $dataAll = array();

    for ($i = 0, $size = count($naturesAliment); $i < $size; ++$i) {
        $dataAll[$i] = $naturesAliment[$i]->id;
    }
    return $dataAll;
}
function getIdNaturePoule()
{
    $naturesModel = new NaturesModel();
    $data = array(
        "cat_produit_id	" => CAT_PRO_POULE,
    );
    $naturesAliment = (array)$naturesModel->findBy($data);

    $dataAll = array();

    for ($i = 0, $size = count($naturesAliment); $i < $size; ++$i) {
        $dataAll[$i] = $naturesAliment[$i]->id;
    }
    return $dataAll;
}
function getIdNaturePoulet()
{
    $naturesModel = new NaturesModel();
    $data = array(
        "cat_produit_id	" => CAT_PRO_POULET,
    );
    $naturesAliment = (array)$naturesModel->findBy($data);

    $dataAll = array();

    for ($i = 0, $size = count($naturesAliment); $i < $size; ++$i) {
        $dataAll[$i] = $naturesAliment[$i]->id;
    }
    return $dataAll;
}



function natureVerify($natureID, $designation)
{
    $aliment = getIdNatureAliment();
    $biogaz = getIdNatureBiogaz();
    $oeuf = getIdNatureOeuf();
    $poussin = getIdNaturePoussin();
    $poule = getIdNaturePoule();
    $poulet = getIdNaturePoulet();


    switch ($designation) {
        case DESIGN_ALIMENT: {

                if (!in_array($natureID, $aliment)) {

                    return error422("La nature du produit n'est pas " . $designation);
                }
                break;
            }

        case DESIGN_BIOGAZ: {
                if (!in_array($natureID, $biogaz)) {
                    return error422("La nature du produit n'est pas " . $designation);
                }
                break;
            }
        case DESIGN_POUSSIN: {
                if (!in_array($natureID, $poussin)) {
                    return error422("La nature du produit n'est pas " . $designation);
                }
                break;
            }
        case DESIGN_OEUF: {
                if (!in_array($natureID, $oeuf)) {
                    return error422("La nature du produit n'est pas " . $designation);
                }
                break;
            }
        case DESIGN_POULE: {
                if (!in_array($natureID, $poule)) {
                    return error422("La nature du produit n'est pas " . $designation);
                }
                break;
            }
        case DESIGN_POULET: {
                if (!in_array($natureID, $poulet)) {
                    return error422("La nature du produit n'est pas " . $designation);
                }
                break;
            }
    }
}


function getAgentOnlineID()
{
    $payload = authentification();
    $user = (array)json_decode($payload);
    $id = $user['id'];
    // $role = $user['role'];

    // $userId = $id;
    // $roleId = getRoleId($role);

    return $id;
}

function testStatutRapport($statusRapportID)
{
    $statusRapportModel = new Status_rapportsModel();
    if ($statusRapportID !== null) {
        $data = $statusRapportModel->find($statusRapportID);
        if (!empty($data) && (in_array($statusRapportID, STATUS_MODIFIABLE))) {

            $test = true;
            return $test;
        } else {
            $message = "Ce rapport ne peut pas etre modifier ";
            return success205($message);
        }
    } else {
        $message = "Veuillez renseignée le status du rapport ";
        return success205($message);
    }
}
function testStatusInc($statusIncID)
{
    $statusIncModel = new Status_incubationsModel();
    if ($statusIncID !== null) {
        $data = $statusIncModel->find($statusIncID);
        if (!empty($data) && (in_array($statusIncID, STATUS_INC_UPDATED))) {

            $test = true;
            return $test;
        } else {
            $message = "Ce lot est deja sortie de l'incubateur ";
            return success205($message);
        }
    } else {
        $message = "Veuillez renseignée le status du produit en incubation ";
        return success205($message);
    }
}

function reduireStockIncubateur($incubationData)
{
    $IncModel = new IncubationsModel();
    $incubations = $IncModel;
    $natureID = $incubationData['natures_idNature'];
    $quantite = (float)$incubationData['quantite'];
    $oeufNatures = getIdNatureOeuf();
    $incubationID = $incubationData['incubation_id'];



    if (in_array($natureID,  $oeufNatures)) {

        if ($quantite !== null && $quantite > 0) {

            $dataStock = $IncModel->find($incubationID);
            $qteActuelle = $dataStock->quantite;
            $qteNouvelle = $qteActuelle - $quantite;
            $incubations->setQuantite($qteNouvelle);
            $incubations->setUpdated_at(getSiku());
            //debug400('Ime rudiya MBOVU', $incubationData);
            $IncModel->update((int)$incubationID, $incubations);
        } else {
            $message = "  Verifier la valeur de la quantite ";
            return success205($message);
        }
    } else {
        $message = "La nature du produit n'est pas un Oeuf ";
        return success205($message);
    }
}

function getDataIncubationById($incubation_id)
{
    $incubationsModel = new IncubationsModel();
    $dataInc = $incubationsModel->find($incubation_id);
    return $dataInc;
}

/**
 * Summary of getCommandeProduit
 * @param mixed $idCmdeClient
 * @return $arrayName = array();
 */
function getCommandeProduit($idCmdeClient)
{
    $data = null;
    $commandeAlimentModel = new Commande_alimentsModel();
    $commandeBiogazModel = new Commande_biogazModel();
    $commandeOeufModel = new Commande_oeufsModel();
    $commandePouleModel = new Commande_poulesModel();
    $commandePouletModel = new Commande_pouletsModel();
    $commandePoussinModel = new Commande_poussinsModel();

    $cmdClient = array(
        "commandeClients_idCommande" => $idCmdeClient
    );

    $dataCmdAliment = $commandeAlimentModel->findBy($cmdClient);
    $dataCmdBiogaz = (array)$commandeBiogazModel->findBy($cmdClient);
    $dataCmdOeuf = (array)$commandeOeufModel->findBy($cmdClient);
    $dataCmdPoule = (array)$commandePouleModel->findBy($cmdClient);
    $dataCmdPoulet = (array)$commandePouletModel->findBy($cmdClient);
    $dataCmdPoussin = (array)$commandePoussinModel->findBy($cmdClient);

    if (!empty($dataCmdAliment) && ($dataCmdAliment[0]->commandeClients_idCommande == $idCmdeClient)) {
        $data =   $dataCmdAliment;
    } elseif (!empty($dataCmdBiogaz) && ($dataCmdBiogaz[0]->commandeClients_idCommande == $idCmdeClient)) {
        $data =  $dataCmdBiogaz;
    } elseif (!empty($dataCmdOeuf) && ($dataCmdOeuf[0]->commandeClients_idCommande == $idCmdeClient)) {
        $data = $dataCmdOeuf;
    } elseif (!empty($dataCmdPoule) && ($dataCmdPoule[0]->commandeClients_idCommande == $idCmdeClient)) {
        $data = $dataCmdPoule;
    } elseif (!empty($dataCmdPoulet) && ($dataCmdPoulet[0]->commandeClients_idCommande == $idCmdeClient)) {
        $data = $dataCmdPoulet;
    } elseif (!empty($dataCmdPoussin) && ($dataCmdPoussin[0]->commandeClients_idCommande == $idCmdeClient)) {
        $data = $dataCmdPoussin;
    }
    // debug400("test aliment",  $dataCmdPoulet);
    return $data[0];
}

function cumulMontant($newMontant, $oldMontant, $prixTotal)
{
    $solde = 0;
    $montant = 0;
    $solde = $prixTotal - $oldMontant;
    // debug400("Solde", $solde);
    if ($oldMontant == $prixTotal) {
        $montant = $oldMontant;
    } elseif ($solde > $newMontant) {
        $montant = $oldMontant + $newMontant;
        // debug400("montant", $montant);
    } elseif ($solde == $newMontant) {
        $montant = $prixTotal;
    } elseif ($solde < $newMontant) {
        $montant = $prixTotal;
    }
    return reduireChiffre($montant);
}

function VerifyQteStockAliment($natureID, $quantite)
{
    $stockAlimentModel = new Stock_alimentsModel();
    $isAvailable = FALSE;
    $DataStockQte = array(
        "natures_idNature" => $natureID,
    );
    $dataStock = $stockAlimentModel->findBy($DataStockQte);
    $maxQte = quantiteStockMax($dataStock);
    if ($quantite < $maxQte) {
        $isAvailable = true;
        return $isAvailable;
    } else {
        $message = "La quantite renseignée n'est pas disponible en stock";
        return success205($message);
    }
}
function VerifyQteStockBiogaz($natureID, $quantite)
{
    $stockBiogazModel = new Stock_biogazModel();
    $isAvailable = FALSE;
    $DataStockQte = array(
        "natures_idNature" => $natureID,
    );
    $dataStock = $stockBiogazModel->findBy($DataStockQte);
    $maxQte = quantiteStockMax($dataStock);
    if ($quantite < $maxQte) {
        $isAvailable = true;
        return $isAvailable;
    } else {
        $message = "La quantite renseignée n'est pas disponible en stock";
        return success205($message);
    }
}
function VerifyQteStockOeuf($natureID, $quantite)
{
    $stockOeufModel = new Stock_oeufsModel();
    $isAvailable = FALSE;
    $DataStockQte = array(
        "natures_idNature" => $natureID,
    );
    $dataStock = $stockOeufModel->findBy($DataStockQte);
    $maxQte = quantiteStockMax($dataStock);
    if ($quantite < $maxQte) {
        $isAvailable = true;
        return $isAvailable;
    } else {
        $message = "La quantite renseignée n'est pas disponible en stock";
        return success205($message);
    }
}
function VerifyQteStockPoule($natureID, $quantite)
{
    $stockPouleModel = new Stock_poulesModel();
    $isAvailable = FALSE;
    $DataStockQte = array(
        "natures_idNature" => $natureID,
    );
    $dataStock = $stockPouleModel->findBy($DataStockQte);
    $maxQte = quantiteStockMax($dataStock);
    if ($quantite < $maxQte) {
        $isAvailable = true;
        return $isAvailable;
    } else {
        $message = "La quantite renseignée n'est pas disponible en stock";
        return success205($message);
    }
}
function VerifyQteStockPoulet($natureID, $quantite)
{
    $stockPouletModel = new Stock_pouletsModel();
    $isAvailable = FALSE;
    $DataStockQte = array(
        "natures_idNature" => $natureID,
    );
    $dataStock = $stockPouletModel->findBy($DataStockQte);
    $maxQte = quantiteStockMax($dataStock);
    if ($quantite < $maxQte) {
        $isAvailable = true;
        return $isAvailable;
    } else {
        $message = "La quantite renseignée n'est pas disponible en stock";
        return success205($message);
    }
}
function VerifyQteStockPoussin($natureID, $quantite)
{
    $stockPoussinModel = new Stock_poussinsModel();
    $isAvailable = FALSE;
    $DataStockQte = array(
        "natures_idNature" => $natureID,
    );
    $dataStock = $stockPoussinModel->findBy($DataStockQte);
    $maxQte = quantiteStockMax($dataStock);
    if ($quantite < $maxQte) {
        $isAvailable = true;
        return $isAvailable;
    } else {
        $message = "La quantite renseignée n'est pas disponible en stock";
        return success205($message);
    }
}

function createStatutCommande($montant)
{

    $statusID = null;
    if ($montant == 0) {
        $statusID = STATUS_CMD_DEFAUT;
    } else {
        $statusID = STATUS_CMD_RESERVE;
    }
    return   $statusID;
}