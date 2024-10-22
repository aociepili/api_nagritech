<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Type_operationsModel;

Autoloader::register();

# Store
function storeTypeOperation($typeOperationData)
{
    $typeOperationsModel = new Type_operationsModel();
    $typeOperation = $typeOperationsModel;

    # On recupere les informations venues de POST
    if (empty(trim($typeOperationData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $typeOperationData["designation"];
        $test = isExistTypeByDesignation($designation);

        if ($test) {
            $typeOperation->setDesignation($designation);
            $typeOperation->setStatus(true);
            $typeOperation->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $typeOperationsModel->create($typeOperation);
            $message = "Type operation  created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_TYPE_OP);
            return success201($message);
        }
    }
}

#Delete
function deleteTypeOperation($typeOperationParams)
{
    $typeOperationsModel = new Type_operationsModel();
    paramsVerify($typeOperationParams, "Type Operation");

    $typeOperationID = $typeOperationParams['id'];
    $typeOperationData = $typeOperationsModel->find($typeOperationID);

    if ($typeOperationID == $typeOperationData->id) {
        $typeOperationsModel->delete($typeOperationID);
        $message = "Type Operation deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_TYPE_OP);
        return success200($message);
    } else {
        $message = "Type Operation not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_TYPE_OP);
        return success205($message);
    }
}

#Get
function getTypeOperationById($typeOperationParams)
{
    $typeOperationsModel = new Type_operationsModel();
    paramsVerify($typeOperationParams, "Type Operation");
    $typeOperationFound = $typeOperationsModel->find($typeOperationParams['id']);

    if (!empty($typeOperationFound)) {
        $message = "Type Operation Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_TYPE_OP);
        return datasuccess200($message, $typeOperationFound);
    } else {
        $message = "No Type Operation Found";
        return success205($message);
    }
}

function getListTypeOperationAll()
{
    $typeOperationsModel = new Type_operationsModel();
    $typeOperation = (array)$typeOperationsModel->findAll();

    if (!empty($typeOperation)) {
        $message = "Liste des Type Operation";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_TYPE_OP);
        return dataTableSuccess200($message, $typeOperation);
    } else {
        $message = "Pas de Type Operation";
        return success205($message);
    }
}
function getListTypeOperation()
{
    $typeOperationsModel = new Type_operationsModel();
    $data = array(
        "status" => true,
    );
    $typeOperation = (array)$typeOperationsModel->findBy($data);

    if (!empty($typeOperation)) {
        $message = "Liste des Type Operation";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_TYPE_OP);
        return dataTableSuccess200($message, $typeOperation);
    } else {
        $message = "Pas de Type Operation";
        return success205($message);
    }
}


#Archive
function archiveTypeOperation($typeOperationParams)
{
    $typeOperationsModel = new Type_operationsModel();
    $typeOperation = $typeOperationsModel;
    paramsVerify($typeOperationParams, "Type Operation");

    $typeOperationID = $typeOperationParams['id'];
    $typeOperationData = $typeOperationsModel->find($typeOperationID);

    if ($typeOperationID == $typeOperationData->id) {
        $typeOperation->setStatus(false);
        $typeOperation->setUpdated_at(getSiku());
        $typeOperationsModel->update($typeOperationID, $typeOperation);
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_TYPE_OP);
        $message = "Type Operation Archive successfully";
        return success200($message);
    } else {
        $message = "Type Operation not Archive  ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_TYPE_OP);
        return success205($message);
    }
}

# Update
function updateTypeOperation($typeOperationData, $typeOperationParams)
{
    $typeOperationsModel = new Type_operationsModel();
    $typeOperation = $typeOperationsModel;
    paramsVerify($typeOperationParams, "Status");

    # On recupere les informations venues de POST
    if (empty(trim($typeOperationData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $typeOperationData["designation"];
        $status = $typeOperationData["status"];
        $typeOperationID = $typeOperationParams['id'];

        $typeOperationFound = $typeOperationsModel->find($typeOperationID);

        $test = isExistTypeByDesignationUpdate($designation, $typeOperationID);
        $typeOperation->setDesignation($designation);
        $typeOperation->setStatus($status);
        $typeOperation->setUpdated_at(getSiku());
        if ($test) {

            if ($typeOperationID == $typeOperationFound->id) {
                $typeOperationsModel->update($typeOperationID, $typeOperation);
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_TYPE_OP);
                # On ajoute l'Adresse  dans la BD
                $message = "Status updated successfully";
                return success200($message);
            } else {
                createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_TYPE_OP);
                $message = "No Status Found ";
                return success205($message);
            }
        }
    }
}


function isExistTypeByDesignation($designation)
{
    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $typeOperationModel = new Type_operationsModel();
    $statusData = (object)$typeOperationModel->findBy($data);

    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}
function isExistTypeByDesignationUpdate($designation, $id)
{

    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $typeOperationModel = new Type_operationsModel();
    $statusData = $typeOperationModel->findBy($data);
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