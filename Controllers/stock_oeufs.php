<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Stock_oeufsModel;

Autoloader::register();

# Store
function storeStockOeuf($stockOeufsData)
{
    $stockOeufsModel = new Stock_oeufsModel();
    $stockOeufs = $stockOeufsModel;

    # On recupere les informations venues de POST
    chargementStockAliments($stockOeufsData);

    $designation = $stockOeufsData['designation_lot'];
    $quantite = $stockOeufsData['quantite'];
    $date = $stockOeufsData["date"];
    $etat = $stockOeufsData["etat"];
    $natureID = $stockOeufsData['natures_idNature'];
    $today = getSiku();

    $testNature = testNaturebyId($natureID);

    if ($testNature) {
        $stockOeufs->setDesignation_lot($designation);
        $stockOeufs->setQuantite($quantite);
        $stockOeufs->setDate($date);
        $stockOeufs->setEtat($etat);
        $stockOeufs->setNatures_idNature($natureID);
        $stockOeufs->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $stockOeufsModel->create($stockOeufs);
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_OEUF);
        $message = "Stock Oeufs  created successfully";

        return success201($message);
    }
}

#Delete
function deleteStockOeufs($stockOeufsParams)
{
    $stockOeufsModel = new Stock_oeufsModel();
    paramsVerify($stockOeufsParams, "Stock Oeuf");

    $stockOeufsID = $stockOeufsParams['id'];
    $stockOeufsData = $stockOeufsModel->find($stockOeufsID);

    if ($stockOeufsID == $stockOeufsData->id) {
        $res = $stockOeufsModel->delete($stockOeufsID);
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STOCK_OEUF);
        $message = "Stock Oeuf deleted successfully";
        return success200($message);
    } else {
        $message = "Stock Oeuf not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STOCK_OEUF);
        return error405($message);
    }
}

#Get
function getStockOeufById($stockOeufsParams)
{
    $stockOeufsModel = new Stock_oeufsModel();
    paramsVerify($stockOeufsParams, "Stock Oeuf");
    $stockOeufsFound = $stockOeufsModel->find($stockOeufsParams['id']);

    if (!empty($stockOeufsFound)) {
        $dataSO = getStockOeufDataById($stockOeufsFound->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STOCK_OEUF);
        $message = "Stock Oeufs Fetched successfully";
        return datasuccess200($message, $dataSO);
    } else {
        $message = "No Stock Oeufs Found";
        return success205($message);
    }
}

function getListStockOeuf()
{
    $stockOeufsModel = new Stock_oeufsModel();
    $stockOeufs = (array)$stockOeufsModel->findAll();

    if (!empty($stockOeufs)) {
        $dataListSO = getListStockOeufData($stockOeufs);
        $message = "Situation Stock oeuf";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STOCK_OEUF);
        return dataTableSuccess200($message, $dataListSO);
    } else {
        $message = "Pas de situation dans le Stock Oeuf";
        return success205($message);
    }
}

# Update
// function updateStockOeuf($stockOeufsData, $stockOeufsParams)
// {
//     $stockOeufsModel = new Stock_oeufsModel();
//     $stockOeufs = $stockOeufsModel;
//     paramsVerify($stockOeufsParams, "Stock Oeuf");

//     # On recupere les informations venues de POST
//     $stockOeufsID = $stockOeufsParams['id'];

//     $designation = $stockOeufsData['designation_lot'];
//     $quantite = $stockOeufsData['quantite'];
//     $date = $stockOeufsData["date"];
//     $etat = $stockOeufsData["etat"];
//     $natureID = $stockOeufsData['natures_idNature'];
//     $today = getSiku();

//     $testNature = testNaturebyId($natureID);
//     if ($testNature) {
//         $stockOeufs->setDesignation_lot($designation);
//         $stockOeufs->setQuantite($quantite);
//         $stockOeufs->setDate($date);
//         $stockOeufs->setEtat($etat);
//         $stockOeufs->setNatures_idNature($natureID);
//         $stockOeufs->setUpdated_at($today);

//         $stockOeufsFound = $stockOeufsModel->find($stockOeufsID);

//         if ($stockOeufsID == $stockOeufsFound->id) {
//             $stockOeufsModel->update($stockOeufsID, $stockOeufs);
//             # On ajoute l'Adresse  dans la BD
//             $message = "Stock Oeuf updated successfully";
//             createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_OEUF);
//             return success200($message);
//         } else {
//             $message = "No Stock Oeuf Found ";
//             return success205($message);
//         }
//     }
// }

function getStockOeufByDate($date)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "date" => $date,
    );
    $stockOeufsModel = new Stock_oeufsModel();
    $dataSO = (object)$stockOeufsModel->findBy($DataEntree);

    if ($date == $dataSO->designation) {
        $test = 1;
    }
    return $test;
}