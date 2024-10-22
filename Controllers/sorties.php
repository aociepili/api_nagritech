<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\SortiesModel;

Autoloader::register();

# Store
function storeSorties($sortiesData)
{
    $sortiesModel = new SortiesModel();
    $sorties = $sortiesModel;

    # On recupere les informations venues de POST
    chargementSorties($sortiesData);

    $date = $sortiesData["date"];
    $natureID = $sortiesData['natures_idNature'];
    $motifID = $sortiesData['motifSorties_idMotif'];
    $agentID = $sortiesData['agents_id'];
    $today = getSiku();

    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);

    if ($testAgent and $testmotif and $testNature) {
        $sorties->setDate($date);
        $sorties->setNatures_idNature($natureID);
        $sorties->setMotifSorties_idMotif($motifID);
        $sorties->setAgents_id($agentID);
        $sorties->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $sortiesModel->create($sorties);
        $message = "Sortie  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_SORTIE);
        return success201($message);
    }
}

#Delete
function deleteSorties($sortiesParams)
{
    $sortiesModel = new SortiesModel();
    paramsVerify($sortiesParams, "Sorties");

    $sortiesID = $sortiesParams['id'];
    $sortiesData = $sortiesModel->find($sortiesID);

    if ($sortiesID == $sortiesData->id) {
        $res = $sortiesModel->delete($sortiesID);
        $message = "Sorties deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_SORTIE);
        return success200($message);
    } else {
        $message = "Sorties not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_SORTIE);
        return error405($message);
    }
}

#Get
function getSortiesById($sortiesParams)
{
    $sortiesModel = new SortiesModel();
    paramsVerify($sortiesParams, "Sorties");
    $sortiesFound = $sortiesModel->find($sortiesParams['id']);

    if (!empty($sortiesFound)) {
        $dataSortie = getSortieDataById($sortiesFound->id);
        $message = "Sortie Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_SORTIE);
        return datasuccess200($message, $dataSortie);
    } else {
        $message = "No sorties Found";
        return success205($message);
    }
}

function getListSorties()
{
    $sortiesModel = new SortiesModel();
    $sorties = (array)$sortiesModel->findAll();

    if (!empty($sorties)) {
        $dataListSortie = getListSortieData($sorties);
        $message = "Liste des Sorties";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_SORTIE);
        return dataTableSuccess200($message, $dataListSortie);
    } else {
        $message = "Pas de Sorties";
        return success205($message);
    }
}
function getListSortiesYear($sortiesParams)
{
    $sortiesModel = new SortiesModel();
    $sorties = (array)$sortiesModel->findAll();
    paramsVerifyYear($sortiesParams, "Sorties");
    $year = $sortiesParams['year'];


    if (!empty($sorties)) {
        $dataListSortie = getListSortiesDataByIdYear($sorties, $year);
        $message = "Liste des Sortiesde l'annee " . $year;
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_SORTIE);
        return dataTableSuccess200($message, $dataListSortie);
    } else {
        $message = "Pas de Sorties";
        return success205($message);
    }
}
function getListSortiesMonth()
{
    $sortiesModel = new SortiesModel();
    $sorties = (array)$sortiesModel->findAll();

    if (!empty($sorties)) {
        $dataListSortie = getListSortiesDataByIdMonth($sorties);
        $message = "Liste des Sorties du Mois ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_SORTIE);
        return dataTableSuccess200($message, $dataListSortie);
    } else {
        $message = "Pas de Sorties";
        return success205($message);
    }
}
function getListSortiesWeek()
{
    $sortiesModel = new SortiesModel();
    $sorties = (array)$sortiesModel->findAll();

    if (!empty($sorties)) {
        $dataListSortie = getListSortiesDataByIdWeek($sorties);
        $message = "Liste des Sorties de la semaine";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_SORTIE);
        return dataTableSuccess200($message, $dataListSortie);
    } else {
        $message = "Pas de Sorties";
        return success205($message);
    }
}
function getListSortiesDay()
{
    $sortiesModel = new SortiesModel();
    $sorties = (array)$sortiesModel->findAll();

    if (!empty($sorties)) {
        $dataListSortie = getListSortiesDataByIdDay($sorties);
        $message = "Liste des Sorties du Jour ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_SORTIE);
        return dataTableSuccess200($message, $dataListSortie);
    } else {
        $message = "Pas de Sorties";
        return success205($message);
    }
}

# Update
function updateSorties($sortiesData, $sortiesParams)
{
    $sortiesModel = new SortiesModel();
    $sorties = $sortiesModel;
    paramsVerify($sortiesParams, "Sorties");

    # On recupere les informations venues de POST
    $sortiesID = $sortiesParams['id'];

    $date = $sortiesData["date"];
    $natureID = $sortiesData['natures_idNature'];
    $motifID = $sortiesData['motifSorties_idMotif'];
    $agentID = $sortiesData['agents_id'];
    $today = getSiku();

    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);

    if ($testAgent and $testmotif and $testNature) {
        $sorties->setDate($date);
        $sorties->setNatures_idNature($natureID);
        $sorties->setMotifSorties_idMotif($motifID);
        $sorties->setAgents_id($agentID);
        $sorties->setUpdated_at($today);


        $sortiesFound = $sortiesModel->find($sortiesID);

        if ($sortiesID == $sortiesFound->id) {
            $sortiesModel->update($sortiesID, $sorties);
            # On ajoute l'Adresse  dans la BD
            $message = "Sortie updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_SORTIE);
            return success200($message);
        } else {
            $message = "No Sortie Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_SORTIE);
            return success205($message);
        }
    }
}