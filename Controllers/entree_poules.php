<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Entree_poulesModel;


Autoloader::register();

# Store
function storeEntreePoules($entreePoulesData)
{
    $entreePoulesModel = new Entree_poulesModel();
    $entreePoules = $entreePoulesModel;

    # On recupere les informations venues de POST
    chargementEntreeAliments($entreePoulesData);
    $entreePoulesData["etat"] = ETAT_BON;
    $entreePoulesData['motifSorties_idMotif'] = MOTIF_ENTREE_CASH;
    #Entree
    $natureID = $entreePoulesData['natures_idNature'];
    natureVerify($natureID, DESIGN_POULE);
    $motifID = $entreePoulesData['motifSorties_idMotif'];
    $quantite = $entreePoulesData['quantite'];
    $fournisseurID = $entreePoulesData['fournisseur_id'];

    #Stock Poule
    $etat = $entreePoulesData["etat"];
    $today = getSiku();
    $entreePoulesData["date"] = $today;
    $testNature = testNaturebyId($natureID);
    $testMotif = testMotifbyId($motifID);
    $testEtat = testEtatRapportbyId($etat) && isGoodProduct($etat);
    $testFour = testFournisseurbyId($fournisseurID);

    if ($testNature && $testMotif && $testEtat &&  $testFour) {
        # Creer Entree
        createEntree($entreePoulesData);
        $entreeData = getLastEntree($entreePoulesData);
        $entreeID = $entreeData->id;

        if (empty($entreeData)) {
            return success205("Pas d'enregistrement de l'Entree");
        } else {
            #Creer Stock Poule
            createStockPoule($entreePoulesData);
            $stockAlimentData = getLastStockPoule($entreePoulesData);
            $stockPouleID =  $stockAlimentData->id;



            if (empty($stockPouleID)) {
                return success205("Pas d'enregistrement Stock Poule");
            } else {
                $entreePoules->setQuantite($quantite);
                $entreePoules->setEntrees_idEntree($entreeID);
                $entreePoules->setStock_Poules_idStock($stockPouleID);
                $entreePoules->setCreated_at($today);

                $entreePoulesModel->create($entreePoules);
                createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ENT_POULE);
                $message = "Entree Poule  created successfully";
                return success201($message);
            }
        }
    }
}

#Delete
function deleteEntreePoules($entreePoulesParams)
{
    $entreePoulesModel = new Entree_poulesModel();
    paramsVerify($entreePoulesParams, "Entree Poule");

    $entreePouleFound = $entreePoulesParams['id'];
    $entreePoulesData = $entreePoulesModel->find($entreePouleFound);

    if ($entreePouleFound == $entreePoulesData->id) {
        $res = $entreePoulesModel->delete($entreePouleFound);
        $message = "Entree Poule deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ENT_POULE);
        return success200($message);
    } else {
        $message = "Entree Poule not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_ENT_POULE);
        return success205($message);
    }
}

#Get
function getEntreePouleById($entreePoulesParams)
{
    $entreePoulesModel = new Entree_poulesModel();
    paramsVerify($entreePoulesParams, "Entree Poule");

    $entreePouleFound = $entreePoulesModel->find($entreePoulesParams['id']);

    if (!empty($entreePouleFound)) {
        $dataEP = getEntreePouleDataById($entreePouleFound->id);
        $message = "Entree Poule Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ENT_POULE);
        return datasuccess200($message, $dataEP);
    } else {
        $message = "No Entree Poule Found";
        return success205($message);
    }
}

function getListEntreePoules()
{
    $entreePoulesModel = new Entree_poulesModel();
    $entreePoules = (array)$entreePoulesModel->findAll();

    if (!empty($entreePoules)) {
        $dataListEP = getListEntreePouleData($entreePoules);
        $message = "Situation Entree Poule";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENT_POULE);
        return dataTableSuccess200($message, $dataListEP);
    } else {
        $message = "Pas de situation dans le Entree Poule";
        return success205($message);
    }
}

# Update
function updateEntreePoules($entreePoulesData, $entreePoulesParams)
{
    $entreePoulesModel = new Entree_poulesModel();
    $entreePoules = $entreePoulesModel;
    paramsVerify($entreePoulesParams, "Entree Poule");

    # On recupere les informations venues de POST
    $entreePoulesID = $entreePoulesParams['id'];
    $etat = $entreePoulesData["etat"];

    $quantite = $entreePoulesData['quantite'];
    $stockPouleID = $entreePoulesData['stock_Poules_idStock'];
    $today = getSiku();

    $entreePouleFound = $entreePoulesModel->find($entreePoulesID);
    if ($entreePoulesID == $entreePouleFound->id) {
        $entreeID = $entreePouleFound->entrees_idEntree;
        updateEntree($entreePoulesData, $entreeID);

        $stockPouleID = $entreePouleFound->stock_Poules_idStock;
        updateStockPoule($entreePoulesData, $stockPouleID);
        $entreePoules->setQuantite($quantite);
        $entreePoules->setUpdated_at($today);
        $entreePoulesModel->update($entreePoulesID, $entreePoules);
        # On ajoute l'Adresse  dans la BD
        $message = "Entree Poule updated successfully";
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ENT_POULE);
        return success200($message);
    } else {
        createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_ENT_POULE);
        $message = "No Entree Poule Found ";
        return success205($message);
    }
}
