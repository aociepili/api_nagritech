<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Stock_poussinsModel;

Autoloader::register();

# Store
function storeStockPoussins($stockPoussinsData)
{

    $stockPoussinsModel = new Stock_poussinsModel();
    $stockPoussins = $stockPoussinsModel;

    # On recupere les informations venues de POST
    chargementStockAliments($stockPoussinsData);

    $designation = $stockPoussinsData['designation_lot'];
    $quantite = $stockPoussinsData['quantite'];
    $date = $stockPoussinsData["date"];
    $etat = $stockPoussinsData["etat"];
    $natureID = $stockPoussinsData['natures_idNature'];
    $today = getSiku();

    $testNature = testNaturebyId($natureID);
    if ($testNature) {

        $stockPoussins->setDesignation_lot($designation);
        $stockPoussins->setQuantite($quantite);
        $stockPoussins->setDate($date);
        $stockPoussins->setEtat($etat);
        $stockPoussins->setNatures_idNature($natureID);
        $stockPoussins->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $stockPoussinsModel->create($stockPoussins);
        $message = "Stock Poussin  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_POUSSIN);
        return success201($message);
    }
}

#Delete
function deleteStockPoussin($stockPoussinsParams)
{
    $stockPoussinsModel = new Stock_poussinsModel();
    paramsVerify($stockPoussinsParams, "Stock Poussin");

    $stockPoussinsID = $stockPoussinsParams['id'];
    $stockPoussinsData = $stockPoussinsModel->find($stockPoussinsID);

    if ($stockPoussinsID == $stockPoussinsData->id) {
        $res = $stockPoussinsModel->delete($stockPoussinsID);
        $message = "Stock Poussin deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STOCK_POUSSIN);
        return success200($message);
    } else {
        $message = "Stock Poussin not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STOCK_POUSSIN);
        return success205($message);
    }
}

#Get
function getStockPoussinById($stockPoussinsParams)
{
    $stockPoussinsModel = new Stock_poussinsModel();
    paramsVerify($stockPoussinsParams, "Stock Poussin");
    $stockPoussinsFound = $stockPoussinsModel->find($stockPoussinsParams['id']);

    if (!empty($stockPoussinsFound)) {
        $dataSP = getStockPoussinDataById($stockPoussinsFound->id);
        $message = "Stock Poussin Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STOCK_POUSSIN);
        return datasuccess200($message, $dataSP);
    } else {
        $message = "No Stock Poussin Found";
        return success205($message);
    }
}

function getListStockPoussin()
{
    $stockPoussinsModel = new Stock_poussinsModel();
    $stockPoussins = (array)$stockPoussinsModel->findAll();

    if (!empty($stockPoussins)) {
        $dataListPoussin = getListStockPoussinData($stockPoussins);
        $message = "Situation Stock Poussin";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STOCK_POUSSIN);
        return dataTableSuccess200($message, $dataListPoussin);
    } else {
        $message = "Pas de situation dans le Stock Poussin";
        return success205($message);
    }
}

# Update
// function updateStockPoussin($stockPoussinsData, $stockPoussinsParams)
// {
//     $stockPoussinsModel = new Stock_poussinsModel();
//     $stockPoussins = $stockPoussinsModel;
//     paramsVerify($stockPoussinsParams, "Stock Poussin");

//     # On recupere les informations venues de POST
//     $stockPoussinsID = $stockPoussinsParams['id'];

//     $designation = $stockPoussinsData['designation_lot'];
//     $quantite = $stockPoussinsData['quantite'];
//     $date = $stockPoussinsData["date"];
//     $etat = $stockPoussinsData["etat"];
//     $natureID = $stockPoussinsData['natures_idNature'];
//     $today = getSiku();

//     $testNature = testNaturebyId($natureID);
//     if ($testNature) {
//         $stockPoussins->setDesignation_lot($designation);
//         $stockPoussins->setQuantite($quantite);
//         $stockPoussins->setDate($date);
//         $stockPoussins->setEtat($etat);
//         $stockPoussins->setNatures_idNature($natureID);
//         $stockPoussins->setUpdated_at($today);

//         $stockPoussinsFound = $stockPoussinsModel->find($stockPoussinsID);

//         if ($stockPoussinsID == $stockPoussinsFound->id) {
//             $stockPoussinsModel->update($stockPoussinsID, $stockPoussins);
//             # On ajoute l'Adresse  dans la BD
//             $message = "Stock Poussin updated successfully";
//             createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_POUSSIN);
//             return success200($message);
//         } else {
//             $message = "No Stock Poussin Found ";
//             return error404($message);
//         }
//     }
// }

function getStockPoussinByDate($date)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "date" => $date,
    );
    $stockPoussinsModel = new Stock_poussinsModel();
    $dataSP = (object)$stockPoussinsModel->findBy($DataEntree);

    if ($date == $dataSP->designation) {
        $test = 1;
    }
    return $test;
}