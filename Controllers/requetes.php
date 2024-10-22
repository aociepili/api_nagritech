<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\RequetesModel;

Autoloader::register();

# Store
function storeRequetes($requetesData)
{
    $requetesModel = new RequetesModel();
    $requetes = $requetesModel;
    chargementRequetes($requetesData);
    $requetesData["date"] = getSiku();

    $question = $requetesData["question"];
    $reponse = $requetesData["reponse"];
    $destinateur = $requetesData["destinateur"];
    $date = $requetesData["date"];
    $lecture = false;
    $expediteur = $requetesData["expediteur"];

    $requetes->setQuestion($question);
    $requetes->setReponse($reponse);
    $requetes->setDestinateur($destinateur);
    $requetes->setDate($date);
    $requetes->setLecture($lecture);
    $requetes->setExpediteur($expediteur);
    $requetes->setCreated_at(getSiku());

    $requetesModel->create($requetes);
    $message = "requete  created successfully";
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_REQUETE);
    return success201($message);
}

#Delete
function deleteRequete($requetesParams)
{
    $requetesModel = new RequetesModel();
    paramsVerify($requetesParams, "Requetes");

    $requetesID = $requetesParams['id'];
    $requetesData = $requetesModel->find($requetesID);

    if ($requetesID == $requetesData->id) {
        $res = $requetesModel->delete($requetesID);
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_REQUETE);
        $message = "Requetes deleted successfully";
        return success200($message);
    } else {
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_REQUETE);
        $message = "Requetes not Found  ";
        return error405($message);
    }
}

#Get
function getRequeteById($requetesParams)
{
    $requetesModel = new RequetesModel();
    paramsVerify($requetesParams, "Requetes");
    $requetesFound = $requetesModel->find($requetesParams['id']);

    if (!empty($requetesFound)) {
        $message = "Requetes Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_REQUETE);
        return datasuccess200($message, $requetesFound);
    } else {
        $message = "No Requetes Found";
        return success205($message);
    }
}

function getListRequetes()
{
    $requetesModel = new RequetesModel();
    $requetes = (array)$requetesModel->findAll();

    if (!empty($requetes)) {
        $message = "Liste des Requetes";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_REQUETE);
        return dataTableSuccess200($message, $requetes);
    } else {
        $message = "Pas de Requetes ";
        return success205($message);
    }
}

# Update
function updaterequete($requetesData, $requetesParams)
{
    $requetesModel = new RequetesModel();
    $requetes = $requetesModel;
    paramsVerify($requetesParams, "Requetes");

    $requetesID = $requetesParams['id'];
    $question = $requetesData["question"];
    $reponse = $requetesData["reponse"];
    $destinateur = $requetesData["destinateur"];
    $date = $requetesData["date"];
    // $lecture = $requetesData["lecture"];
    $expediteur = $requetesData["expediteur"];

    $requetes->setQuestion($question);
    $requetes->setReponse($reponse);
    $requetes->setDestinateur($destinateur);
    $requetes->setDate($date);
    // $requetes->setLecture($lecture);
    $requetes->setExpediteur($expediteur);
    $requetes->setUpdated_at(getSiku());


    $requetesFound = $requetesModel->find($requetesID);
    if ($requetesID == $requetesFound->id) {
        $requetesModel->update($requetesID, $requetes);
        # On ajoute l'Adresse  dans la BD
        $message = "Requetes updated successfully";
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_REQUETE);
        return success200($message);
    } else {
        $message = "No Requetes  Found ";
        return success205($message);
    }
}
function lectureQuestion($requetesParams)
{
    $requetesModel = new RequetesModel();
    $requetes = $requetesModel;
    paramsVerify($requetesParams, "Requetes");
    $requetesID = $requetesParams['id'];
    $lecture = false;
    $requetes->setLecture($lecture);
    $requetes->setUpdated_at(getSiku());

    $requetesFound = $requetesModel->find($requetesID);
    if ($requetesID == $requetesFound->id) {
        $requetesModel->update($requetesID, $requetes);
        # On ajoute l'Adresse  dans la BD
        $message = "Lecture requete successfully";
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_REQUETE);
        return success200($message);
    } else {
        $message = "No Requetes  Found ";
        return error404($message);
    }
}