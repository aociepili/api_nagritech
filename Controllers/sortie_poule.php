<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Sortie_poulesModel;

Autoloader::register();

# Store
function storeSortiePoule($sortiePoulesData)
{
    $sortiePoulesModel = new Sortie_poulesModel();
    $sortiePoules = $sortiePoulesModel;

    # On recupere les informations venues de POST
    chargementSortieAliment($sortiePoulesData);
    $sortiePoulesData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;

    $today = getSiku();
    $natureID = $sortiePoulesData['natures_idNature'];
    $motifID = $sortiePoulesData['motifSorties_idMotif'];
    $agentID = $sortiePoulesData['agents_id'];
    $quantite = $sortiePoulesData["quantite"];
    $clientID = $sortiePoulesData['clients_id'];
    $sortiePoulesData['date'] = $today;
    natureVerify($natureID, DESIGN_POULE);

    $testClient = testClientbyId($clientID);
    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);

    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Poule
        reduireStockPoule($sortiePoulesData);

        #creer la sortie
        createSortie($sortiePoulesData);
        $dataSortie = getLastSortie($sortiePoulesData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Poule");
        } else {

            $sortiePoules->setQuantite($quantite);
            $sortiePoules->setSorties_idSortie($sortieID);
            $sortiePoules->setClient_id($clientID);
            $sortiePoules->setCreated_at($today);

            # On ajoute la Designation dans la BD
            $sortiePoulesModel->create($sortiePoules);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_POULE);
            $message = "Sortie Poule created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteSortiePoule($sortiePoulesParams)
{
    $sortiePoulesModel = new Sortie_poulesModel();
    paramsverify($sortiePoulesParams, "sorties Poules");

    $sortiePoulesID = $sortiePoulesParams['id'];
    $sortiePoulesData = $sortiePoulesModel->find($sortiePoulesID);

    if ($sortiePoulesID == $sortiePoulesData->id) {
        $res = $sortiePoulesModel->delete($sortiePoulesID);
        $message = "sorties Poules deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_OUT_POULE);
        return success200($message);
    } else {
        $message = "Sorties Poules not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_OUT_POULE);
        return error405($message);
    }
}

#Get
function getSortiePouleById($sortiePoulesParams)
{
    $sortiePoulesModel = new Sortie_poulesModel();
    paramsverify($sortiePoulesParams, "sorties Poules");
    $sortiePoulesFound = $sortiePoulesModel->find($sortiePoulesParams['id']);

    if (!empty($sortiePoulesFound)) {
        $dataSP = getSortiePouleDataById($sortiePoulesFound->id);
        $message = "Sorties Poules Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_OUT_POULE);
        return datasuccess200($message, $dataSP);
    } else {
        $message = "No sortie Aliments Found";
        return success205($message);
    }
}

function getListSortiePoule()
{
    $sortiePoulesModel = new Sortie_poulesModel();
    $sortiePoules = (array)$sortiePoulesModel->findAll();

    if (!empty($sortiePoules)) {
        $dataListSa = getListSortiePouleData($sortiePoules);
        $message = "Liste des Sorties Poules ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_OUT_POULE);
        return dataTableSuccess200($message, $dataListSa);
    } else {
        $message = "Pas de Sorties Poules";
        return success205($message);
    }
}

# Update
function updateSortiePoule($sortiePoulesData, $sortiePoulesParams)
{
    $sortiePoulesModel = new Sortie_poulesModel();
    $sortiePoules = $sortiePoulesModel;
    paramsverify($sortiePoulesParams, "sorties Poules");

    # On recupere les informations venues de POST
    $sortiePoulesID = $sortiePoulesParams['id'];
    $quantite = $sortiePoulesData["quantite"];
    $sortieID = $sortiePoulesData['sorties_idSortie'];
    $clientID = $sortiePoulesData['clients_id'];
    $today = getSiku();

    $testSortie = testSortiebyId($sortieID);
    $testClient = testClientbyId($clientID);

    if ($testSortie and $testClient) {
        $sortiePoules->setQuantite($quantite);
        $sortiePoules->setSorties_idSortie($sortieID);
        $sortiePoules->setClient_id($clientID);
        $sortiePoules->setUpdated_at($today);

        $sortiePoulesFound = $sortiePoulesModel->find($sortiePoulesID);

        if ($sortiePoulesID == $sortiePoulesFound->id) {
            $sortiePoulesModel->update($sortiePoulesID, $sortiePoules);
            # On ajoute l'Adresse  dans la BD
            $message = "Sortie Poule updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_OUT_POULE);
            return success200($message);
        } else {
            $message = "No Sortie Poule Found ";
            return success205($message);
        }
    }
}

function getSortiesPouleBySortieID($sortieID)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "sorties_idSortie" => $sortieID,
    );
    $sortiePoulesModel = new Sortie_poulesModel();
    $dataE = (object)$sortiePoulesModel->findBy($DataEntree);

    if ($sortieID == $dataE->sorties_idSortie) {
        $test = 1;
    }
    return $test;
}