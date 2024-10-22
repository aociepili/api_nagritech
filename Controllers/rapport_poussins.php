<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Rapport_poussinsModel;

Autoloader::register();

# Store
function storeRapportPoussin($rapportPoussinsData)
{

    $rapportPoussinsModel = new Rapport_poussinsModel();
    $rapportPoussins = $rapportPoussinsModel;

    # On recupere les informations venues de POST
    chargementRapport($rapportPoussinsData);

    $quantite = $rapportPoussinsData['quantite'];
    $rapportPoussinsData['date'] = getSiku();
    $date = $rapportPoussinsData['date'];
    $statusID = STATUS_RAPPORT_ETABLI;
    $commentaire = $rapportPoussinsData['commentaire'];
    $agentID = $rapportPoussinsData['agents_idAgent'];
    $natureID = $rapportPoussinsData['natures_idNature'];
    $etat = $rapportPoussinsData['etat_rapportID'];
    natureVerify($natureID, DESIGN_POUSSIN);

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testStatutR = testStatutRapportbyId($statusID);


    if ($testNature and $testAgent and $testStatutR) {
        $rapportPoussins->setQuantite($quantite);
        $rapportPoussins->setDate($date);
        $rapportPoussins->setEtat_rapportID($etat);
        $rapportPoussins->setCommentaire($commentaire);
        $rapportPoussins->setAgents_idAgent($agentID);
        $rapportPoussins->setNatures_idNature($natureID);
        $rapportPoussins->setStatus_rapport_id($statusID);
        $rapportPoussins->setCreated_at(getSiku());


        # On ajoute la Designation dans la BD
        $rapportPoussinsModel->create($rapportPoussins);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POUSSIN);
        $message = "Rapport poussin  created successfully";
        return success201($message);
    }
}

#Delete
function deleteRapportPoussin($rapportPoussinsParams)
{
    $rapportPoussinsModel = new Rapport_poussinsModel();
    paramsVerify($rapportPoussinsParams, "Rapport poussin");

    $rapportPoussinsID = $rapportPoussinsParams['id'];
    $rapportPoussinsData = $rapportPoussinsModel->find($rapportPoussinsID);

    if ($rapportPoussinsID == $rapportPoussinsData->id) {
        $res = $rapportPoussinsModel->delete($rapportPoussinsID);
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_RAP_POUSSIN);
        $message = "Rapport poussin deleted successfully";
        return success200($message);
    } else {
        $message = "Rapport poussin not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_RAP_POUSSIN);
        return error405($message);
    }
}

#Get
function getRapportPoussinById($rapportPoussinsParams)
{
    $rapportPoussinsModel = new Rapport_poussinsModel();
    paramsVerify($rapportPoussinsParams, "Rapport Poussin");
    $rapportPoussinsFound = $rapportPoussinsModel->find($rapportPoussinsParams['id']);

    if (!empty($rapportPoussinsFound)) {
        $dataRP = getRapportPoussinDataById($rapportPoussinsFound->id);
        $message = "Rapport Poussin Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_RAP_POUSSIN);
        return datasuccess200($message, $dataRP);
    } else {
        $message = "No rapport Poussin Found";
        return error405($message);
    }
}

function getListRapportPoussin()
{
    $rapportPoussinsModel = new Rapport_poussinsModel();
    $rapportPoussins = (array)$rapportPoussinsModel->findAll();

    if (!empty($rapportPoussins)) {
        $dataListRP = getListRapportPoussinData($rapportPoussins);
        $message = "Situation Rapport Poussin";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POUSSIN);
        return dataTableSuccess200($message, $dataListRP);
    } else {
        $message = "Pas de situation dans le Rapport Poussin";
        return success205($message);
    }
}

