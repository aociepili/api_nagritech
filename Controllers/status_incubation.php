<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Status_incubationsModel;

Autoloader::register();

# Store
function storestatusInc($statusIncData)
{

    $statusIncubationModel = new Status_incubationsModel();
    $statusIncubation = $statusIncubationModel;

    # On recupere les informations venues de POST
    if (empty(trim($statusIncData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $statusIncData["designation"];
        $test = isExistStatusIncByDesignation($designation);

        if ($test) {
            $statusIncubation->setDesignation($designation);
            $statusIncubation->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $statusIncubationModel->create($statusIncubation);
            $message = "Status Incubations created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_STATUS_INC);
            return success201($message);
        }
    }
}

#Delete
function deletestatusInc($statusIncParams)
{
    $statusIncubationModel = new Status_incubationsModel();
    paramsVerify($statusIncParams, "Status Incubations");

    $statusIncID = $statusIncParams['id'];
    $statusIncData = $statusIncubationModel->find($statusIncID);

    if ($statusIncID == $statusIncData->id) {
        $res = $statusIncubationModel->delete($statusIncID);
        $message = "Status Incubations deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_STATUS_INC);
        return success200($message);
    } else {
        $message = "Status Incubations not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_STATUS_INC);
        return error405($message);
    }
}

#Get
function getstatusIncById($statusIncParams)
{
    $statusIncubationModel = new Status_incubationsModel();
    paramsVerify($statusIncParams, "Status Incubations");
    $statusIncFound = $statusIncubationModel->find($statusIncParams['id']);

    if (!empty($statusIncFound)) {
        $message = "Status Incubations Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_STATUS_INC);
        return datasuccess200($message, $statusIncFound);
    } else {
        $message = "No Status Incubations Rapport Found";
        return success205($message);
    }
}

function getListstatusInc()
{
    $statusIncubationModel = new Status_incubationsModel();
    $statusIncubation = (array)$statusIncubationModel->findAll();

    if (!empty($statusIncubation)) {
        $message = "Liste des Status Incubations";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_STATUS_INC);
        return dataTableSuccess200($message, $statusIncubation);
    } else {
        $message = "Pas de Status Incubations";
        return success205($message);
    }
}

# Update
function updatestatusInc($statusIncData, $statusIncParams)
{
    $statusIncubationModel = new Status_incubationsModel();
    $statusIncubation = $statusIncubationModel;
    paramsVerify($statusIncParams, "Status Incubations");

    # On recupere les informations venues de POST
    if (empty(trim($statusIncData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $statusIncData["designation"];
        $statusIncID = $statusIncParams['id'];

        $statusIncFound = $statusIncubationModel->find($statusIncID);

        $test = isExistStatusIncByDesignationUpdate($designation, $statusIncID);
        $statusIncubation->setDesignation($designation);
        $statusIncubation->setUpdated_at(getSiku());
        if ($test) {

            if ($statusIncID == $statusIncFound->id) {
                $statusIncubationModel->update($statusIncID, $statusIncubation);
                # On ajoute l'Adresse  dans la BD
                $message = "Status Incubations updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_STATUS_INC);
                return success200($message);
            } else {
                $message = "No Status Incubations Rapport Found ";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_STATUS_INC);
                return success205($message);
            }
        }
    }
}


function isExistStatusIncByDesignation($designation)
{
    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $statusIncubationModel = new Status_incubationsModel();
    $statusData = (object)$statusIncubationModel->findBy($data);

    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}
function isExistStatusIncByDesignationUpdate($designation, $id)
{

    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $statusIncubationModel = new Status_incubationsModel();
    $statusData = $statusIncubationModel->findBy($data);
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