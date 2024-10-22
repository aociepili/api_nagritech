<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Status_commandesModel;

Autoloader::register();

# Store
function storeStatusCmd($statusCmdData)
{

    $statusCmdModel = new Status_commandesModel();
    $statusCmd = $statusCmdModel;

    # On recupere les informations venues de POST
    if (empty(trim($statusCmdData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $statusCmdData["designation"];
        $test = isExistStatusCmdByDesignation($designation);

        if ($test) {
            $statusCmd->setDesignation($designation);
            $statusCmd->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $statusCmdModel->create($statusCmd);
            $message = "Status Commande created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STATUS_CMD);
            return success201($message);
        }
    }
}

#Delete
function deleteStatusCmd($statusCmdParams)
{
    $statusCmdModel = new Status_commandesModel();
    paramsVerify($statusCmdParams, "Status Commande");

    $statusCmdID = $statusCmdParams['id'];
    $statusCmdData = $statusCmdModel->find($statusCmdID);

    if ($statusCmdID == $statusCmdData->id) {
        $res = $statusCmdModel->delete($statusCmdID);
        $message = "Status Commande deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STATUS_CMD);
        return success200($message);
    } else {
        $message = "Status Commande not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STATUS_CMD);
        return error405($message);
    }
}

#Get
function getStatusCmdById($statusCmdParams)
{
    $statusCmdModel = new Status_commandesModel();
    paramsVerify($statusCmdParams, "Status Commande");
    $statusCmdFound = $statusCmdModel->find($statusCmdParams['id']);

    if (!empty($statusCmdFound)) {
        $message = "Status Commande Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STATUS_CMD);
        return datasuccess200($message, $statusCmdFound);
    } else {
        $message = "No status Commande Rapport Found";
        return success205($message);
    }
}

function getListStatusCmd()
{
    $statusCmdModel = new Status_commandesModel();
    $statusCmd = (array)$statusCmdModel->findAll();

    if (!empty($statusCmd)) {
        $message = "Liste des Status Commande";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STATUS_CMD);
        return dataTableSuccess200($message, $statusCmd);
    } else {
        $message = "Pas de Status Commande";
        return success205($message);
    }
}

# Update
function updateStatusCmd($statusCmdData, $statusCmdParams)
{
    $statusCmdModel = new Status_commandesModel();
    $statusCmd = $statusCmdModel;
    paramsVerify($statusCmdParams, "Status Commande");

    # On recupere les informations venues de POST
    if (empty(trim($statusCmdData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $statusCmdData["designation"];
        $statusCmdID = $statusCmdParams['id'];

        $statusCmdFound = $statusCmdModel->find($statusCmdID);

        $test = isExistStatusCmdByDesignationUpdate($designation, $statusCmdID);
        $statusCmd->setDesignation($designation);
        $statusCmd->setUpdated_at(getSiku());
        if ($test) {

            if ($statusCmdID == $statusCmdFound->id) {
                $statusCmdModel->update($statusCmdID, $statusCmd);
                # On ajoute l'Adresse  dans la BD
                $message = "Status Commande updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STATUS_CMD);
                return success200($message);
            } else {
                $message = "No Status Commande Rapport Found ";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_STATUS_CMD);
                return success205($message);
            }
        }
    }
}


function isExistStatusCmdByDesignation($designation)
{
    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $statusCmdModel = new Status_commandesModel();
    $statusData = (object)$statusCmdModel->findBy($data);

    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}
function isExistStatusCmdByDesignationUpdate($designation, $id)
{

    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $statusCmdModel = new Status_commandesModel();
    $statusData = $statusCmdModel->findBy($data);
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