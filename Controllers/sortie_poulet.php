<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Sortie_pouletsModel;

Autoloader::register();

# Store
function storeSortiePoulet($sortiePouletsData)
{
    $sortiePouletsModel = new Sortie_pouletsModel();
    $sortiePoulets = $sortiePouletsModel;

    # On recupere les informations venues de POST
    chargementSortieAliment($sortiePouletsData);
    $sortiePouletsData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;

    $today = getSiku();
    $natureID = $sortiePouletsData['natures_idNature'];
    $motifID = $sortiePouletsData['motifSorties_idMotif'];
    $agentID = $sortiePouletsData['agents_id'];
    $quantite = $sortiePouletsData["quantite"];
    $clientID = $sortiePouletsData['clients_id'];
    $sortiePouletsData['date'] = $today;
    natureVerify($natureID, DESIGN_POULET);

    $testClient = testClientbyId($clientID);
    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);

    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Poule
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
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_POULET);
            $message = "Sortie Poulet created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteSortiePoulet($sortiePouletsParams)
{
    $sortiePouletsModel = new Sortie_pouletsModel();
    paramsverify($sortiePouletsParams, "sorties Poulets");

    $sortiePouletsID = $sortiePouletsParams['id'];
    $sortiePouletsData = $sortiePouletsModel->find($sortiePouletsID);

    if ($sortiePouletsID == $sortiePouletsData->id) {
        $res = $sortiePouletsModel->delete($sortiePouletsID);
        $message = "sorties Poulets deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_OUT_POULET);
        return success200($message);
    } else {
        $message = "Sorties Poules not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_OUT_POULET);
        return error405($message);
    }
}

#Get
function getSortiePouletById($sortiePouletsParams)
{
    $sortiePouletsModel = new Sortie_pouletsModel();
    paramsverify($sortiePouletsParams, "sorties Poulets");
    $sortiePouletsFound = $sortiePouletsModel->find($sortiePouletsParams['id']);

    if (!empty($sortiePouletsFound)) {
        $dataSPt = getSortiePouletDataById($sortiePouletsFound->id);
        $message = "Sorties Poules Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_OUT_POULET);
        return datasuccess200($message, $dataSPt);
    } else {
        $message = "No sortie Poulets Found";
        return success205($message);
    }
}

function getListSortiePoulet()
{
    $sortiePouletsModel = new Sortie_pouletsModel();
    $sortiePoulets = (array)$sortiePouletsModel->findAll();

    if (!empty($sortiePoulets)) {
        $dataListSa = getListSortiePouletData($sortiePoulets);
        $message = "Liste des Sorties Poulets ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_OUT_POULET);
        return dataTableSuccess200($message, $dataListSa);
    } else {
        $message = "Pas de Sorties Poulets";
        return success205($message);
    }
}

# Update
function updateSortiePoulet($sortiePouletsData, $sortiePouletsParams)
{
    $sortiePouletsModel = new Sortie_pouletsModel();
    $sortiePoulets = $sortiePouletsModel;
    paramsverify($sortiePouletsParams, "sorties Poulets");

    # On recupere les informations venues de POST
    $sortiePouletsID = $sortiePouletsParams['id'];
    $quantite = $sortiePouletsData["quantite"];
    $sortieID = $sortiePouletsData['sorties_idSortie'];
    $clientID = $sortiePouletsData['clients_id'];
    $today = getSiku();

    $testSortie = testSortiebyId($sortieID);
    $testClient = testClientbyId($clientID);

    if ($testSortie and $testClient) {
        $sortiePoulets->setQuantite($quantite);
        $sortiePoulets->setSorties_idSortie($sortieID);
        $sortiePoulets->setClient_id($clientID);
        $sortiePoulets->setUpdated_at($today);

        $sortiePouletsFound = $sortiePouletsModel->find($sortiePouletsID);

        if ($sortiePouletsID == $sortiePouletsFound->id) {
            $sortiePouletsModel->update($sortiePouletsID, $sortiePoulets);
            # On ajoute l'Adresse  dans la BD
            $message = "Sortie Poulet updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_OUT_POULET);
            return success200($message);
        } else {
            $message = "No Sortie Poulet Found ";
            return success205($message);
        }
    }
}

function getSortiesPouletBySortieID($sortieID)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "sorties_idSortie" => $sortieID,
    );
    $sortiePouletsModel = new Sortie_pouletsModel();
    $dataE = (object)$sortiePouletsModel->findBy($DataEntree);

    if ($sortieID == $dataE->sorties_idSortie) {
        $test = 1;
    }
    return $test;
}