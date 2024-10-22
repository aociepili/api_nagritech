<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Status_rapportsModel;

Autoloader::register();

# Store
function storeStatus($statusRapportData)
{
    $statusRapportsModel = new Status_rapportsModel();
    $statusRapport = $statusRapportsModel;

    # On recupere les informations venues de POST
    if (empty(trim($statusRapportData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $statusRapportData["designation"];
        $test = isExistStatusByDesignation($designation);

        if ($test) {
            $statusRapport->setDesignation($designation);
            $statusRapport->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $statusRapportsModel->create($statusRapport);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STATUS_RAPP);
            $message = "Status  created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteStatus($statusRapportParams)
{
    $statusRapportsModel = new Status_rapportsModel();
    paramsVerify($statusRapportParams, "Status");

    $statusRapportID = $statusRapportParams['id'];
    $statusRapportData = $statusRapportsModel->find($statusRapportID);

    if ($statusRapportID == $statusRapportData->id) {
        $res = $statusRapportsModel->delete($statusRapportID);
        $message = "Status deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STATUS_RAPP);
        return success200($message);
    } else {
        $message = "Status not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STATUS_RAPP);
        return error405($message);
    }
}

#Get
function getStatusById($statusRapportParams)
{
    $statusRapportsModel = new Status_rapportsModel();
    paramsVerify($statusRapportParams, "Status");
    $statusRapportFound = $statusRapportsModel->find($statusRapportParams['id']);

    if (!empty($statusRapportFound)) {
        $message = "Status Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STATUS_RAPP);
        return datasuccess200($message, $statusRapportFound);
    } else {
        $message = "No status Rapport Found";
        return success205($message);
    }
}

function getListStatus()
{
    $statusRapportsModel = new Status_rapportsModel();
    $statusRapport = (array)$statusRapportsModel->findAll();

    if (!empty($statusRapport)) {
        $message = "Liste des Status Rapport";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STATUS_RAPP);
        return dataTableSuccess200($message, $statusRapport);
    } else {
        $message = "Pas de Status Rapport";
        return success205($message);
    }
}

# Update
function updateStatus($statusRapportData, $statusRapportParams)
{
    $statusRapportsModel = new Status_rapportsModel();
    $statusRapport = $statusRapportsModel;
    paramsVerify($statusRapportParams, "Status");

    # On recupere les informations venues de POST
    if (empty(trim($statusRapportData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $statusRapportData["designation"];
        $statusRapportID = $statusRapportParams['id'];


        $statusRapportFound = $statusRapportsModel->find($statusRapportID);

        $test = isExistStatusByDesignationUpdate($designation, $statusRapportID);
        $statusRapport->setDesignation($designation);
        $statusRapport->setUpdated_at(getSiku());
        if ($test) {

            if ($statusRapportID == $statusRapportFound->id) {
                $statusRapportsModel->update($statusRapportID, $statusRapport);
                # On ajoute l'Adresse  dans la BD
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STATUS_RAPP);
                $message = "Status updated successfully";
                return success200($message);
            } else {
                $message = "No Status Rapport Found ";
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

    $statusRapportModel = new Status_rapportsModel();
    $statusData = (object)$statusRapportModel->findBy($data);

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

    $statusRapportModel = new Status_rapportsModel();
    $statusData = $statusRapportModel->findBy($data);
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