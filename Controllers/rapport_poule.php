<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Rapport_poulesModel;

Autoloader::register();

# Store
function storeRapportPoules($rapportPoulesData)
{
    $rapportPoulesModel = new Rapport_poulesModel();
    $rapportPoules = $rapportPoulesModel;

    # On recupere les informations venues de POST
    chargementRapport($rapportPoulesData);

    $quantite = $rapportPoulesData['quantite'];
    $rapportPoulesData['date'] = getSiku();
    $date = $rapportPoulesData['date'];
    $etat = $rapportPoulesData['etat_rapportID'];
    $commentaire = $rapportPoulesData['commentaire'];
    $agentID = $rapportPoulesData['agents_idAgent'];
    $natureID = $rapportPoulesData['natures_idNature'];
    $statusID = STATUS_RAPPORT_ETABLI;
    natureVerify($natureID, DESIGN_POULE);

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testStatutR = testStatutRapportbyId($statusID);
    $testEtatR = testEtatRapportbyId($etat);

    if ($testNature && $testAgent && $testStatutR && $testEtatR) {
        $rapportPoules->setQuantite($quantite);
        $rapportPoules->setDate($date);
        $rapportPoules->setEtat_rapportID($etat);
        $rapportPoules->setCommentaire($commentaire);
        $rapportPoules->setAgents_idAgent($agentID);
        $rapportPoules->setNatures_idNature($natureID);
        $rapportPoules->setStatus_rapport_id($statusID);
        $rapportPoules->setCreated_at(getSiku());

        # On ajoute la Designation dans la BD
        $rapportPoulesModel->create($rapportPoules);
        $message = "Rapport Poule  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_RAP_POULE);
        return success201($message);
    }
}

#Delete
function deleteRapportPoules($rapportPoulesParams)
{
    $rapportPoulesModel = new Rapport_poulesModel();
    paramsVerify($rapportPoulesParams, "Rapport Poule");

    $rapportPoulesID = $rapportPoulesParams['id'];
    $rapportPoulesData = $rapportPoulesModel->find($rapportPoulesID);

    if ($rapportPoulesID == $rapportPoulesData->id) {
        $res = $rapportPoulesModel->delete($rapportPoulesID);
        $message = "Rapport Poule deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_RAP_POULE);
        return success200($message);
    } else {
        $message = "Rapport Poule not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_RAP_POULE);
        return success205($message);
    }
}

#Get
function getRapportPouleById($rapportPoulesParams)
{
    $rapportPoulesModel = new Rapport_poulesModel();
    paramsVerify($rapportPoulesParams, "Rapport Poule");

    $rapportPoulesFound = $rapportPoulesModel->find($rapportPoulesParams['id']);

    if (!empty($rapportPoulesFound)) {
        $dataRP = getRapportPouleDataById($rapportPoulesFound->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_RAP_POULE);
        $message = "Rapport Poule Fetched successfully";
        return datasuccess200($message, $dataRP);
    } else {
        $message = "No rapport Poule Found";
        return success205($message);
    }
}

function getListRapportPoules()
{
    $rapportPoulesModel = new Rapport_poulesModel();
    $rapportPoules = (array)$rapportPoulesModel->findAll();

    if (!empty($rapportPoules)) {
        $dataListRA = getListRapportPouleData($rapportPoules);
        $message = "Situation Rapport Poule";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULE);
        return dataTableSuccess200($message, $dataListRA);
    } else {
        $message = "Pas de situation dans le Rapport Poule";
        return success205($message);
    }
}

# Update
function updateRapportPoules($rapportPoulesData, $rapportPoulesParams)
{
    $rapportPoulesModel = new Rapport_poulesModel();
    $rapportPoules = $rapportPoulesModel;
    paramsVerify($rapportPoulesParams, "Rapport Poule");

    # On recupere les informations venues de POST
    $rapportPoulesID = $rapportPoulesParams['id'];

    $quantite = $rapportPoulesData['quantite'];
    $date = $rapportPoulesData['date'];
    $etat = $rapportPoulesData['etat_rapportID'];
    $commentaire = $rapportPoulesData['commentaire'];
    $agentID = $rapportPoulesData['agents_idAgent'];
    $natureID = $rapportPoulesData['natures_idNature'];


    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testEtatR = testEtatRapportbyId($etat);

    if ($testNature && $testAgent && $testEtatR) {
        $rapportPoules->setQuantite($quantite);
        $rapportPoules->setDate($date);
        $rapportPoules->setEtat_rapportID($etat);
        $rapportPoules->setCommentaire($commentaire);
        $rapportPoules->setAgents_idAgent($agentID);
        $rapportPoules->setNatures_idNature($natureID);
        $rapportPoules->setUpdated_at(getSiku());


        $rapportPoulesFound = $rapportPoulesModel->find($rapportPoulesID);

        if ($rapportPoulesID == $rapportPoulesFound->id) {
            $testStatutRapport = testStatutRapport($rapportPoulesFound->status_rapport_id);
            if ($testStatutRapport) {
                $rapportPoulesModel->update($rapportPoulesID, $rapportPoules);
                # On ajoute l'Adresse  dans la BD
                $message = "Rapport Poule updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_RAP_POULE);
                return success200($message);
            }
        } else {
            $message = "No Rapport Poule Found ";
            return success205($message);
        }
    }
}

