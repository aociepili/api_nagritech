<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Sortie_poussinsModel;

Autoloader::register();

# Store
function storeSortiePoussin($sortiePoussinsData)
{
    $sortiePoussinsModel = new Sortie_poussinsModel();
    $sortiePoussins = $sortiePoussinsModel;

    # On recupere les informations venues de POST
    chargementSortieAliment($sortiePoussinsData);

    $today = getSiku();
    $quantite = $sortiePoussinsData["quantite"];
    $natureID = $sortiePoussinsData['natures_idNature'];
    $motifID = $sortiePoussinsData['motifSorties_idMotif'];
    $agentID = $sortiePoussinsData['agents_id'];
    $quantite = $sortiePoussinsData["quantite"];
    $clientID = $sortiePoussinsData['clients_id'];
    $sortiePoussinsData['date'] = $today;

    $testClient = testClientbyId($clientID);
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
            $sortiePoussins->setCreated_at(getSiku());

            # On ajoute la Designation dans la BD
            $sortiePoussinsModel->create($sortiePoussins);
            $message = "Sortie Poussin created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_POUSSIN);
            return success201($message);
        }
    }
}

#Delete
function deleteSortiePoussin($SortieBiogazParams)
{
    $sortiePoussinsModel = new Sortie_poussinsModel();
    paramsverify($SortieBiogazParams, "Sorties  Poussin");

    $sortiePoussinsID = $SortieBiogazParams['id'];
    $sortiePoussinsData = $sortiePoussinsModel->find($sortiePoussinsID);

    if ($sortiePoussinsID == $sortiePoussinsData->id) {
        $res = $sortiePoussinsModel->delete($sortiePoussinsID);
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_OUT_POUSSIN);
        $message = "Sorties  Poussin deleted successfully";
        return success200($message);
    } else {
        $message = "Sorties Poussin not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_OUT_POUSSIN);
        return error405($message);
    }
}

#Get
function getSortiePoussinById($SortieBiogazParams)
{
    $sortiePoussinsModel = new Sortie_poussinsModel();
    paramsverify($SortieBiogazParams, "Sorties  Poussin");
    $sortiePoussinsFound = $sortiePoussinsModel->find($SortieBiogazParams['id']);

    if (!empty($sortiePoussinsFound)) {
        $dataSP = getSortiePoussinDataById($sortiePoussinsFound->id);
        $message = "Sorties Poussin Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_OUT_POUSSIN);
        return datasuccess200($message, $dataSP);
    } else {
        $message = "No sortie Poussin Found";
        return success205($message);
    }
}

function getListSortiePoussin()
{
    $sortiePoussinsModel = new Sortie_poussinsModel();
    $sortiePoussins = (array)$sortiePoussinsModel->findAll();

    if (!empty($sortiePoussins)) {
        $dataListSP = getListSortiePoussinData($sortiePoussins);
        $message = "Liste des Sorties Poussin ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_OUT_POUSSIN);
        return dataTableSuccess200($message, $dataListSP);
    } else {
        $message = "Pas de Sorties Poussin";
        return success205($message);
    }
}

# Update
function updateSortiePoussin($sortiePoussinsData, $SortieBiogazParams)
{
    $sortiePoussinsModel = new Sortie_poussinsModel();
    $sortiePoussins = $sortiePoussinsModel;
    paramsverify($SortieBiogazParams, "Sorties  Poussin");

    # On recupere les informations venues de POST
    $sortiePoussinsID = $SortieBiogazParams['id'];
    $quantite = $sortiePoussinsData["quantite"];
    $sortieID = $sortiePoussinsData['sorties_idSortie'];
    $clientID = $sortiePoussinsData['clients_id'];

    $testSortie = testSortiebyId($sortieID);
    $testClient = testClientbyId($clientID);

    if ($testSortie and $testClient) {
        $sortiePoussins->setQuantite($quantite);
        $sortiePoussins->setSorties_idSortie($sortieID);
        $sortiePoussins->setClient_id($clientID);
        $sortiePoussins->setUpdated_at(getSiku());

        $sortiePoussinsFound = $sortiePoussinsModel->find($sortiePoussinsID);

        if ($sortiePoussinsID == $sortiePoussinsFound->id) {
            $sortiePoussinsModel->update($sortiePoussinsID, $sortiePoussins);
            # On ajoute l'Adresse  dans la BD
            $message = "Sortie Poussin updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_OUT_POUSSIN);
            return success200($message);
        } else {
            $message = "No Sortie  Poussin Found ";
            return success205($message);
        }
    }
}

function getSortiesPoussinBySortieID($sortieID)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "sorties_idSortie" => $sortieID,
    );
    $sortiePoussinsModel = new Sortie_poussinsModel();
    $dataE = (object)$sortiePoussinsModel->findBy($DataEntree);

    if ($sortieID == $dataE->sorties_idSortie) {
        $test = 1;
    }
    return $test;
}