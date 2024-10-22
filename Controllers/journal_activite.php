<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Journal_activitesModel;

Autoloader::register();

# Store
// function storeActivity($journalActiviteData)
// {
//     $journalActivitesModel = new Journal_activitesModel();
//     $journalActivite = $journalActivitesModel;

//     $userId = $journalActiviteData['user_id'];
//     $roleId = $journalActiviteData['role_id'];
//     $typeOpId = $journalActiviteData['type_op_id'];
//     $statusOpId = $journalActiviteData['status_op_id'];
//     $tableId = $journalActiviteData['table_id'];

//     # On recupere les informations venues de POST
//     $journalActivite->setUser_id($userId);
//     $journalActivite->setRole_id($roleId);
//     $journalActivite->setType_op_id($typeOpId);
//     $journalActivite->setStatus_op_id($statusOpId);
//     $journalActivite->setTable_id($tableId);
//     $journalActivite->setCreated_at(getSiku());

//     # On ajoute la Designation dans la BD
//     $journalActivitesModel->create($journalActivite);
//     $message = "Activite  User  created successfully";
//     return success201($message);
// }

#Delete
function deleteActivity($journalActiviteParams)
{
    $journalActivitesModel = new Journal_activitesModel();
    paramsVerify($journalActiviteParams, "Journal Activite");

    $journalActiviteID = $journalActiviteParams['id'];
    $journalActiviteData = $journalActivitesModel->find($journalActiviteID);

    if ($journalActiviteID == $journalActiviteData->id) {
        $journalActivitesModel->delete($journalActiviteID);
        $message = "Activity deleted successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ACTIVITY);
        return success200($message);
    } else {
        $message = "Activity not delete  ";
        return success205($message);
    }
}

#Get
function getActivityById($journalActiviteParams)
{
    $journalActivitesModel = new Journal_activitesModel();
    paramsVerify($journalActiviteParams, "Journal Activite");
    $journalActiviteFound = $journalActivitesModel->find($journalActiviteParams['id']);

    if (!empty($journalActiviteFound)) {
        $message = "Activity Fetched successfully";
        $dataJournal = getJournalActivityDataById($journalActiviteFound->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ACTIVITY);
        return datasuccess200($message, $dataJournal);
    } else {
        $message = "Activity not  Found";
        return success205($message);
    }
}

function getActivityAll()
{
    $journalActivitesModel = new Journal_activitesModel();
    $ListjournalActivite = (array)$journalActivitesModel->findAll();

    if (!empty($ListjournalActivite)) {
        $message = "Liste des Activites utilisateurs";
        $dataListJournal = getListJournalActivityDataById($ListjournalActivite);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ACTIVITY);
        return dataTableSuccess200($message, $dataListJournal);
    } else {
        $message = "Pas d'activite ";
        return success205($message);
    }
}