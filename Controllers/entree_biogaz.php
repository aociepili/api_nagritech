<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Entree_biogazModel;


Autoloader::register();

# Store
function storeEntreeBiogaz($entreeBiogazData)
{
    $EntreeBiogazModel = new Entree_biogazModel();
    $EntreeBiogaz = $EntreeBiogazModel;

    # On recupere les informations venues de POST
    chargementEntreeBiogaz($entreeBiogazData);
    $entreeBiogazData["etat"] = ETAT_BON;
    $entreeBiogazData['motifSorties_idMotif'] = MOTIF_ENTREE_CASH;

    $quantite = $entreeBiogazData['quantite'];
    $entreeID = $entreeBiogazData["entrees_idEntree"];
    $stockBiogazID = $entreeBiogazData['stock_Biogaz_idStock'];
    $today = getSiku();
    $entreeBiogazData["date"] = $today;
    $natureID = $entreeBiogazData['natures_idNature'];
    natureVerify($natureID, DESIGN_BIOGAZ);
    $motifID = $entreeBiogazData['motifSorties_idMotif'];
    $date = $entreeBiogazData["date"];
    $etat = $entreeBiogazData["etat"];
    $fournisseurID = $entreeBiogazData['fournisseur_id'];

    $testNature = testNaturebyId($natureID);
    $testFour = testFournisseurbyId($fournisseurID);
    $testMotif = testMotifbyId($motifID);
    $testEtat = testEtatRapportbyId($etat) && isGoodProduct($etat);
    if ($testNature && $testFour && $testMotif && $testEtat) {
        # Creer Entree
        createEntree($entreeBiogazData);
        $entreeData = getLastEntree($entreeBiogazData);
        $entreeID = $entreeData->id;
        if (empty($entreeData)) {
            return success205("Pas d'enregistrement de l'Entree");
        } else {
            #Creer Stock Aliment
            createStockBiogaz($entreeBiogazData);
            $stockBiogazData = getLastStockBiogaz($entreeBiogazData);
            $stockBiogazID = $stockBiogazData->id;
            if (empty($stockBiogazData)) {
                return success205("Pas d'enregistrement Stock Aliment");
            } else {
                $EntreeBiogaz->setQuantite($quantite);
                $EntreeBiogaz->setEntrees_idEntree($entreeID);
                $EntreeBiogaz->setStock_Biogaz_idStock($stockBiogazID);
                $EntreeBiogaz->setCreated_at($today);
                # On ajoute la Designation dans la BD
                $EntreeBiogazModel->create($EntreeBiogaz);
                createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ENT_BIOGAZ);
                $message = "Entree Biogaz  created successfully";
                return success201($message);
            }
        }
    }
}

#Delete
function deleteEntreeBiogaz($entreeBiogazParams)
{
    $EntreeBiogazModel = new Entree_biogazModel();
    paramsVerify($entreeBiogazParams, "Entree Biogaz");

    $entreeBiogazID = $entreeBiogazParams['id'];
    $entreeBiogazData = $EntreeBiogazModel->find($entreeBiogazID);

    if ($entreeBiogazID == $entreeBiogazData->id) {
        $res = $EntreeBiogazModel->delete($entreeBiogazID);
        $message = "Entree Biogaz deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ENT_BIOGAZ);
        return success200($message);
    } else {
        $message = "Entree Biogaz not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_ENT_BIOGAZ);
        return error405($message);
    }
}

#Get
function getEntreeBiogazById($entreeBiogazParams)
{
    $EntreeBiogazModel = new Entree_biogazModel();
    paramsVerify($entreeBiogazParams, "Entree Biogaz");
    $entreeBiogazFound = $EntreeBiogazModel->find($entreeBiogazParams['id']);

    if (!empty($entreeBiogazFound)) {
        $dataEBG = getStockBiogazDataById($entreeBiogazFound->stock_Biogaz_idStock);
        $message = "Entree Biogaz Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ENT_BIOGAZ);
        return datasuccess200($message, $dataEBG);
    } else {
        $message = "No Entree Biogaz Found";
        return success205($message);
    }
}

function getListEntreeBiogaz()
{
    $EntreeBiogazModel = new Entree_biogazModel();
    $EntreeBiogaz = (array)$EntreeBiogazModel->findAll();

    if (!empty($EntreeBiogaz)) {
        $dataListEBG = getListEntreeBiogazData($EntreeBiogaz);
        $message = "Situation Entree Biogaz";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENT_BIOGAZ);
        return dataTableSuccess200($message, $dataListEBG);
    } else {
        $message = "Pas de situation dans le Entree Biogaz";
        return success205($message);
    }
}

# Update
function updateEntreeBiogaz($entreeBiogazData, $entreeBiogazParams)
{
    $EntreeBiogazModel = new Entree_biogazModel();
    $EntreeBiogaz = $EntreeBiogazModel;
    paramsVerify($entreeBiogazParams, "Entree Biogaz");

    # On recupere les informations venues de POST
    $entreeBiogazID = $entreeBiogazParams['id'];
    $etat = $entreeBiogazData["etat"];

    $quantite = $entreeBiogazData['quantite'];
    $stockBiogazID = $entreeBiogazData['stock_Biogaz_idStock'];
    $today = getSiku();

    $entreeBiogazFound = $EntreeBiogazModel->find($entreeBiogazID);
    if ($entreeBiogazID ==  $entreeBiogazFound->id) {
        $entreeID = $entreeBiogazFound->entrees_idEntree;

        updateEntree($entreeBiogazData, $entreeID);

        $stockBiogazID = $entreeBiogazFound->stock_Biogaz_idStock;

        updateStockBiogaz($entreeBiogazData, $stockBiogazID);

        $EntreeBiogaz->setQuantite($quantite);
        $EntreeBiogaz->setUpdated_at($today);
        $EntreeBiogazModel->update($entreeBiogazID, $EntreeBiogaz);
        # On ajoute l'Adresse  dans la BD
        $message = "Entree Biogaz updated successfully";
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ENT_BIOGAZ);
        return success200($message);
    } else {
        $message = "Entree Biogaz not found";
        return success205($message);
    }
}

function getEntreeBiogazByentreeID($entreeID)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "entrees_idEntree" => $entreeID,
    );
    $EntreeBiogazModel = new Entree_biogazModel();
    $dataSP = (object)$EntreeBiogazModel->findBy($DataEntree);

    if ($entreeID == $dataSP->entrees_idEntree) {
        $test = 1;
    }
    return $test;
}
