<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Categorie_adminsModel;

Autoloader::register();

# Store
function storeCatAdmin($catAdminData)
{
    $catAdminModel = new Categorie_adminsModel();
    $catAdmin = $catAdminModel;

    # On recupere les informations venues de POST
    if (empty(trim($catAdminData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $catAdminData["designation"];
        $status = true;
        $today = getSiku();
        $test = isExistDesignation($designation);

        if ($test) {
            $catAdmin->setDesignation($designation);
            $catAdmin->setStatus($status);
            $catAdmin->setCreated_at($today);
            # On ajoute la Designation dans la BD
            $catAdminModel->create($catAdmin);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CAT_ADMIN);
            $message = "category Admin created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteCatAdmin($catAdminParams)
{
    $catAdminModel = new Categorie_adminsModel();
    paramsVerify($catAdminParams, "category Admin");

    # On recupere les informations venues de POST
    $catAdminID = $catAdminParams['id'];
    $catAdminData = $catAdminModel->find($catAdminID);

    if ($catAdminID == $catAdminData->id) {
        try {
            $catAdminModel->delete($catAdminID);
            createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CAT_ADMIN);
            $message = "CatAdmin deleted successfully";
            return success200($message);
        } catch (\Throwable $th) {
            $message = "CatAdmin not delete this adresse ";
            return success205($message);
        }
    } else {
        $message = "CatAdmin not delete ";
        return success205($message);
    }
}

#Get
function getCatAdminbyId($catAdminParams)
{
    $catAdminModel = new Categorie_adminsModel();
    paramsVerify($catAdminParams, "category Admin");
    $res = $catAdminModel->find($catAdminParams['id']);

    if (!empty($res)) {
        $message = "category Admin Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CAT_ADMIN);
        return datasuccess200($message, $res);
    } else {
        $message = "No catAdmin Found";
        success205($message);
    }
}
function changeStatusCatAdmin($catAdminParams)
{
    $catAdminModel = new Categorie_adminsModel();
    $catAdmin = $catAdminModel;
    paramsVerify($catAdminParams, "category Admin");
    $res = $catAdminModel->find($catAdminParams['id']);
    $status = $res->status;
    if (!empty($res)) {
        switch ($status) {
            case '0': {
                    # Active...
                    $catAdmin->setStatus(True);
                    $catAdmin->setUpdated_at(getSiku());
                    $catAdminModel->update($catAdminParams['id'], $catAdmin);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CAT_ADMIN);
                    $message = "category Admin activated successfully";
                    return success200($message);
                }

            case '1': {
                    # Desactive...
                    $catAdmin->setStatus(false);
                    $catAdmin->setUpdated_at(getSiku());
                    $catAdminModel->update($catAdminParams['id'], $catAdmin);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CAT_ADMIN);
                    $message = "category Admin desactivated successfully";
                    return success200($message);
                }
            default:
                # code...
                $message = "No catAdmin Found";
                success205($message);
                break;
        }
        $message = "category Admin Fetched successfully";
        return datasuccess200($message, $res);
    } else {
        $message = "No catAdmin Found";
        success205($message);
    }
}

function getListCatAdmin()
{
    $catAdminModel = new Categorie_adminsModel();
    $catAdmins = (array)$catAdminModel->findAll();
    $data = getListCatAdminData($catAdmins);

    if (!empty($catAdmins)) {
        $message = "Liste des Categories Admin";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CAT_ADMIN);
        return dataTablesuccess200($message, $data);
    } else {
        $message = "Pas de categorie Admin";
        success205($message);
    }
}

# Update
function updateCatAdmin($catAdminData, $catAdminParams)
{
    $catAdminModel = new Categorie_adminsModel();
    $catAdmin = $catAdminModel;
    paramsVerify($catAdminParams, "category Admin");

    # On recupere les informations venues de POST
    if (empty(trim($catAdminData["designation"]))) {
        return error422("Entree Designation");
    } else {
        $designation = $catAdminData["designation"];
        $catAdminID = $catAdminParams['id'];
        $today = getSiku();
        $test = isExistDesignationUpdate($designation, $catAdminID);

        if ($test) {

            $catAdmin->setDesignation($designation);
            $catAdmin->setUpdated_at($today);
            $catAdminFound = $catAdminModel->find($catAdminID);
            # test de l'existence de la designation dans la BD

            if (empty($catAdminFound)) {
                $message = "No category Admin Found";
                return success205($message);
            } else {

                $catAdminModel->update($catAdminID, $catAdmin);
                # On ajoute l'Adresse  dans la BD
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CAT_ADMIN);
                $message = "Category Admin updated successfully";
                return success200($message);
            }
        }
    }
}

function  isExistDesignation($designation)
{

    #rechercher de l'ID de l'adresse
    $test = false;
    $dataDesignation = array(
        "designation" => $designation,
    );
    $catAdminModel = new Categorie_adminsModel();
    $dataCA = (object)$catAdminModel->findBy($dataDesignation);

    if (empty((array)$dataCA)) {
        $test = true;
        return $test;
    } else {
        error401(" Cette Designation existe deja");
    }
}

function isExistDesignationUpdate($designation, $id)
{

    $test = false;
    $dataDesignation = array(
        "designation" => $designation,
    );

    $catAdminModel = new Categorie_adminsModel();
    $dataCA = $catAdminModel->findBy($dataDesignation);
    if (empty((array)$dataCA)) {
        # CA n'existe pas
        $test = true;
        return $test;
    } else {
        if ($id == $dataCA[0]->id) {
            # Cette Designation existe et elle appartenait a ce meme CA
            $test = true;
            return $test;
        } else {
            error401(" Cette Designation existe deja");
        }
    }
}