# Update
function updateRapportPoussin($rapportPoussinsData, $rapportPoussinsParams)
{
    $rapportPoussinsModel = new Rapport_poussinsModel();
    $rapportPoussins = $rapportPoussinsModel;
    paramsVerify($rapportPoussinsParams, "Rapport Poussin");

    # On recupere les informations venues de POST
    $rapportPoussinsID = $rapportPoussinsParams['id'];

    $quantite = $rapportPoussinsData['quantite'];
    $date = $rapportPoussinsData['date'];
    $etat = $rapportPoussinsData['etat_rapportID'];
    $commentaire = $rapportPoussinsData['commentaire'];
    $agentID = $rapportPoussinsData['agents_idAgent'];
    $natureID = $rapportPoussinsData['natures_idNature'];
    $statusID = $rapportPoussinsData['status_rapport_id'];

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testStatutR = testStatutRapportbyId($statusID);
    $testEtatR = testEtatRapportbyId($etat);

    if ($testNature && $testAgent  && $testEtatR) {
        $rapportPoussins->setQuantite($quantite);
        $rapportPoussins->setDate($date);
        $rapportPoussins->setEtat_rapportID($etat);
        $rapportPoussins->setCommentaire($commentaire);
        $rapportPoussins->setAgents_idAgent($agentID);
        $rapportPoussins->setNatures_idNature($natureID);
        $rapportPoussins->setStatus_rapport_id($statusID);
        $rapportPoussins->setUpdated_at(getSiku());


        $rapportPoussinsFound = $rapportPoussinsModel->find($rapportPoussinsID);

        if ($rapportPoussinsID == $rapportPoussinsFound->id) {
            $testStatutRapport = testStatutRapport($rapportPoussinsFound->status_rapport_id);
            if ($testStatutRapport) {
                $rapportPoussinsModel->update($rapportPoussinsID, $rapportPoussins);
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_RAP_POUSSIN);
                # On ajoute l'Adresse  dans la BD
                $message = "Rapport Poussin updated successfully";
                return success200($message);
            }
        } else {
            $message = "No Rapport Poussin Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_RAP_POUSSIN);
            return success205($message);
        }
    }
}

