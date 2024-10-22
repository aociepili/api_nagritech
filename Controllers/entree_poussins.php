<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Entree_poussinsModel;

Autoloader::register();

# Store
function storeEntreePoussins($entreePoussinsData)
{
    $entreePoussinsModel = new Entree_poussinsModel();
    $entreePoussins = $entreePoussinsModel;

    # On recupere les informations venues de POST
    chargementEntreePoussins($entreePoussinsData);
    $entreePoussinsData["etat"] = ETAT_BON;
    $entreePoussinsData['motifSorties_idMotif'] = MOTIF_ENTREE_CASH;

    $quantite = $entreePoussinsData['quantite'];
    $today = getSiku();
    $entreePoussinsData["date"] = $today;
    $natureID = $entreePoussinsData['natures_idNature'];
    natureVerify($natureID, DESIGN_POUSSIN);
    $motifID = $entreePoussinsData['motifSorties_idMotif'];
    $date = $entreePoussinsData["date"];
    $etat = $entreePoussinsData["etat"];
    $fournisseurID = $entreePoussinsData["fournisseur_id"];

    $testNature = testNaturebyId($natureID);
    $testMotif = testMotifbyId($motifID);
    $testEtat = testEtatRapportbyId($etat) && isGoodProduct($etat);

    $testFour = testFournisseurbyId($fournisseurID);

    if ($testNature && $testMotif && $testEtat && $testFour) {
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
                createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ENT_POUSSIN);
                $message = "Entree Poussin  created successfully";
                return success201($message);
            }
        }
    }
}

#Delete
function deleteEntreePoussin($entreePoussinsParams)
{
    $entreePoussinsModel = new Entree_poussinsModel();
    paramsVerify($entreePoussinsParams, "Entree Poussin");

    $entreePoussinsID = $entreePoussinsParams['id'];
    $entreePoussinsData = $entreePoussinsModel->find($entreePoussinsID);

    if ($entreePoussinsID == $entreePoussinsData->id) {
        $entreePoussinsModel->delete($entreePoussinsID);
        $message = "Entree Poussin deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ENT_POUSSIN);
        return success200($message);
    } else {
        $message = "Entree Poussin not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_ENT_POUSSIN);
        return error405($message);
    }
}

#Get
function getEntreePoussinById($entreePoussinsParams)
{
    $entreePoussinsModel = new Entree_poussinsModel();
    paramsVerify($entreePoussinsParams, "Entree Poussin");
    $entreePoussinsFound = $entreePoussinsModel->find($entreePoussinsParams['id']);

    if (!empty($entreePoussinsFound)) {
        $dataEP = getEntreePoussinDataById($entreePoussinsFound->id);
        $message = "Entree Poussin Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ENT_POUSSIN);
        return datasuccess200($message, $dataEP);
    } else {
        $message = "No Entree Poussin Found";
        return success205($message);
    }
}

function getListEntreePoussin()
{
    $entreePoussinsModel = new Entree_poussinsModel();
    $entreePoussins = (array)$entreePoussinsModel->findAll();

    if (!empty($entreePoussins)) {
        $dataList = getListEntreePoussinData($entreePoussins);
        $message = "Situation Entree Poussin";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENT_POUSSIN);
        return dataTableSuccess200($message, $dataList);
    } else {
        $message = "Pas de situation dans le Entree Poussin";
        return success205($message);
    }
}

# Update
function updateEntreePoussin($entreePoussinsData, $entreePoussinsParams)
{
    $entreePoussinsModel = new Entree_poussinsModel();
    $entreePoussins = $entreePoussinsModel;
    paramsVerify($entreePoussinsParams, "Entree Poussin");

    # On recupere les informations venues de POST
    $entreePoussinsID = $entreePoussinsParams['id'];
    $etat = $entreePoussinsData["etat"];

    $quantite = $entreePoussinsData['quantite'];
    $stockPoussinID = $entreePoussinsData['stock_Poussin_idStock'];
    $today = getSiku();

    $entreePoussinsFound = $entreePoussinsModel->find($entreePoussinsID);
    if ($entreePoussinsID == $entreePoussinsFound->id) {
        $entreeID = $entreePoussinsFound->entrees_idEntree;
        updateEntree($entreePoussinsData, $entreeID);

        $stockPoussinsID = $entreePoussinsFound->stock_Poussins_idStock;
        updateStockPoussin($entreePoussinsData, $stockPoussinsID);

        $entreePoussins->setQuantite($quantite);
        $entreePoussins->setUpdated_at($today);
        $entreePoussinsModel->update($entreePoussinsID, $entreePoussins);
        # On ajoute l'Adresse  dans la BD
        $message = "Entree Poussin updated successfully";
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ENT_POUSSIN);
        return success200($message);
    } else {
        $message = "No Entree Poussin Found ";
        return success205($message);
    }
}

function getEntreePoussinByentreeID($entreeID)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "entrees_idEntree" => $entreeID,
    );
    $entreePoussinsModel = new Entree_poussinsModel();
    $dataSP = (object)$entreePoussinsModel->findBy($DataEntree);

    if ($entreeID == $dataSP->entrees_idEntree) {
        $test = 1;
    }
    return $test;
}
