<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Status_operationsModel;

Autoloader::register();

# Store
function storeStatus($statusOperationData)
{
    $statusOperationsModel = new Status_operationsModel();
    $statusOperation = $statusOperationsModel;

    # On recupere les informations venues de POST
    if (empty(trim($statusOperationData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $statusOperationData["designation"];
        $test = isExistStatusByDesignation($designation);

        if ($test) {
            $statusOperation->setDesignation($designation);
            $statusOperation->setStatus(true);
            $statusOperation->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $statusOperationsModel->create($statusOperation);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STATUS_OPER);
            $message = "Status  created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteStatus($statusOperationParams)
{
    $statusOperationsModel = new Status_operationsModel();
    paramsVerify($statusOperationParams, "Status");

    $statusOperationID = $statusOperationParams['id'];
    $statusOperationData = $statusOperationsModel->find($statusOperationID);

    if ($statusOperationID == $statusOperationData->id) {
        $res = $statusOperationsModel->delete($statusOperationID);
        $message = "Status deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STATUS_OPER);
        return success200($message);
    } else {
        $message = "Status not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STATUS_OPER);
        return error405($message);
    }
}

#Get
function getStatusById($statusOperationParams)
{
    $statusOperationsModel = new Status_operationsModel();
    paramsVerify($statusOperationParams, "Status");
    $statusOperationFound = $statusOperationsModel->find($statusOperationParams['id']);

    if (!empty($statusOperationFound)) {
        $message = "Status Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STATUS_OPER);
        return datasuccess200($message, $statusOperationFound);
    } else {
        $message = "No Status Found";
        return success205($message);
    }
}

function getListStatus()
{
    $statusOperationsModel = new Status_operationsModel();
    $data = array(
        "status" => true,
    );
    $statusOperation = (array)$statusOperationsModel->findBy($data);

    if (!empty($statusOperation)) {
        $message = "Liste des Status";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STATUS_OPER);
        return dataTableSuccess200($message, $statusOperation);
    } else {
        $message = "Pas de Status";
        return success205($message);
    }
}
function getListStatusAll()
{
    $statusOperationsModel = new Status_operationsModel();
    $statusOperation = (array)$statusOperationsModel->findAll();

    if (!empty($statusOperation)) {
        $message = "Liste des Status";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STATUS_OPER);
        return dataTableSuccess200($message, $statusOperation);
    } else {
        $message = "Pas de Status";
        return success205($message);
    }
}


#Archive
function archiveStatus($statusOperationParams)
{
    $statusOperationsModel = new Status_operationsModel();
    $statusOperation = $statusOperationsModel;
    paramsVerify($statusOperationParams, "Status");

    $statusOperationID = $statusOperationParams['id'];
    $statusOperationData = $statusOperationsModel->find($statusOperationID);

    if ($statusOperationID == $statusOperationData->id) {
        $statusOperation->setStatus(false);
        $statusOperation->setUpdated_at(getSiku());
        $statusOperationsModel->update($statusOperationID, $statusOperation);
        $message = "Status Archive successfully";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_STATUS_OPER);
        return success200($message);
    } else {
        $message = "Status not Archive  ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_STATUS_OPER);
        return error405($message);
    }
}

# Update
function updateStatus($statusOperationData, $statusOperationParams)
{
    $statusOperationsModel = new Status_operationsModel();
    $statusOperation = $statusOperationsModel;
    paramsVerify($statusOperationParams, "Status");

    # On recupere les informations venues de POST
    if (empty(trim($statusOperationData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $statusOperationData["designation"];
        $status = $statusOperationData["status"];
        $statusOperationID = $statusOperationParams['id'];

        $statusOperationFound = $statusOperationsModel->find($statusOperationID);

        $test = isExistStatusByDesignationUpdate($designation, $statusOperationID);
        $statusOperation->setDesignation($designation);
        $statusOperation->setStatus($status);
        $statusOperation->setUpdated_at(getSiku());
        if ($test) {

            if ($statusOperationID == $statusOperationFound->id) {
                $statusOperationsModel->update($statusOperationID, $statusOperation);
                # On ajoute l'Adresse  dans la BD
                $message = "Status updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STATUS_OPER);
                return success200($message);
            } else {
                $message = "No Status Found ";
                return success205($message);
            }
        }
    }
}


function isExistStatusByDesignation($designation)
{
    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $statusOperationModel = new Status_operationsModel();
    $statusData = (object)$statusOperationModel->findBy($data);

    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}
function isExistStatusByDesignationUpdate($designation, $id)
{

    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $statusOperationModel = new Status_operationsModel();
    $statusData = $statusOperationModel->findBy($data);
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