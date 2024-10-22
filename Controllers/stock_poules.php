<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Stock_poulesModel;

Autoloader::register();

# Store
// function storeStockPoule($StockPoulesData)
// {

//     $StockPoulesModel = new Stock_poulesModel();
//     $StockPoules = $StockPoulesModel;

//     # On recupere les informations venues de POST
//     chargementStockAliments($StockPoulesData);

//     $designation = $StockPoulesData['designation_lot'];
//     $quantite = $StockPoulesData['quantite'];
//     $date = $StockPoulesData["date"];
//     $etat = $StockPoulesData["etat"];
//     $natureID = $StockPoulesData['natures_idNature'];
//     $today = getSiku();
//     $testNature = testNaturebyId($natureID);
//     if ($testNature) {
//         $StockPoules->setDesignation_lot($designation);
//         $StockPoules->setQuantite($quantite);
//         $StockPoules->setDate($date);
//         $StockPoules->setEtat($etat);
//         $StockPoules->setNatures_idNature($natureID);
//         $StockPoules->setCreated_at($today);

//         # On ajoute la Designation dans la BD
//         $StockPoulesModel->create($StockPoules);
//         $message = "Stock Poules  created successfully";
//         createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_POULE);
//         return success201($message);
//     }
// }

#Delete
function deleteStockPoule($StockPoulesParams)
{
    $StockPoulesModel = new Stock_poulesModel();
    paramsVerify($StockPoulesParams, "Stock Poule");

    $StockPoulesID = $StockPoulesParams['id'];
    $StockPoulesData = $StockPoulesModel->find($StockPoulesID);

    if ($StockPoulesID == $StockPoulesData->id) {
        $StockPoulesModel->delete($StockPoulesID);
        $message = "Stock Poule deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STOCK_POULE);
        return success200($message);
    } else {
        $message = "Stock Poule not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STOCK_POULE);
        return error405($message);
    }
}

#Get
function getStockPoulesById($StockPoulesParams)
{
    $StockPoulesModel = new Stock_poulesModel();
    paramsVerify($StockPoulesParams, "Stock Poule");
    $stockPoulesFound = $StockPoulesModel->find($StockPoulesParams['id']);

    if (!empty($stockPoulesFound)) {
        $dataSA = getStockPouleDataById($stockPoulesFound->id);
        $message = "Stock Poules Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STOCK_POULE);
        return datasuccess200($message, $dataSA);
    } else {
        $message = "No Stock Poules Found";
        return success205($message);
    }
}

function getListStockPoules()
{
    $StockPoulesModel = new Stock_poulesModel();
    $StockPoules = (array)$StockPoulesModel->findAll();

    if (!empty($StockPoules)) {
        $dataSAlist = getListStockPouleDataById($StockPoules);
        $message = "Situation Stock Poules";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STOCK_POULE);
        return dataTableSuccess200($message, $dataSAlist);
    } else {
        $message = "Pas de situation dans le Stock Poules";
        return success205($message);
    }
}

# Update
// function updateStockAliments($StockPoulesData, $StockPoulesParams)
// {
//     $StockPoulesModel = new Stock_poulesModel();
//     $StockPoules = $StockPoulesModel;
//     paramsVerify($StockPoulesParams, "Stock Poule");

//     # On recupere les informations venues de POST
//     $StockPoulesID = $StockPoulesParams['id'];

//     $designation = $StockPoulesData['designation_lot'];
//     $quantite = $StockPoulesData['quantite'];
//     $date = $StockPoulesData["date"];
//     $etat = $StockPoulesData["etat"];
//     $natureID = $StockPoulesData['natures_idNature'];
//     $today = getSiku();

//     $testNature = testNaturebyId($natureID);
//     if ($testNature) {
//         $StockPoules->setDesignation_lot($designation);
//         $StockPoules->setQuantite($quantite);
//         $StockPoules->setDate($date);
//         $StockPoules->setEtat($etat);
//         $StockPoules->setNatures_idNature($natureID);
//         $StockPoules->setUpdated_at($today);

//         $stockPoulesFound = $StockPoulesModel->find($StockPoulesID);

//         if ($StockPoulesID == $stockPoulesFound->id) {
//             $StockPoulesModel->update($StockPoulesID, $StockPoules);
//             # On ajoute l'Adresse  dans la BD
//             $message = "Stock Poule updated successfully";
//             createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_POULE);
//             return success200($message);
//         } else {
//             $message = "No Stock Poule Found ";
//             createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_STOCK_POULE);
//             return error404($message);
//         }
//     }
// }