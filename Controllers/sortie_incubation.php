<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Sortie_incubationsModel;

Autoloader::register();

# Store

#Delete
function deleteSortieIncubation($SortieIncubationParams)
{
    $SortieIncubationModel = new Sortie_incubationsModel();
    paramsverify($SortieIncubationParams, "Sorties  Incubation");

    $sortieIncID = $SortieIncubationParams['id'];
    $SortieIncData = $SortieIncubationModel->find($sortieIncID);

    if ($sortieIncID == $SortieIncData->id) {
        $res = $SortieIncubationModel->delete($sortieIncID);
        $message = "Sorties  Incubation deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_OUT_OEUF);
        return success200($message);
    } else {
        $message = "Sorties Incubation not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_OUT_OEUF);
        return success205($message);
    }
}

#Get
function getSortieIncById($SortieIncubationParams)
{
    $SortieIncubationModel = new Sortie_incubationsModel();
    paramsverify($SortieIncubationParams, "Sorties  Incubation");
    $sortieIncFound = $SortieIncubationModel->find($SortieIncubationParams['id']);

    if (!empty($sortieIncFound)) {
        $dataSB = getsortieIncDataById($sortieIncFound->id);
        $message = "Sorties Incubation Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_OUT_OEUF);
        return datasuccess200($message, $dataSB);
    } else {
        $message = "No sortie Incubation Found";
        return success205($message);
    }
}

function getListSortieInc()
{
    $SortieIncubationModel = new Sortie_incubationsModel();
    $SortieIncubation = (array)$SortieIncubationModel->findAll();

    if (!empty($SortieIncubation)) {
        $dataListSB = getListSortieIncData($SortieIncubation);
        $message = "Liste des Sorties Incubation ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_OUT_OEUF);
        return dataTableSuccess200($message, $dataListSB);
    } else {
        $message = "Pas de Sorties Incubation";
        return success205($message);
    }
}

# Update
function updateSortieOeuf($SortieIncData, $SortieIncubationParams)
{
    $SortieIncubationModel = new Sortie_incubationsModel();
    $SortieIncubation = $SortieIncubationModel;
    paramsverify($SortieIncubationParams, "Sorties  Incubation");

    # On recupere les informations venues de POST
    $sortieIncID = $SortieIncubationParams['id'];
    $quantite = $SortieIncData["quantite"];
    $sortieID = $SortieIncData['sorties_idSortie'];
    $clientID = $SortieIncData['clients_id'];
    $today = getSiku();

    $testSortie = testSortiebyId($sortieID);
    $testClient = testClientbyId($clientID);

    if ($testSortie and $testClient) {
        $SortieIncubation->setQuantite($quantite);
        $SortieIncubation->setSorties_idSortie($sortieID);
        $SortieIncubation->setClient_id($clientID);
        $SortieIncubation->setUpdated_at($today);


        $sortieIncFound = $SortieIncubationModel->find($sortieIncID);

        if ($sortieIncID == $sortieIncFound->id) {
            $SortieIncubationModel->update($sortieIncID, $SortieIncubation);
            # On ajoute l'Adresse  dans la BD
            $message = "Sortie Incubation updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_OUT_OEUF);
            return success200($message);
        } else {
            $message = "No Sortie  Incubation Found ";
            return success205($message);
        }
    }
}
