<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Motif_sortiesModel;

Autoloader::register();
# categorie_admins
# Store
function storeMotif($motifData)
{

    $motifModel = new Motif_sortiesModel();
    $motif = $motifModel;

    # On recupere les informations venues de POST
    if (empty(trim($motifData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {
        $designation = $motifData["designation"];
        $today = getSiku();
        $test = isExistMotifByDesignation($designation);

        if ($test) {
            $motif->setDesignation($designation);
            $motif->setCreated_at($today);
            # On ajoute la Designation dans la BD
            $motifModel->create($motif);
            $message = "Motif  created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_MOTIF);
            return success201($message);
        }
    }
}

#Delete
function deleteMotif($motifParams)
{
    $motifModel = new Motif_sortiesModel();
    paramsVerify($motifParams, "Motif");

    $motifID = $motifParams['id'];
    $motifData = $motifModel->find($motifID);

    if ($motifID == $motifData->id) {
        try {
            //code...
            $motifModel->delete($motifID);
            $message = "Motif deleted successfully";
            createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_MOTIF);
            return success200($message);
        } catch (Exception $th) {
            $message = "Impossible de supprimer ou de mettre à jour cette information";
            createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_MOTIF);
            return error422($message);
        }
    } else {
        $message = "Motif not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_MOTIF);
        return error405($message);
    }
}

#Get
function getMotifById($motifParams)
{
    $motifModel = new Motif_sortiesModel();
    paramsVerify($motifParams, "Motif");
    $motifFound = $motifModel->find($motifParams['id']);

    if (!empty($motifFound)) {
        $message = "Motif Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_MOTIF);
        return datasuccess200($message, $motifFound);
    } else {
        $message = "No motif Found";
        return success205($message);
    }
}

function getListMotif()
{
    $motifModel = new Motif_sortiesModel();
    $motif = (array)$motifModel->findAll();


    if (!empty($motif)) {
        $dataM = getListMotifData($motif);
        $message = "Liste des Motif";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_MOTIF);
        return dataTableSuccess200($message, $dataM);
    } else {
        $message = "Pas de Motif";
        return success205($message);
    }
}

# Update
function updateMotif($motifData, $motifParams)
{

    $motifModel = new Motif_sortiesModel();
    $motif = $motifModel;

    paramsVerify($motifParams, "Motif");


    # On recupere les informations venues de POST
    if (empty(trim($motifData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $motifData["designation"];
        $today = getSiku();
        $motifID = $motifParams['id'];

        # test de l'existence de la designation dans la BD
        $test = isExistMotifByDesignationUpdate($designation, $motifID);


        $motif->setDesignation($designation);
        $motif->setUpdated_at($today);
        $motifFound = $motifModel->find($motifID);


        if ($test) {
            if ($motifID == $motifFound->id) {
                $motifModel->update($motifID, $motif);
                # On ajoute l'Adresse  dans la BD
                $message = "Motif updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_MOTIF);
                return success200($message);
            } else {
                $message = "No Motif Admin Found ";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_MOTIF);
                return success205($message);
            }
        }
    }
}

function isExistMotifByDesignation($designation)
{
    #rechercher de l'ID de l'adresse
    $test = false;
    $Datamotif = array(
        "designation" => $designation,
    );
    $motifModel = new Motif_sortiesModel();
    $dataM = (object)$motifModel->findBy($Datamotif);

    if (empty((array)$dataM)) {
        # Agent n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}


function isExistMotifByDesignationUpdate($designation, $id)
{

    $test = false;
    $Datamotif = array(
        "designation" => $designation,
    );
    $motifModel = new Motif_sortiesModel();
    $dataM = $motifModel->findBy($Datamotif);

    if (empty((array)$dataM)) {
        # Agent n'existe pas
        $test = true;
        return $test;
    } else {

        if ($id == $dataM[0]->id) {
            # Cet adresse Mail existe et elle appartenait a ce meme agent
            $test = true;
            return $test;
        } else {
            success203("Cette Designation existe deja");
        }
    }
}