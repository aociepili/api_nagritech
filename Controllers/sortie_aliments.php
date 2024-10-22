<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Sortie_alimentsModel;

Autoloader::register();

# Store
function storeSortieAliment($sortieAlimentsData)
{
    $sortieAlimentsModel = new Sortie_alimentsModel();
    $sortieAliments = $sortieAlimentsModel;

    # On recupere les informations venues de POST
    chargementSortieAliment($sortieAlimentsData);
    $sortieAlimentsData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;

    $today = getSiku();
    $natureID = $sortieAlimentsData['natures_idNature'];
    $motifID = $sortieAlimentsData['motifSorties_idMotif'];
    $agentID = $sortieAlimentsData['agents_id'];
    $quantite = $sortieAlimentsData["quantite"];
    $clientID = $sortieAlimentsData['clients_id'];
    $sortieAlimentsData['date'] = $today;
    natureVerify($natureID, DESIGN_ALIMENT);


    $testClient = testClientbyId($clientID);
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
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_ALIMENT);
            $message = "Sortie Aliment created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteSortieAliment($sortieAlimentsParams)
{
    $sortieAlimentsModel = new Sortie_alimentsModel();
    paramsverify($sortieAlimentsParams, "Sorties  Aliments");

    $sortieAlimentsID = $sortieAlimentsParams['id'];
    $sortieAlimentsData = $sortieAlimentsModel->find($sortieAlimentsID);

    if ($sortieAlimentsID == $sortieAlimentsData->id) {
        $res = $sortieAlimentsModel->delete($sortieAlimentsID);
        $message = "Sorties  Aliments deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_OUT_ALIMENT);
        return success200($message);
    } else {
        $message = "Sorties Aliments not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_OUT_ALIMENT);
        return error405($message);
    }
}

#Get
function getSortieAlimentById($sortieAlimentsParams)
{
    $sortieAlimentsModel = new Sortie_alimentsModel();
    paramsverify($sortieAlimentsParams, "Sorties  Aliments");
    $sortieAlimentsFound = $sortieAlimentsModel->find($sortieAlimentsParams['id']);

    if (!empty($sortieAlimentsFound)) {
        $dataSA = getSortieAlimentDataById($sortieAlimentsFound->id);
        $message = "Sorties Aliments Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_OUT_ALIMENT);
        return datasuccess200($message, $dataSA);
    } else {
        $message = "No sortie Aliments Found";
        return success205($message);
    }
}

function getListSortieAliment()
{
    $sortieAlimentsModel = new Sortie_alimentsModel();
    $sortieAliments = (array)$sortieAlimentsModel->findAll();

    if (!empty($sortieAliments)) {
        $dataListSa = getListSortieAlimentData($sortieAliments);
        $message = "Liste des Sorties Aliments ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_OUT_ALIMENT);
        return dataTableSuccess200($message, $dataListSa);
    } else {
        $message = "Pas de Sorties Aliments";
        return success205($message);
    }
}

# Update
function updateSortieAliment($sortieAlimentsData, $sortieAlimentsParams)
{
    $sortieAlimentsModel = new Sortie_alimentsModel();
    $sortieAliments = $sortieAlimentsModel;
    paramsverify($sortieAlimentsParams, "Sorties  Aliments");

    # On recupere les informations venues de POST
    $sortieAlimentsID = $sortieAlimentsParams['id'];
    $quantite = $sortieAlimentsData["quantite"];
    $sortieID = $sortieAlimentsData['sorties_idSortie'];
    $clientID = $sortieAlimentsData['clients_id'];
    $today = getSiku();

    $testSortie = testSortiebyId($sortieID);
    $testClient = testClientbyId($clientID);

    if ($testSortie and $testClient) {
        $sortieAliments->setQuantite($quantite);
        $sortieAliments->setSorties_idSortie($sortieID);
        $sortieAliments->setClient_id($clientID);
        $sortieAliments->setUpdated_at($today);

        $sortieAlimentsFound = $sortieAlimentsModel->find($sortieAlimentsID);

        if ($sortieAlimentsID == $sortieAlimentsFound->id) {
            $sortieAlimentsModel->update($sortieAlimentsID, $sortieAliments);
            # On ajoute l'Adresse  dans la BD
            $message = "Sortie Aliment updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_OUT_ALIMENT);
            return success200($message);
        } else {
            $message = "No Sortie  Aliment Found ";
            return success205($message);
        }
    }
}

function getSortiesAlimentBySortieID($sortieID)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "sorties_idSortie" => $sortieID,
    );
    $sortieAlimentsModel = new Sortie_alimentsModel();
    $dataE = (object)$sortieAlimentsModel->findBy($DataEntree);

    if ($sortieID == $dataE->sorties_idSortie) {
        $test = 1;
    }
    return $test;
}