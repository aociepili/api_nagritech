<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Sortie_biogazModel;

Autoloader::register();

# Store
function storeSortieBiogaz($SortieBiogazData)
{
    $SortieBiogazModel = new Sortie_biogazModel();
    $SortieBiogaz = $SortieBiogazModel;

    # On recupere les informations venues de POST
    chargementSortieAliment($SortieBiogazData);
    $today = getSiku();
    $quantite = $SortieBiogazData["quantite"];
    $natureID = $SortieBiogazData['natures_idNature'];
    $motifID = $SortieBiogazData['motifSorties_idMotif'];
    $agentID = $SortieBiogazData['agents_id'];
    $quantite = $SortieBiogazData["quantite"];
    $clientID = $SortieBiogazData['clients_id'];
    $SortieBiogazData['date'] = $today;


    $testClient = testClientbyId($clientID);
    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);

    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Aliment
        reduireStockBiogaz($SortieBiogazData);
        // debug400('Reduction',  $sortieAlimentsData);
        #creer la sortie
        createSortie($SortieBiogazData);
        $dataSortie = getLastSortie($SortieBiogazData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Aliment");
        } else {
            $SortieBiogaz->setQuantite($quantite);
            $SortieBiogaz->setSorties_idSortie($sortieID);
            $SortieBiogaz->setClient_id($clientID);
            $SortieBiogaz->setCreated_at($today);

            # On ajoute la Designation dans la BD
            $SortieBiogazModel->create($SortieBiogaz);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_BIOGAZ);
            $message = "Sortie Biogaz created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteSortieBiogaz($SortieBiogazParams)
{
    $SortieBiogazModel = new Sortie_biogazModel();
    paramsverify($SortieBiogazParams, "Sorties  Biogaz");

    $sortieBiogazID = $SortieBiogazParams['id'];
    $SortieBiogazData = $SortieBiogazModel->find($sortieBiogazID);

    if ($sortieBiogazID == $SortieBiogazData->id) {
        $res = $SortieBiogazModel->delete($sortieBiogazID);
        $message = "Sorties  Biogaz deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_OUT_BIOGAZ);
        return success200($message);
    } else {
        $message = "Sorties Biogaz not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_OUT_BIOGAZ);
        return success205($message);
    }
}

#Get
function getSortieBiogazById($SortieBiogazParams)
{
    $SortieBiogazModel = new Sortie_biogazModel();
    paramsverify($SortieBiogazParams, "Sorties  Biogaz");
    $SortieBiogazFound = $SortieBiogazModel->find($SortieBiogazParams['id']);

    if (!empty($SortieBiogazFound)) {
        $dataSB = getSortieBiogazDataById($SortieBiogazFound->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_OUT_BIOGAZ);
        $message = "Sorties Biogaz Fetched successfully";
        return datasuccess200($message, $dataSB);
    } else {
        $message = "No sortie Biogaz Found";
        return success205($message);
    }
}

function getListSortieBiogaz()
{
    $SortieBiogazModel = new Sortie_biogazModel();
    $SortieBiogaz = (array)$SortieBiogazModel->findAll();

    if (!empty($SortieBiogaz)) {
        $dataListSB = getListSortieBiogazData($SortieBiogaz);
        $message = "Liste des Sorties Biogaz ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_OUT_BIOGAZ);
        return dataTableSuccess200($message, $dataListSB);
    } else {
        $message = "Pas de Sorties Biogaz";
        return success205($message);
    }
}

# Update
function updateSortieBiogaz($SortieBiogazData, $SortieBiogazParams)
{
    $SortieBiogazModel = new Sortie_biogazModel();
    $SortieBiogaz = $SortieBiogazModel;
    paramsverify($SortieBiogazParams, "Sorties  Biogaz");

    # On recupere les informations venues de POST
    $sortieBiogazID = $SortieBiogazParams['id'];
    $quantite = $SortieBiogazData["quantite"];
    $sortieID = $SortieBiogazData['sorties_idSortie'];
    $clientID = $SortieBiogazData['clients_id'];
    $today = getSiku();

    $testSortie = testSortiebyId($sortieID);
    $testClient = testClientbyId($clientID);

    if ($testSortie and $testClient) {
        $SortieBiogaz->setQuantite($quantite);
        $SortieBiogaz->setSorties_idSortie($sortieID);
        $SortieBiogaz->setClient_id($clientID);
        $SortieBiogaz->setUpdated_at($today);


        $SortieBiogazFound = $SortieBiogazModel->find($sortieBiogazID);

        if ($sortieBiogazID == $SortieBiogazFound->id) {
            $SortieBiogazModel->update($sortieBiogazID, $SortieBiogaz);
            # On ajoute l'Adresse  dans la BD
            $message = "Sortie Biogaz updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_OUT_BIOGAZ);
            return success200($message);
        } else {
            $message = "No Sortie  Biogaz Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_OUT_BIOGAZ);
            return success205($message);
        }
    }
}

function getSortiesBiogazBySortieID($sortieID)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "sorties_idSortie" => $sortieID,
    );
    $SortieBiogazModel = new Sortie_biogazModel();
    $dataE = (object)$SortieBiogazModel->findBy($DataEntree);

    if ($sortieID == $dataE->sorties_idSortie) {
        $test = 1;
    }
    return $test;
}