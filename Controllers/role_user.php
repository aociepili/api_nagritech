<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Role_usersModel;

Autoloader::register();

# Store
function storeRoleUser($roleUserData)
{
    $roleUserModel = new Role_usersModel();
    $roleUser = $roleUserModel;

    # On recupere les informations venues de POST
    if (empty(trim($roleUserData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $roleUserData["designation"];
        $test = isExistRoleUserByDesignation($designation);

        if ($test) {
            $roleUser->setDesignation($designation);
            $roleUser->setStatus(true);
            $roleUser->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $roleUserModel->create($roleUser);
            $message = "Role User  created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ROLE_USER);
            return success201($message);
        }
    }
}

#Delete
function deleteRoleUser($roleUserParams)
{
    $roleUserModel = new Role_usersModel();
    paramsVerify($roleUserParams, "Role user");

    $roleUserID = $roleUserParams['id'];
    $roleUserData = $roleUserModel->find($roleUserID);

    if ($roleUserID == $roleUserData->id) {
        $roleUserModel->delete($roleUserID);
        $message = "Role User deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ROLE_USER);
        return success200($message);
    } else {
        $message = "Role User not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_ROLE_USER);
        return success205($message);
    }
}

#Get
function getRoleUserById($roleUserParams)
{
    $roleUserModel = new Role_usersModel();
    paramsVerify($roleUserParams, "Role User");
    $roleUserFound = $roleUserModel->find($roleUserParams['id']);

    if (!empty($roleUserFound)) {
        $message = "Role User Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ROLE_USER);
        return datasuccess200($message, $roleUserFound);
    } else {
        $message = "No Role User Found";
        return success205($message);
    }
}

function getListRoleUserAll()
{
    $roleUserModel = new Role_usersModel();
    $roleUser = (array)$roleUserModel->findAll();

    if (!empty($roleUser)) {
        $message = "Liste des Role User";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ROLE_USER);
        return dataTableSuccess200($message, $roleUser);
    } else {
        $message = "Pas de Role User";
        return success205($message);
    }
}
function getListRoleUser()
{
    $roleUserModel = new Role_usersModel();

    $data = array(
        "status" => true,
    );
    $roleUser = (array)$roleUserModel->findBy($data);

    if (!empty($roleUser)) {
        $message = "Liste des Role User";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ROLE_USER);
        return dataTableSuccess200($message, $roleUser);
    } else {
        $message = "Pas de Role User";
        return success205($message);
    }
}


#Archive
function archiveRoleUser($roleUserParams)
{
    $roleUserModel = new Role_usersModel();
    $roleUser = $roleUserModel;
    paramsVerify($roleUserParams, "Role User");

    $roleUserID = $roleUserParams['id'];
    $roleUserData = $roleUserModel->find($roleUserID);

    if ($roleUserID == $roleUserData->id) {
        $roleUser->setStatus(false);
        $roleUser->setUpdated_at(getSiku());
        $roleUserModel->update($roleUserID, $roleUser);
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_ROLE_USER);
        $message = "Role User Archive successfully";
        return success200($message);
    } else {
        $message = "Role User not Archive  ";
        return success205($message);
    }
}

# Update
function updateRoleUser($roleUserData, $roleUserParams)
{
    $roleUserModel = new Role_usersModel();
    $roleUser = $roleUserModel;
    paramsVerify($roleUserParams, "Role User");

    # On recupere les informations venues de POST
    if (empty(trim($roleUserData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $roleUserData["designation"];
        $status = $roleUserData["status"];
        $roleUserID = $roleUserParams['id'];

        $roleUserFound = $roleUserModel->find($roleUserID);

        $test = isExistRoleUserByDesignationUpdate($designation, $roleUserID);
        $roleUser->setDesignation($designation);
        $roleUser->setStatus($status);
        $roleUser->setUpdated_at(getSiku());
        if ($test) {

            if ($roleUserID == $roleUserFound->id) {
                $roleUserModel->update($roleUserID, $roleUser);
                # On ajoute l'Adresse  dans la BD
                $message = "Role User updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ROLE_USER);
                return success200($message);
            } else {
                $message = "No Role User Found ";
                return success205($message);
            }
        }
    }
}


function isExistRoleUserByDesignation($designation)
{
    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $roleUserModel = new Role_usersModel();
    $statusData = (object)$roleUserModel->findBy($data);

    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}
function isExistRoleUserByDesignationUpdate($designation, $id)
{

    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $roleUserModel = new Role_usersModel();
    $statusData = $roleUserModel->findBy($data);

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