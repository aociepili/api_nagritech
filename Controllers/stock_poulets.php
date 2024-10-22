<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Stock_pouletsModel;

Autoloader::register();

# Store
function storeStockPoulet($stockPouletsData)
{
    $stockPouletsModel = new Stock_pouletsModel();
    $stockPoulets = $stockPouletsModel;

    # On recupere les informations venues de POST
    chargementStockAliments($stockPouletsData);

    $designation = $stockPouletsData['designation_lot'];
    $quantite = $stockPouletsData['quantite'];
    $date = $stockPouletsData["date"];
    $etat = $stockPouletsData["etat"];
    $natureID = $stockPouletsData['natures_idNature'];
    $today = getSiku();
    $testNature = testNaturebyId($natureID);
    if ($testNature) {
        $stockPoulets->setDesignation_lot($designation);
        $stockPoulets->setQuantite($quantite);
        $stockPoulets->setDate($date);
        $stockPoulets->setEtat($etat);
        $stockPoulets->setNatures_idNature($natureID);
        $stockPoulets->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $stockPouletsModel->create($stockPoulets);
        $message = "Stock Poulets  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_POULET);
        return success201($message);
    }
}

#Delete
function deleteStockPoulet($stockPouletsParams)
{
    $stockPouletsModel = new Stock_pouletsModel();
    paramsVerify($stockPouletsParams, "Stock Poulet");

    $stockPouletsID = $stockPouletsParams['id'];
    $stockPouletsData = $stockPouletsModel->find($stockPouletsID);

    if ($stockPouletsID == $stockPouletsData->id) {
        $stockPouletsModel->delete($stockPouletsID);
        $message = "Stock Poulet deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STOCK_POULET);
        return success200($message);
    } else {
        $message = "Stock Poulet not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STOCK_POULET);
        return error405($message);
    }
}

#Get
function getStockPouletsById($stockPouletsParams)
{
    $stockPouletsModel = new Stock_pouletsModel();
    paramsVerify($stockPouletsParams, "Stock Poulet");
    $stockPouletsFound = $stockPouletsModel->find($stockPouletsParams['id']);

    if (!empty($stockPouletsFound)) {
        $dataSA = getStockPouletDataById($stockPouletsFound->id);
        $message = "Stock Poulets Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STOCK_POULET);
        return datasuccess200($message, $dataSA);
    } else {
        $message = "No Stock Poulets Found";
        return success205($message);
    }
}

function getListStockPoulets()
{
    $stockPouletsModel = new Stock_pouletsModel();
    $stockPoulets = (array)$stockPouletsModel->findAll();

    if (!empty($stockPoulets)) {
        $dataSAlist = getListStockPouletDataById($stockPoulets);
        $message = "Situation Stock Poulets";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STOCK_POULET);
        return dataTableSuccess200($message, $dataSAlist);
    } else {
        $message = "Pas de situation dans le Stock Poulets";
        return success205($message);
    }
}

# Update
// function updateStockAliments($stockPouletsData, $stockPouletsParams)
// {
//     $stockPouletsModel = new Stock_pouletsModel();
//     $stockPoulets = $stockPouletsModel;
//     paramsVerify($stockPouletsParams, "Stock Poulet");

//     # On recupere les informations venues de POST
//     $stockPouletsID = $stockPouletsParams['id'];

//     $designation = $stockPouletsData['designation_lot'];
//     $quantite = $stockPouletsData['quantite'];
//     $date = $stockPouletsData["date"];
//     $etat = $stockPouletsData["etat"];
//     $natureID = $stockPouletsData['natures_idNature'];
//     $today = getSiku();

//     $testNature = testNaturebyId($natureID);
//     if ($testNature) {
//         $stockPoulets->setDesignation_lot($designation);
//         $stockPoulets->setQuantite($quantite);
//         $stockPoulets->setDate($date);
//         $stockPoulets->setEtat($etat);
//         $stockPoulets->setNatures_idNature($natureID);
//         $stockPoulets->setUpdated_at($today);

//         $stockPouletsFound = $stockPouletsModel->find($stockPouletsID);

//         if ($stockPouletsID == $stockPouletsFound->id) {
//             $stockPouletsModel->update($stockPouletsID, $stockPoulets);
//             # On ajoute l'Adresse  dans la BD
//             $message = "Stock Poulet updated successfully";
//             createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_POULET);
//             return success200($message);
//         } else {
//             $message = "No Stock Poulet Found ";
//             createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_STOCK_POULET);
//             return error404($message);
//         }
//     }
// }