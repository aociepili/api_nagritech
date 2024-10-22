<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Tranche_agesModel;

Autoloader::register();

# Store
function storeTrancheAge($trancheAgeData)
{
    $trancheAgesModel = new Tranche_agesModel();
    $trancheAge = $trancheAgesModel;

    # On recupere les informations venues de POST
    if (empty(trim($trancheAgeData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {

        $designation = $trancheAgeData["designation"];
        $test = isExistTrancheAgeByDesignation($designation);

        if ($test) {
            $trancheAge->setDesignation($designation);
            $trancheAge->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $trancheAgesModel->create($trancheAge);
            $message = "tranche Age created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_TRANCHE_AGE);
            return success201($message);
        }
    }
}

#Delete
function deleteTrancheAge($trancheAgeParams)
{
    $trancheAgesModel = new Tranche_agesModel();
    paramsVerify($trancheAgeParams, "tranche Age");

    $trancheAgeID = $trancheAgeParams['id'];
    $trancheAgeData = $trancheAgesModel->find($trancheAgeID);

    if ($trancheAgeID == $trancheAgeData->id) {
        $res = $trancheAgesModel->delete($trancheAgeID);
        $message = "tranche Age deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_TRANCHE_AGE);
        return success200($message);
    } else {
        $message = "tranche Age not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_TRANCHE_AGE);
        return error405($message);
    }
}

#Get
function getTrancheAgeById($trancheAgeParams)
{
    $trancheAgesModel = new Tranche_agesModel();
    paramsVerify($trancheAgeParams, "tranche Age");
    $trancheAgeFound = $trancheAgesModel->find($trancheAgeParams['id']);

    if (!empty($trancheAgeFound)) {
        $message = "tranche Age Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_TRANCHE_AGE);
        return datasuccess200($message, $trancheAgeFound);
    } else {
        $message = "No status Commande Rapport Found";
        return success205($message);
    }
}

function getListTrancheAge()
{
    $trancheAgesModel = new Tranche_agesModel();
    $trancheAge = (array)$trancheAgesModel->findAll();

    if (!empty($trancheAge)) {
        $message = "Liste des tranche Age";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_TRANCHE_AGE);
        return dataTableSuccess200($message, $trancheAge);
    } else {
        $message = "Pas de tranche Age";
        return success205($message);
    }
}

# Update
function updateTrancheAge($trancheAgeData, $trancheAgeParams)
{
    $trancheAgesModel = new Tranche_agesModel();
    $trancheAge = $trancheAgesModel;
    paramsVerify($trancheAgeParams, "tranche Age");

    # On recupere les informations venues de POST
    if (empty(trim($trancheAgeData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $trancheAgeData["designation"];
        $trancheAgeID = $trancheAgeParams['id'];

        $trancheAgeFound = $trancheAgesModel->find($trancheAgeID);

        $test = isExistTrancheAgeByDesignationUpdate($designation, $trancheAgeID);
        $trancheAge->setDesignation($designation);
        $trancheAge->setUpdated_at(getSiku());
        if ($test) {

            if ($trancheAgeID == $trancheAgeFound->id) {
                $trancheAgesModel->update($trancheAgeID, $trancheAge);
                # On ajoute l'Adresse  dans la BD
                $message = "tranche Age updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_TRANCHE_AGE);
                return success200($message);
            } else {
                $message = "No tranche Age Rapport Found ";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_TRANCHE_AGE);
                return success205($message);
            }
        }
    }
}


function isExistTrancheAgeByDesignation($designation)
{
    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $trancheAgesModel = new Tranche_agesModel();
    $statusData = (object)$trancheAgesModel->findBy($data);

    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}
function isExistTrancheAgeByDesignationUpdate($designation, $id)
{

    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $trancheAgesModel = new Tranche_agesModel();
    $statusData = $trancheAgesModel->findBy($data);
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