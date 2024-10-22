<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Stock_biogazModel;

Autoloader::register();

# Store
function storeStockBiogaz($stockBiogazData)
{

    $stockBiogazModel = new Stock_biogazModel();
    $stockBiogaz = $stockBiogazModel;

    # On recupere les informations venues de POST
    chargementStockAliments($stockBiogazData);

    $designation = $stockBiogazData['designation_lot'];
    $quantite = $stockBiogazData['quantite'];
    $date = $stockBiogazData["date"];
    $etat = $stockBiogazData["etat"];
    $natureID = $stockBiogazData['natures_idNature'];
    $today = getSiku();

    $testNature = testNaturebyId($natureID);
    if ($testNature) {
        $stockBiogaz->setDesignation_lot($designation);
        $stockBiogaz->setQuantite($quantite);
        $stockBiogaz->setDate($date);
        $stockBiogaz->setEtat($etat);
        $stockBiogaz->setNatures_idNature($natureID);
        $stockBiogaz->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $stockBiogazModel->create($stockBiogaz);
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_BIOGAZ);
        $message = "Stock Biogaz  created successfully";
        return success201($message);
    }
}

#Delete
function deleteStockBiogaz($stockBiogazParams)
{

    $stockBiogazModel = new Stock_biogazModel();
    paramsVerify($stockBiogazParams, "Stock Biogaz");


    $stockBiogazID = $stockBiogazParams['id'];
    $stockBiogazData = $stockBiogazModel->find($stockBiogazID);


    if ($stockBiogazID == $stockBiogazData->id) {
        $stockBiogazModel->delete($stockBiogazID);
        $message = "Stock Biogaz deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STOCK_BIOGAZ);
        return success200($message);
    } else {
        $message = "Stock Biogaz not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STOCK_BIOGAZ);
        return error405($message);
    }
}

#Get
function getStockBiogazById($stockBiogazParams)
{
    $stockBiogazModel = new Stock_biogazModel();
    paramsVerify($stockBiogazParams, "Stock Biogaz");
    $stockBiogazFound = $stockBiogazModel->find($stockBiogazParams['id']);

    if (!empty($stockBiogazFound)) {
        $dataSG = getStockBiogazDataById($stockBiogazFound->id);
        $message = "Stock Biogaz Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STOCK_BIOGAZ);
        return datasuccess200($message, $dataSG);
    } else {
        $message = "No Stock Biogaz Found";
        return success205($message);
    }
}

function getListStockBiogaz()
{
    $stockBiogazModel = new Stock_biogazModel();
    $stockBiogaz = (array)$stockBiogazModel->findAll();

    if (!empty($stockBiogaz)) {
        $dataSG = getListStockBiogazData($stockBiogaz);
        $message = "Situation Stock Biogaz";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STOCK_BIOGAZ);
        return dataTableSuccess200($message, $dataSG);
    } else {
        $message = "Pas de situation dans le Stock Biogaz";
        return success205($message);
    }
}

# Update
// function updateStockBiogaz($stockBiogazData, $stockBiogazParams)
// {
//     $stockBiogazModel = new Stock_biogazModel();
//     $stockBiogaz = $stockBiogazModel;
//     paramsVerify($stockBiogazParams, "Stock Biogaz");

//     # On recupere les informations venues de POST
//     $stockBiogazID = $stockBiogazParams['id'];

//     $designation = $stockBiogazData['designation_lot'];
//     $quantite = $stockBiogazData['quantite'];
//     $date = $stockBiogazData["date"];
//     $etat = $stockBiogazData["etat"];
//     $natureID = $stockBiogazData['natures_idNature'];
//     $today = getSiku();

//     $stockBiogaz->setDesignation_lot($designation);
//     $stockBiogaz->setQuantite($quantite);
//     $stockBiogaz->setDate($date);
//     $stockBiogaz->setEtat($etat);
//     $stockBiogaz->setNatures_idNature($natureID);
//     $stockBiogaz->setUpdated_at($today);

//     $testNature = testNaturebyId($natureID);
//     if ($testNature) {
//         $stockBiogazFound = $stockBiogazModel->find($stockBiogazID);

//         if ($stockBiogazID == $stockBiogazFound->id) {
//             $stockBiogazModel->update($stockBiogazID, $stockBiogaz);
//             # On ajoute l'Adresse  dans la BD
//             $message = "Stock Biogaz updated successfully";
//             createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_BIOGAZ);
//             return success200($message);
//         } else {
//             $message = "No Stock Biogaz Found ";
//             createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_STOCK_BIOGAZ);
//             return success205($message);
//         }
//     }
// }