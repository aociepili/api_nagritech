<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Table_operationsModel;

Autoloader::register();

# Store
function storeTable($tableOperationData)
{

    $tableOperationsModel = new Table_operationsModel();
    $tableOperation = $tableOperationsModel;

    # On recupere les informations venues de POST
    if (empty(trim($tableOperationData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $tableOperationData["designation"];
        $test = isExistTableByDesignation($designation);

        if ($test) {
            $tableOperation->setDesignation($designation);
            $tableOperation->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $tableOperationsModel->create($tableOperation);
            $message = "Table  created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_OPERATION);
            return success201($message);
        }
    }
}

#Delete
function deleteTable($tableOperationParams)
{
    $tableOperationsModel = new Table_operationsModel();
    paramsVerify($tableOperationParams, "Table");

    $tableOperationID = $tableOperationParams['id'];
    $tableOperationData = $tableOperationsModel->find($tableOperationID);

    if ($tableOperationID == $tableOperationData->id) {
        $res = $tableOperationsModel->delete($tableOperationID);
        $message = "Table deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_OPERATION);
        return success200($message);
    } else {
        $message = "Table not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_OPERATION);
        return error405($message);
    }
}

#Get
function getTableById($tableOperationParams)
{
    $tableOperationsModel = new Table_operationsModel();
    paramsVerify($tableOperationParams, "Status");
    $tableOperationFound = $tableOperationsModel->find($tableOperationParams['id']);

    if (!empty($tableOperationFound)) {
        $message = "Table Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_OPERATION);
        return datasuccess200($message, $tableOperationFound);
    } else {
        $message = "No table Rapport Found";
        return success205($message);
    }
}

function getListTableAll()
{
    $tableOperationsModel = new Table_operationsModel();
    $tableOperation = (array)$tableOperationsModel->findAll();

    if (!empty($tableOperation)) {
        $message = "Liste des Table operation";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_OPERATION);
        return dataTableSuccess200($message, $tableOperation);
    } else {
        $message = "Pas de Table operation";
        return success205($message);
    }
}
function getListTable()
{
    $tableOperationsModel = new Table_operationsModel();
    $data = array(
        "status" => true,
    );
    $tableOperation = (array)$tableOperationsModel->findBy($data);

    if (!empty($tableOperation)) {
        $message = "Liste des Table Operation";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_OPERATION);
        return dataTableSuccess200($message, $tableOperation);
    } else {
        $message = "Pas de Table Operation";
        return success205($message);
    }
}

function archiveTable($tableOperationParams)
{
    $tableOperationsModel = new Table_operationsModel();
    $tableOperation = $tableOperationsModel;
    paramsVerify($tableOperationParams, "Status");

    $tableOperationID = $tableOperationParams['id'];
    $tableOperationData = $tableOperationsModel->find($tableOperationID);

    if ($tableOperationID == $tableOperationData->id) {
        $tableOperation->setStatus(false);
        $tableOperation->setUpdated_at(getSiku());
        $tableOperationsModel->update($tableOperationID, $tableOperation);
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_OPERATION);
        $message = "Table Archive successfully";
        return success200($message);
    } else {
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_OPERATION);
        $message = "Table not Archive  ";
        return error405($message);
    }
}
function activeTable($tableOperationParams)
{
    $tableOperationsModel = new Table_operationsModel();
    $tableOperation = $tableOperationsModel;
    paramsVerify($tableOperationParams, "Status");

    $tableOperationID = $tableOperationParams['id'];
    $tableOperationData = $tableOperationsModel->find($tableOperationID);

    if ($tableOperationID == $tableOperationData->id) {
        $tableOperation->setStatus(true);
        $tableOperation->setUpdated_at(getSiku());
        $tableOperationsModel->update($tableOperationID, $tableOperation);
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_OPERATION);
        $message = "Table Archive successfully";
        return success200($message);
    } else {
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_OPERATION);
        $message = "Table not Archive  ";
        return error405($message);
    }
}

# Update
function updateTable($tableOperationData, $tableOperationParams)
{
    $tableOperationsModel = new Table_operationsModel();
    $tableOperation = $tableOperationsModel;
    paramsVerify($tableOperationParams, "Status");

    # On recupere les informations venues de POST
    if (empty(trim($tableOperationData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $tableOperationData["designation"];
        $tableOperationID = $tableOperationParams['id'];


        $tableOperationFound = $tableOperationsModel->find($tableOperationID);

        $test = isExistTableByDesignationUpdate($designation, $tableOperationID);
        $tableOperation->setDesignation($designation);
        $tableOperation->setUpdated_at(getSiku());
        if ($test) {

            if ($tableOperationID == $tableOperationFound->id) {
                $tableOperationsModel->update($tableOperationID, $tableOperation);
                # On ajoute l'Adresse  dans la BD
                $message = "Table updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_OPERATION);
                return success200($message);
            } else {
                $message = "No Table Found ";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_OPERATION);
                return success205($message);
            }
        }
    }
}


function isExistTableByDesignation($designation)
{
    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $tableOperationModel = new Table_operationsModel();
    $statusData = (object)$tableOperationModel->findBy($data);

    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}
function isExistTableByDesignationUpdate($designation, $id)
{

    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $tableOperationModel = new Table_operationsModel();
    $statusData = $tableOperationModel->findBy($data);
    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        if ($id == $statusData[0]->id) {
            $test = true;
            return $test;
        } else {
            success203(" Cette Designation existe deja");
        }
    }
}