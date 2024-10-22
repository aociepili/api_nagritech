<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('..\Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Etat_rapportsModel;

Autoloader::register();

# Store
function storeEtatRapport($etatRapportData)
{

    $etatRapportsModel = new Etat_rapportsModel();
    $etatRapport = $etatRapportsModel;

    # On recupere les informations venues de POST
    if (empty(trim($etatRapportData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $etatRapportData["designation"];
        $test = isExistEtatRapportByDesignation($designation);

        if ($test) {
            $etatRapport->setDesignation($designation);
            $etatRapport->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $etatRapportsModel->create($etatRapport);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ETAT_RAPP);
            $message = "Etat Rapport created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteEtatRapport($etatRapportParams)
{
    $etatRapportsModel = new Etat_rapportsModel();
    paramsVerify($etatRapportParams, "Etat Rapport");

    $etatRapportID = $etatRapportParams['id'];
    $etatRapportData = $etatRapportsModel->find($etatRapportID);

    if ($etatRapportID == $etatRapportData->id) {
        $etatRapportsModel->delete($etatRapportID);
        $message = "Etat Rapport deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ETAT_RAPP);
        return success200($message);
    } else {
        $message = "Etat Rapport not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_ETAT_RAPP);
        return error405($message);
    }
}

#Get
function getEtatRapportById($etatRapportParams)
{
    $etatRapportsModel = new Etat_rapportsModel();
    paramsVerify($etatRapportParams, "Etat Rapport");
    $etatRapportFound = $etatRapportsModel->find($etatRapportParams['id']);

    if (!empty($etatRapportFound)) {
        $message = "Etat Rapport Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ETAT_RAPP);
        return datasuccess200($message, $etatRapportFound);
    } else {
        $message = "No Etat Rapporte Rapport Found";
        return success205($message);
    }
}

function getListEtatRapportAll()
{
    $etatRapportsModel = new Etat_rapportsModel();
    $etatRapport = (array)$etatRapportsModel->findAll();

    if (!empty($etatRapport)) {
        $message = "Liste des Etat Rapport";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ETAT_RAPP);
        return dataTableSuccess200($message, $etatRapport);
    } else {
        $message = "Pas de Etat Rapport";
        return success205($message);
    }
}
function getListEtatRapport()
{
    $etatRapportsModel = new Etat_rapportsModel();
    $data = array(
        "status" => true,
    );
    $etatRapport = (array)$etatRapportsModel->findBy($data);
    if (!empty($etatRapport)) {
        $message = "Liste des Etat Rapport";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ETAT_RAPP);
        return dataTableSuccess200($message, $etatRapport);
    } else {
        $message = "Pas de Etat Rapport";
        return success205($message);
    }
}

function archiveEtatRapport($etatRapportParams)
{
    $etatRapportsModel = new Etat_rapportsModel();
    $etatRapport = $etatRapportsModel;
    paramsVerify($etatRapportParams, "Etat Rapport");

    $etatRapportID = $etatRapportParams['id'];
    $etatRapportData = $etatRapportsModel->find($etatRapportID);

    if ($etatRapportID == $etatRapportData->id) {
        $etatRapport->setStatus(false);
        $etatRapport->setUpdated_at(getSiku());
        $etatRapportsModel->update($etatRapportID, $etatRapport);
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_ETAT_RAPP);
        $message = "Etat Rapport Archive successfully";
        return success200($message);
    } else {
        $message = "Etat Rapport not Archive  ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_ETAT_RAPP);
        return success205($message);
    }
}
function activeEtatRapport($etatRapportParams)
{
    $etatRapportsModel = new Etat_rapportsModel();
    $etatRapport = $etatRapportsModel;
    paramsVerify($etatRapportParams, "Etat Rapport");

    $etatRapportID = $etatRapportParams['id'];
    $etatRapportData = $etatRapportsModel->find($etatRapportID);

    if ($etatRapportID == $etatRapportData->id) {
        $etatRapport->setStatus(false);
        $etatRapport->setUpdated_at(getSiku());
        $etatRapportsModel->update($etatRapportID, $etatRapport);
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_ETAT_RAPP);
        $message = "Etat Rapport Archive successfully";
        return success200($message);
    } else {
        $message = "Etat Rapport not Archive  ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_ETAT_RAPP);
        return success205($message);
    }
}

# Update
function updateEtatRapport($etatRapportData, $etatRapportParams)
{
    $etatRapportsModel = new Etat_rapportsModel();
    $etatRapport = $etatRapportsModel;
    paramsVerify($etatRapportParams, "Etat Rapport");

    # On recupere les informations venues de POST
    if (empty(trim($etatRapportData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $etatRapportData["designation"];
        $etatRapportID = $etatRapportParams['id'];

        $etatRapportFound = $etatRapportsModel->find($etatRapportID);

        $test = isExistEtatRapportByDesignationUpdate($designation, $etatRapportID);
        $etatRapport->setDesignation($designation);
        $etatRapport->setUpdated_at(getSiku());
        if ($test) {

            if ($etatRapportID == $etatRapportFound->id) {
                $etatRapportsModel->update($etatRapportID, $etatRapport);
                # On ajoute l'Adresse  dans la BD
                $message = "Etat Rapport updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ETAT_RAPP);
                return success200($message);
            } else {
                $message = "No Etat Rapport Rapport Found ";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_ETAT_RAPP);
                return success205($message);
            }
        }
    }
}


function isExistEtatRapportByDesignation($designation)
{
    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $etatRapportsModel = new Etat_rapportsModel();
    $etatData = (object)$etatRapportsModel->findBy($data);

    if (empty((array)$etatData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}
function isExistEtatRapportByDesignationUpdate($designation, $id)
{

    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $etatRapportsModel = new Etat_rapportsModel();
    $etatData = $etatRapportsModel->findBy($data);
    if (empty((array)$etatData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        if ($id == $etatData[0]->id) {
            $test = true;
            return $test;
        } else {
            success203(" Cette Designation existe deja");
        }
    }
}