function changeStatusRapportPoule($rapportPoulesData, $rapportPoulesParams)
{
    $rapportPoulesModel = new Rapport_poulesModel();
    $rapportPoules = $rapportPoulesModel;

    $statusRID = $rapportPoulesData['status_rapport_id'];
    $rapportPoulesID = $rapportPoulesParams['id'];
    paramsVerify($rapportPoulesParams, "Rapport Poule");

    $rapportPoulesFound = $rapportPoulesModel->find($rapportPoulesID);
    if ($rapportPoulesFound->id == $rapportPoulesID) {
        $rapportPoulesData['quantite'] = $rapportPoulesFound->quantite;
        $rapportPoulesData['natures_idNature'] = $rapportPoulesFound->natures_idNature;
        $rapportPoulesData['etat_rapportID'] = $rapportPoulesFound->etat_rapportID;
        $rapportPoulesData['agents_idAgent'] = $rapportPoulesFound->agents_idAgent;
        $rapportPoulesData['agents_id'] = $rapportPoulesFound->agents_idAgent;
        $rapportPoulesData['date'] = getSiku();

        $etatRID = $rapportPoulesData['etat_rapportID'];
        $natureID = $rapportPoulesData['natures_idNature'];
        natureVerify($natureID, DESIGN_POULE);
        if ($rapportPoulesFound->status_rapport_id == STATUS_RAPPORT_VALIDE) {
            # RAPPORT DEJA TRAITE : VALIDE
            $message = "le Status du Rapport Poule a deja subi un traitement ";
            return success200($message);
        } elseif ($rapportPoulesFound->status_rapport_id == STATUS_RAPPORT_ANNULE) {
            # RAPPORT DEJA TRAITE : ANNULE
            $message = "le Status du Rapport Poule a deja subi un traitement ";

            return success200($message);
        } else {

            $simpleMod = array(STATUS_RAPPORT_ANNULE, STATUS_RAPPORT_REVISE, STATUS_RAPPORT_ETABLI);

            if (in_array($statusRID, $simpleMod)) {
                # RAPPORT SIMPLE A MODIFIER

                $rapportPoules->setStatus_rapport_id($statusRID);
                $rapportPoules->setUpdated_at(getSiku());
                $rapportPoulesModel->update($rapportPoulesID, $rapportPoules);
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_POULE);
                $message = "le Status du Rapport Poule a ete Modifie ";
                return success200($message);
            } elseif ($statusRID == STATUS_RAPPORT_VALIDE) {
                # Verification de l'Etat du Produit GOOD PRODUCT
                if (in_array($etatRID, GOOD_PRODUCT)) {
                    #RAPPORT VALIDE SIMPLE POUR BONNE QUALITE DU PRODUIT
                    $rapportPoules->setStatus_rapport_id($statusRID);
                    $rapportPoules->setUpdated_at(getSiku());
                    $rapportPoulesModel->update($rapportPoulesID, $rapportPoules);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_POULE);
                    $message = "le Status du Rapport Poule a ete Modifie ";
                    return success200($message);
                } elseif ((in_array($etatRID, BAD_PRODUCT)) && (VerifyQteStockPoule($natureID,  $rapportPoulesData['quantite']))) {

                    sortiePoule($rapportPoulesData);

                    $rapportPoules->setStatus_rapport_id($statusRID);
                    $rapportPoules->setUpdated_at(getSiku());
                    $rapportPoulesModel->update($rapportPoulesID, $rapportPoules);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_POULE);
                    $message = "le Status du Rapport Poule a ete Modifie ";
                    return success200($message);
                } else {
                    $message = "Veuillez revoir l'etat que vous avez inserer ";
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_POULE);
                    return success205($message);
                }
            } else {
                $message = "Veuillez revoir le status que vous avez inserer ";
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_POULE);
                return success205($message);
            }
        }
    } else {
        $message = "No Rapport Poule Found ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_POULE);
        return success205($message);
    }
}

function getListRapportPoulesYear($rapportPoulesParams)
{
    $rapportPoulesModel = new Rapport_poulesModel();
    $rapportPoules = (array)$rapportPoulesModel->findAll();

    paramsVerifyYear($rapportPoulesParams, "Rapport Poule");
    $year = $rapportPoulesParams['year'];

    if (!empty($rapportPoules)) {
        $listData = getListRapportPouleDataYear($rapportPoules, $year);
        $message = "Situation Rapport Poule de l'annee " . $year;
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULE);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poule";
        return success205($message);
    }
}

function getListRapportPoulesMonth()
{
    $rapportPoulesModel = new Rapport_poulesModel();
    $rapportPoules = (array)$rapportPoulesModel->findAll();

    if (!empty($rapportPoules)) {
        $listData = getListRapportPouleDataMonth($rapportPoules);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULE);
        $message = "Situation Rapport Poule de ce Mois ";
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poule";
        return success205($message);
    }
}

function getListRapportPoulesWeek()
{
    $rapportPoulesModel = new Rapport_poulesModel();
    $rapportPoules = (array)$rapportPoulesModel->findAll();
    if (!empty($rapportPoules)) {
        $listData = getListRapportPouleDataWeek($rapportPoules);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULE);
        $message = "Situation Rapport Poule de la semaine  ";
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poule";
        return success205($message);
    }
}

function getListRapportPoulesDay()
{
    $rapportPoulesModel = new Rapport_poulesModel();
    $rapportPoules = (array)$rapportPoulesModel->findAll();

    if (!empty($rapportPoules)) {
        $listData = getListRapportPouleDataDay($rapportPoules);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULE);
        $message = "Situation Rapport Poule de jour " . getSiku();
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poule";
        return success205($message);
    }
}