function changeStatusRapportPoussin($rapportPoussinData, $rapportPoussinParams)
{
    $rapportPoussinModel = new Rapport_poussinsModel();
    $rapportPoussin = $rapportPoussinModel;


    $statusRID = $rapportPoussinData['status_rapport_id'];

    $rapportPoussinID = $rapportPoussinParams['id'];
    paramsVerify($rapportPoussinParams, "Rapport Poussin");



    $rapportPoussinFound = $rapportPoussinModel->find($rapportPoussinID);
    if ($rapportPoussinFound->id == $rapportPoussinID) {
        $rapportPoussinData['quantite'] = $rapportPoussinFound->quantite;
        $rapportPoussinData['natures_idNature'] = $rapportPoussinFound->natures_idNature;
        $rapportPoussinData['etat_rapportID'] = $rapportPoussinFound->etat_rapportID;
        $rapportPoussinData['agents_idAgent'] = $rapportPoussinFound->agents_idAgent;
        $rapportPoussinData['agents_id'] = $rapportPoussinFound->agents_idAgent;
        $rapportPoussinData['date'] = getSiku();

        $etatRID = $rapportPoussinData['etat_rapportID'];
        $natureID = $rapportPoussinData['natures_idNature'];
        natureVerify($natureID, DESIGN_POUSSIN);

        if ($rapportPoussinFound->status_rapport_id == STATUS_RAPPORT_VALIDE) {
            # RAPPORT DEJA TRAITE : ETABLI
            $message = "le Status du Rapport Poussin a deja subi un traitement ";
            return success200($message);
        } elseif ($rapportPoussinFound->status_rapport_id == STATUS_RAPPORT_ANNULE) {
            # RAPPORT DEJA TRAITE : ANNULE
            $message = "le Status du Rapport Poussin a deja subi un traitement ";
            return success200($message);
        } else {

            $simpleMod = array(STATUS_RAPPORT_ANNULE, STATUS_RAPPORT_REVISE, STATUS_RAPPORT_ETABLI);

            if (in_array($statusRID, $simpleMod)) {
                # RAPPORT SIMPLE A MODIFIER

                $rapportPoussin->setStatus_rapport_id($statusRID);
                $rapportPoussin->setUpdated_at(getSiku());
                $rapportPoussinModel->update($rapportPoussinID, $rapportPoussin);
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_POUSSIN);
                $message = "le Status du Rapport Poussin a ete Modifie ";
                return success200($message);
            } elseif ($statusRID == STATUS_RAPPORT_VALIDE) {
                # Verification de l'Etat du Produit GOOD PRODUCT
                if (in_array($etatRID, GOOD_PRODUCT)) {
                    #RAPPORT VALIDE SIMPLE POUR BONNE QUALITE DU PRODUIT
                    $rapportPoussin->setStatus_rapport_id($statusRID);
                    $rapportPoussin->setUpdated_at(getSiku());
                    $rapportPoussinModel->update($rapportPoussinID, $rapportPoussin);
                    $message = "le Status du Rapport Poussin a ete Modifie ";
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_POUSSIN);
                    return success200($message);
                } elseif ((in_array($etatRID, BAD_PRODUCT)) && (VerifyQteStockPoussin($natureID,  $rapportPoussinData['quantite']))) {

                    sortiePoussin($rapportPoussinData);
                    // debug400('Ime rudiya MBOVU', $rapportPoussinData);
                    $rapportPoussin->setStatus_rapport_id($statusRID);
                    $rapportPoussin->setUpdated_at(getSiku());
                    $rapportPoussinModel->update($rapportPoussinID, $rapportPoussin);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_POUSSIN);
                    $message = "le Status du Rapport Poussin a ete Modifie ";
                    return success200($message);
                } else {
                    $message = "Veuillez revoir l'etat que vous avez inserer ";
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_POUSSIN);
                    return success205($message);
                }
            } else {
                $message = "Veuillez revoir le status que vous avez inserer ";
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_POUSSIN);
                return success205($message);
            }
        }
    } else {
        $message = "No Rapport Poussin Found ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_POUSSIN);
        return success205($message);
    }
}


function getListRapportPoussinYear($rapportPoussinsParams)
{
    $rapportPoussinModel = new Rapport_poussinsModel();
    $rapportPoussins = (array)$rapportPoussinModel->findAll();

    paramsVerifyYear($rapportPoussinsParams, "Rapport Poussin");
    $year = $rapportPoussinsParams['year'];

    if (!empty($rapportPoussins)) {
        $listData = getListRapportPoussinDataYear($rapportPoussins, $year);
        $message = "Situation Rapport Poussin de l'annee " . $year;
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POUSSIN);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poussin";
        return success205($message);
    }
}

function getListRapportPoussinMonth()
{
    $rapportPoussinModel = new Rapport_poussinsModel();
    $rapportPoussins = (array)$rapportPoussinModel->findAll();

    if (!empty($rapportPoussins)) {
        $listData = getListRapportPoussinDataMonth($rapportPoussins);
        $message = "Situation Rapport Poussin de ce Mois ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POUSSIN);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poussin";
        return success205($message);
    }
}

function getListRapportPoussinWeek()
{
    $rapportPoussinModel = new Rapport_poussinsModel();
    $rapportPoussins = (array)$rapportPoussinModel->findAll();
    if (!empty($rapportPoussins)) {
        $listData = getListRapportPoussinDataWeek($rapportPoussins);
        $message = "Situation Rapport Poussin de la semaine  ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POUSSIN);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poussin";
        return success205($message);
    }
}

function getListRapportPoussinDay()
{
    $rapportPoussinModel = new Rapport_poussinsModel();
    $rapportPoussins = (array)$rapportPoussinModel->findAll();

    if (!empty($rapportPoussins)) {
        $listData = getListRapportPoussinDataDay($rapportPoussins);
        $message = "Situation Rapport Poussin de jour " . getSiku();
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POUSSIN);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poussin";
        return success205($message);
    }
}