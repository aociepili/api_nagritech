<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\ServicesModel;

Autoloader::register();
# Services

# Store
function storeService($serviceData)
{
    $serviceModel = new ServicesModel();
    $service = $serviceModel;

    chargementService($serviceData);

    $designation = $serviceData["designation"];
    $abrege = $serviceData["abrege"];
    $today = getSiku();

    $test = isExistServiceDesignation($designation);
    if ($test) {
        $service->setDesignation($designation);
        $service->setAbrege($abrege);
        $service->setStatus(true);
        $service->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $serviceModel->create($service);
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_SERVICE);
        $message = "Service created successfully";
        return success201($message);
    }
}

#Delete
function deleteService($serviceParams)
{
    $serviceModel = new ServicesModel();
    paramsVerify($serviceParams, "Service");

    # On recupere les informations venues de POST
    $serviceID = $serviceParams['id'];
    $serviceFoundData = $serviceModel->find($serviceID);

    if ($serviceID == $serviceFoundData->id) {
        try {
            $serviceModel->delete($serviceID);
            $message = "Service deleted successfully";
            createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_SERVICE);
            return success200($message);
        } catch (\Throwable $th) {
            $message = "Impossible de supprimer ce Service ";
            createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_SERVICE);
            return error405($message);
        }
    } else {
        $message = "Service not delete ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_SERVICE);
        return error405($message);
    }
}

#Get
function getServicebyId($serviceParams)
{
    $serviceModel = new ServicesModel();
    paramsVerify($serviceParams, "Service");
    $res = $serviceModel->find($serviceParams['id']);

    if (!empty($res)) {
        $message = "Service Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_SERVICE);
        return datasuccess200($message, $res);
    } else {
        $message = "Service not found";
        return success205($message);
    }
}


function changeStatusService($serviceParams)
{
    $serviceModel = new ServicesModel();
    $service = $serviceModel;
    paramsVerify($serviceParams, "service");
    $res = $serviceModel->find($serviceParams['id']);
    $status = $res->status;
    if (!empty($res)) {
        switch ($status) {
            case '0': {
                    # Active...
                    $service->setStatus(True);
                    $service->setUpdated_at(getSiku());
                    $serviceModel->update($serviceParams['id'], $service);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_SERVICE);
                    $message = "service activated successfully";
                    return success200($message);
                }

            case '1': {
                    # Desactive...
                    $service->setStatus(false);
                    $service->setUpdated_at(getSiku());
                    $serviceModel->update($serviceParams['id'], $service);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_SERVICE);
                    $message = "service desactivated successfully";
                    return success200($message);
                }
            default:
                # code...
                $message = "No Service Found";
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_SERVICE);
                success205($message);
                break;
        }
    } else {
        $message = "No Service Found";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_SERVICE);
        success205($message);
    }
}


function getListService()
{
    $serviceModel = new ServicesModel();
    $services = (array)$serviceModel->findAll();

    if (!empty($services)) {
        $message = "Liste des Services ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_SERVICE);
        return dataTableSuccess200($message, $services);
    } else {
        $message = "Pas de categorie Admin";
        return success205($message);
    }
}

# Update
function updateService($serviceData, $serviceParams)
{
    $serviceModel = new ServicesModel();
    $service = $serviceModel;


    paramsVerify($serviceParams, "Service");

    $designation = $serviceData["designation"];
    $abrege = $serviceData["abrege"];
    $serviceID = $serviceParams['id'];
    $today = getSiku();

    $service->setDesignation($designation);
    $service->setAbrege($abrege);
    $service->setUpdated_at($today);
    $serviceFoundData = $serviceModel->find($serviceID);

    # test de l'existence de la designation dans la BD
    $test = isExistServiceDesignationUpdate($designation, $serviceID);

    if ($test) {

        if ($serviceID == $serviceFoundData->id) {
            $serviceModel->update($serviceID, $service);
            # On ajoute l'Adresse  dans la BD
            $message = "Service updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_SERVICE);
            return success200($message);
        } else {
            $message = "No Service Found";
            return success205($message);
        }
    }
}

function getServiceByDesignation($designation)
{

    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataService = array(
        "designation" => $designation,
    );
    $serviceModel = new ServicesModel();
    $dataS = (object)$serviceModel->findBy($DataService);

    if ($designation == $dataS->designation) {
        $test = 1;
    }

    return $test;
}

function isExistServiceDesignation($designation)
{

    $test = false;
    $dataDesignation = array(
        "designation" => $designation,
    );

    $serviceModel = new ServicesModel();
    $service = (object)$serviceModel->findBy($dataDesignation);

    if (empty((array)$service)) {
        # Admin n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette designation existe deja");
    }
}
function isExistServiceDesignationUpdate($designation, $id)
{

    $test = false;
    $dataDesignation = array(
        "designation" => $designation,
    );

    $serviceModel = new ServicesModel();
    $service = $serviceModel->findBy($dataDesignation);


    if (empty((array)$service)) {
        # Admin n'existe pas
        $test = true;
        return $test;
    } else {

        if ($id == $service[0]->id) {

            $test = true;
            return $test;
        } else {
            success203("Cette designation existe deja");
        }
    }
}