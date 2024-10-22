<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Stock_alimentsModel;

Autoloader::register();

# Store
function storeStockAliment($StockAlimentsData)
{

    $StockAlimentsModel = new Stock_alimentsModel();
    $StockAliments = $StockAlimentsModel;

    # On recupere les informations venues de POST
    chargementStockAliments($StockAlimentsData);

    $designation = $StockAlimentsData['designation_lot'];
    $quantite = $StockAlimentsData['quantite'];
    $date = $StockAlimentsData["date"];
    $etat = $StockAlimentsData["etat"];
    $natureID = $StockAlimentsData['natures_idNature'];
    $today = getSiku();
    $testNature = testNaturebyId($natureID);
    if ($testNature) {
        $StockAliments->setDesignation_lot($designation);
        $StockAliments->setQuantite($quantite);
        $StockAliments->setDate($date);
        $StockAliments->setEtat($etat);
        $StockAliments->setNatures_idNature($natureID);
        $StockAliments->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $StockAlimentsModel->create($StockAliments);
        $message = "Stock Aliments  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STOCK_ALIMENT);
        return success201($message);
    }
}

#Delete
function deleteStockAliments($StockAlimentsParams)
{
    $StockAlimentsModel = new Stock_alimentsModel();
    paramsVerify($StockAlimentsParams, "Stock Aliment");

    $stockAlimentsID = $StockAlimentsParams['id'];
    $StockAlimentsData = $StockAlimentsModel->find($stockAlimentsID);

    if ($stockAlimentsID == $StockAlimentsData->id) {
        $StockAlimentsModel->delete($stockAlimentsID);
        $message = "Stock Aliment deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STOCK_ALIMENT);
        return success200($message);
    } else {
        $message = "Stock Aliment not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STOCK_ALIMENT);
        return error405($message);
    }
}

#Get
function getStockAlimentsById($StockAlimentsParams)
{
    $StockAlimentsModel = new Stock_alimentsModel();
    paramsVerify($StockAlimentsParams, "Stock Aliment");
    $stockAlimentsFound = $StockAlimentsModel->find($StockAlimentsParams['id']);

    if (!empty($stockAlimentsFound)) {
        $dataSA = getStockAlimentDataById($stockAlimentsFound->id);
        $message = "Stock Aliments Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STOCK_ALIMENT);
        return datasuccess200($message, $dataSA);
    } else {
        $message = "No Stock Aliments Found";
        return success205($message);
    }
}

function getListStockAliments()
{
    $StockAlimentsModel = new Stock_alimentsModel();
    $StockAliments = (array)$StockAlimentsModel->findAll();

    if (!empty($StockAliments)) {
        $dataSAlist = getListStockAlimentDataById($StockAliments);
        $message = "Situation Stock Aliments";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STOCK_ALIMENT);
        return dataTableSuccess200($message, $dataSAlist);
    } else {
        $message = "Pas de situation dans le Stock Aliments";
        return success205($message);
    }
}

# Update
function updateStockAliments($StockAlimentsData, $StockAlimentsParams)
{
    $StockAlimentsModel = new Stock_alimentsModel();
    $StockAliments = $StockAlimentsModel;
    paramsVerify($StockAlimentsParams, "Stock Aliment");

    # On recupere les informations venues de POST
    $stockAlimentsID = $StockAlimentsParams['id'];

    $designation = $StockAlimentsData['designation_lot'];
    $quantite = $StockAlimentsData['quantite'];
    $date = $StockAlimentsData["date"];
    $etat = $StockAlimentsData["etat"];
    $natureID = $StockAlimentsData['natures_idNature'];
    $today = getSiku();

    $testNature = testNaturebyId($natureID);
    if ($testNature) {
        $StockAliments->setDesignation_lot($designation);
        $StockAliments->setQuantite($quantite);
        $StockAliments->setDate($date);
        $StockAliments->setEtat($etat);
        $StockAliments->setNatures_idNature($natureID);
        $StockAliments->setUpdated_at($today);

        $stockAlimentsFound = $StockAlimentsModel->find($stockAlimentsID);

        if ($stockAlimentsID == $stockAlimentsFound->id) {
            $StockAlimentsModel->update($stockAlimentsID, $StockAliments);
            # On ajoute l'Adresse  dans la BD
            $message = "Stock Aliment updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STOCK_ALIMENT);
            return success200($message);
        } else {
            $message = "No Stock Aliment Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_STOCK_ALIMENT);
            return error404($message);
        }
    }
}