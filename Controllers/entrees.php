<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\EntreesModel;

Autoloader::register();

# Store
// function storeEntrees($entreesData)
// {
//     $entreesModel = new EntreesModel();
//     $entrees = $entreesModel;

//     # On recupere les informations venues de POST
//     chargementEntree($entreesData);

//     $date = $entreesData["date"];
//     $natureID = $entreesData['natures_idNature'];
//     $motifID = $entreesData['motifSorties_idMotif'];
//     $today = getSiku();

//     $testNature = testNaturebyId($natureID);
//     $testMotif = testMotifbyId($motifID);

//     if ($testNature and $testMotif) {
//         $entrees->setDate($date);
//         $entrees->setNatures_idNature($natureID);
//         $entrees->setMotifSorties_idMotif($motifID);
//         $entrees->setCreated_at($today);

//         # On ajoute la Designation dans la BD
//         $entreesModel->create($entrees);
//         createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ENTREE);
//         $message = "Entrees  created successfully";
//         return success201($message);
//     }
// }

#Delete
function deleteEntrees($entreesParams)
{
    $entreesModel = new EntreesModel();
    paramsVerify($entreesParams, "Entrees");

    $entreesID = $entreesParams['id'];
    $entreesData = $entreesModel->find($entreesID);

    if ($entreesID == $entreesData->id) {
        $res = $entreesModel->delete($entreesID);
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ENTREE);
        $message = "Entrees deleted successfully";
        return success200($message);
    } else {
        $message = "Entrees not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_ENTREE);
        return error405($message);
    }
}

#Get
function getEntreesById($entreesParams)
{
    $entreesModel = new EntreesModel();
    paramsVerify($entreesParams, "Entrees");
    $entreesFound = $entreesModel->find($entreesParams['id']);

    if (!empty($entreesFound)) {
        $dataEntree = getEntreeDataById($entreesFound->id);
        $message = "Entrees Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ENTREE);
        return datasuccess200($message, $dataEntree);
    } else {
        $message = "Entree not found";
        return success205($message);
    }
}

function getListEntrees()
{
    $entreesModel = new EntreesModel();
    $entrees = $entreesModel->findAll();

    if (!empty($entrees)) {
        $listData = getListEntreesDataById($entrees);
        $message = "Liste des Entrees";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENTREE);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de Entrees";
        return success205($message);
    }
}
function getListEntreesYear($entreesParams)
{
    $entreesModel = new EntreesModel();
    $entrees = $entreesModel->findAll();
    paramsVerifyYear($entreesParams, "Entrees");
    $year = $entreesParams['year'];


    if (!empty($entrees)) {
        $listData = getListEntreesDataByIdYear($entrees, $year);
        $message = "Liste des Entrees de l'annee " . $year;
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENTREE);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de Entrees dans la base ";
        return success205($message);
    }
}
function getListEntreesMonth()
{
    $entreesModel = new EntreesModel();
    $entrees = $entreesModel->findAll();

    if (!empty($entrees)) {
        $listData = getListEntreesDataByIdMonth($entrees);
        $message = "Liste des Entrees de ce Mois ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENTREE);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de Entrees";
        return success205($message);
    }
}
function getListEntreesWeek()
{
    $entreesModel = new EntreesModel();
    $entrees = $entreesModel->findAll();

    if (!empty($entrees)) {
        $listData = getListEntreesDataByIdWeek($entrees);
        $message = "Liste des Entrees de la semaine  ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENTREE);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de Entrees";
        return success205($message);
    }
}
function getListEntreesDay()
{
    $entreesModel = new EntreesModel();
    $entrees = $entreesModel->findAll();

    if (!empty($entrees)) {
        $listData = getListEntreesDataByIdDay($entrees);
        $message = "Liste des Entrees de jour " . getSiku();
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENTREE);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de Entrees";
        return success205($message);
    }
}

# Update
// function updateEntrees($entreesData, $entreesParams)
// {
//     $entreesModel = new EntreesModel();
//     $entrees = $entreesModel;
//     paramsVerify($entreesParams, "Entrees");

//     # On recupere les informations venues de POST
//     $entreesID = $entreesParams['id'];

//     $date = $entreesData["date"];
//     $natureID = $entreesData['natures_idNature'];
//     $motifID = $entreesData['motifSorties_idMotif'];
//     $today = getSiku();

//     $testNature = testNaturebyId($natureID);
//     $testMotif = testMotifbyId($motifID);
//     // debug400("test Entree", $motifID);

//     if ($testNature and $testMotif) {
//         $entrees->setDate($date);
//         $entrees->setNatures_idNature($natureID);
//         $entrees->setMotifSorties_idMotif($motifID);
//         $entrees->setUpdated_at($today);

//         $entreesFound = $entreesModel->find($entreesID);

//         if ($entreesID == $entreesFound->id) {
//             $entreesModel->update($entreesID, $entrees);
//             # On ajoute l'Adresse  dans la BD
//             $message = "Entrees updated successfully";
//             createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ENTREE);
//             return success200($message);
//         } else {
//             $message = "No Entree Found ";
//             return error404($message);
//         }
//     }
// }