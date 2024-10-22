<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Sortie_oeufsModel;

Autoloader::register();

# Store
function storeSortieOeuf($sortieOeufData)
{

    $SortieOeufModel = new Sortie_oeufsModel();
    $sortieOeuf = $SortieOeufModel;

    # On recupere les informations venues de POST
    chargementSortieAliment($sortieOeufData);

    $today = getSiku();
    $quantite = $sortieOeufData["quantite"];
    $natureID = $sortieOeufData['natures_idNature'];
    $motifID = $sortieOeufData['motifSorties_idMotif'];
    $agentID = $sortieOeufData['agents_id'];
    $quantite = $sortieOeufData["quantite"];
    $clientID = $sortieOeufData['clients_id'];
    $sortieOeufData['date'] = $today;


    $testClient = testClientbyId($clientID);
    $testAgent = testAgentbyId($agentID);
    $testmotif = testMotifbyId($motifID);
    $testNature = testNaturebyId($natureID);

    if ($testAgent && $testClient && $testmotif && $testNature) {
        #reduire le stock Aliment
        reduireStockOeuf($sortieOeufData);
        // debug400('Reduction',  $sortieAlimentsData);
        #creer la sortie
        createSortie($sortieOeufData);
        $dataSortie = getLastSortie($sortieOeufData);
        $sortieID = $dataSortie->id;

        if (empty($dataSortie)) {
            return success205("Pas d'enregistrement Sortie Aliment");
        } else {
            $sortieOeuf->setQuantite($quantite);
            $sortieOeuf->setSorties_idSortie($sortieID);
            $sortieOeuf->setClient_id($clientID);
            $sortieOeuf->setCreated_at($today);

            # On ajoute la Designation dans la BD
            $SortieOeufModel->create($sortieOeuf);
            $message = "Sortie Oeuf created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OUT_OEUF);
            return success201($message);
        }
    }
}

#Delete
function deleteSortieOeuf($sortieOeufParams)
{
    $SortieOeufModel = new Sortie_oeufsModel();
    paramsverify($sortieOeufParams, "Sorties  Oeuf");

    $sortieOeufID = $sortieOeufParams['id'];
    $sortieOeufData = $SortieOeufModel->find($sortieOeufID);

    if ($sortieOeufID == $sortieOeufData->id) {
        $res = $SortieOeufModel->delete($sortieOeufID);
        $message = "Sorties  Oeuf deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_OUT_OEUF);
        return success200($message);
    } else {
        $message = "Sorties Oeuf not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_OUT_OEUF);
        return success205($message);
    }
}

#Get
function getSortieOeufById($sortieOeufParams)
{
    $SortieOeufModel = new Sortie_oeufsModel();
    paramsverify($sortieOeufParams, "Sorties  Oeuf");
    $sortieOeufFound = $SortieOeufModel->find($sortieOeufParams['id']);

    if (!empty($sortieOeufFound)) {
        $dataSB = getsortieOeufDataById($sortieOeufFound->id);
        $message = "Sorties Oeuf Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_OUT_OEUF);
        return datasuccess200($message, $dataSB);
    } else {
        $message = "No sortie Oeuf Found";
        return success205($message);
    }
}

function getListSortieOeuf()
{
    $SortieOeufModel = new Sortie_oeufsModel();
    $sortieOeuf = (array)$SortieOeufModel->findAll();

    if (!empty($sortieOeuf)) {
        $dataListSB = getListsortieOeufData($sortieOeuf);
        $message = "Liste des Sorties Oeuf ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_OUT_OEUF);
        return dataTableSuccess200($message, $dataListSB);
    } else {
        $message = "Pas de Sorties Oeuf";
        return success205($message);
    }
}

# Update
function updateSortieOeuf($sortieOeufData, $sortieOeufParams)
{
    $SortieOeufModel = new Sortie_oeufsModel();
    $sortieOeuf = $SortieOeufModel;
    paramsverify($sortieOeufParams, "Sorties  Oeuf");

    # On recupere les informations venues de POST
    $sortieOeufID = $sortieOeufParams['id'];
    $quantite = $sortieOeufData["quantite"];
    $sortieID = $sortieOeufData['sorties_idSortie'];
    $clientID = $sortieOeufData['clients_id'];
    $today = getSiku();

    $testSortie = testSortiebyId($sortieID);
    $testClient = testClientbyId($clientID);

    if ($testSortie and $testClient) {
        $sortieOeuf->setQuantite($quantite);
        $sortieOeuf->setSorties_idSortie($sortieID);
        $sortieOeuf->setClient_id($clientID);
        $sortieOeuf->setUpdated_at($today);


        $sortieOeufFound = $SortieOeufModel->find($sortieOeufID);

        if ($sortieOeufID == $sortieOeufFound->id) {
            $SortieOeufModel->update($sortieOeufID, $sortieOeuf);
            # On ajoute l'Adresse  dans la BD
            $message = "Sortie Oeuf updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_OUT_OEUF);
            return success200($message);
        } else {
            $message = "No Sortie  Oeuf Found ";
            return success205($message);
        }
    }
}