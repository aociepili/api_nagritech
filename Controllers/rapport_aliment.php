<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Rapport_alimentsModel;

Autoloader::register();

# Store
function storeRapportAliments($rapportAlimentsData)
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = $rapportAlimentsModel;

    # On recupere les informations venues de POST
    chargementRapport($rapportAlimentsData);

    $quantite = $rapportAlimentsData['quantite'];
    $rapportAlimentsData['date'] = getSiku();
    $date = $rapportAlimentsData['date'];
    $etat = $rapportAlimentsData['etat_rapportID'];
    $commentaire = $rapportAlimentsData['commentaire'];
    $agentID = $rapportAlimentsData['agents_idAgent'];
    $natureID = $rapportAlimentsData['natures_idNature'];
    $statusID = STATUS_RAPPORT_ETABLI;
    natureVerify($natureID, DESIGN_ALIMENT);

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testStatutR = testStatutRapportbyId($statusID);
    $testEtatR = testEtatRapportbyId($etat);

    if ($testNature && $testAgent && $testStatutR && $testEtatR) {
        $rapportAliments->setQuantite($quantite);
        $rapportAliments->setDate($date);
        $rapportAliments->setEtat_rapportID($etat);
        $rapportAliments->setCommentaire($commentaire);
        $rapportAliments->setAgents_idAgent($agentID);
        $rapportAliments->setNatures_idNature($natureID);
        $rapportAliments->setStatus_rapport_id($statusID);
        $rapportAliments->setCreated_at(getSiku());

        # On ajoute la Designation dans la BD
        $rapportAlimentsModel->create($rapportAliments);
        $message = "Rapport Aliment  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_RAP_ALIMENT);
        return success201($message);
    }
}

#Delete
function deleteRapportAliments($rapportAlimentsParams)
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    paramsVerify($rapportAlimentsParams, "Rapport Aliment");

    $rapportAlimentsID = $rapportAlimentsParams['id'];
    $rapportAlimentsData = $rapportAlimentsModel->find($rapportAlimentsID);

    if ($rapportAlimentsID == $rapportAlimentsData->id) {
        $res = $rapportAlimentsModel->delete($rapportAlimentsID);
        $message = "Rapport Aliment deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_RAP_ALIMENT);
        return success200($message);
    } else {
        $message = "Rapport Aliment not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_RAP_ALIMENT);
        return success205($message);
    }
}

#Get
function getRapportAlimentById($rapportAlimentsParams)
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    paramsVerify($rapportAlimentsParams, "Rapport Aliment");

    $rapportAlimentsFound = $rapportAlimentsModel->find($rapportAlimentsParams['id']);

    if (!empty($rapportAlimentsFound)) {
        $dataRA = getRapportAlimentDataById($rapportAlimentsFound->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_RAP_ALIMENT);
        $message = "Rapport Aliment Fetched successfully";
        return datasuccess200($message, $dataRA);
    } else {
        $message = "No rapport Aliment Found";
        return success205($message);
    }
}

function getListRapportAliments()
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = (array)$rapportAlimentsModel->findAll();

    if (!empty($rapportAliments)) {
        $dataListRA = getListRapportAlimentData($rapportAliments);
        $message = "Situation Rapport Aliment";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_ALIMENT);
        return dataTableSuccess200($message, $dataListRA);
    } else {
        $message = "Pas de situation dans le Rapport Aliment";
        return success205($message);
    }
}

# Update
function updateRapportAliments($rapportAlimentsData, $rapportAlimentsParams)
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = $rapportAlimentsModel;
    paramsVerify($rapportAlimentsParams, "Rapport Aliment");

    # On recupere les informations venues de POST
    $rapportAlimentsID = $rapportAlimentsParams['id'];

    $quantite = $rapportAlimentsData['quantite'];
    $date = $rapportAlimentsData['date'];
    $etat = $rapportAlimentsData['etat_rapportID'];
    $commentaire = $rapportAlimentsData['commentaire'];
    $agentID = $rapportAlimentsData['agents_idAgent'];
    $natureID = $rapportAlimentsData['natures_idNature'];
    //$statusRapportID = $rapportAlimentsData['status_rapport_id'];


    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testEtatR = testEtatRapportbyId($etat);

    if ($testNature && $testAgent && $testEtatR) {
        $rapportAliments->setQuantite($quantite);
        $rapportAliments->setDate($date);
        $rapportAliments->setEtat_rapportID($etat);
        $rapportAliments->setCommentaire($commentaire);
        $rapportAliments->setAgents_idAgent($agentID);
        $rapportAliments->setNatures_idNature($natureID);
        $rapportAliments->setUpdated_at(getSiku());


        $rapportAlimentsFound = $rapportAlimentsModel->find($rapportAlimentsID);

        if ($rapportAlimentsID == $rapportAlimentsFound->id) {
            $testStatutRapport = testStatutRapport($rapportAlimentsFound->status_rapport_id);
            if ($testStatutRapport) {
                $rapportAlimentsModel->update($rapportAlimentsID, $rapportAliments);
                # On ajoute l'Adresse  dans la BD
                $message = "Rapport Aliment updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_RAP_ALIMENT);
                return success200($message);
            }
        } else {
            $message = "No Rapport Aliment Found ";
            return success205($message);
        }
    }
}

function changeStatusRapportAliment($rapportAlimentsData, $rapportAlimentsParams)
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = $rapportAlimentsModel;

    $statusRID = $rapportAlimentsData['status_rapport_id'];
    $rapportAlimentsID = $rapportAlimentsParams['id'];
    paramsVerify($rapportAlimentsParams, "Rapport Aliment");

    $rapportAlimentsFound = $rapportAlimentsModel->find($rapportAlimentsID);
    if ($rapportAlimentsFound->id == $rapportAlimentsID) {
        $rapportAlimentsData['quantite'] = $rapportAlimentsFound->quantite;
        $rapportAlimentsData['natures_idNature'] = $rapportAlimentsFound->natures_idNature;
        $rapportAlimentsData['etat_rapportID'] = $rapportAlimentsFound->etat_rapportID;
        $rapportAlimentsData['agents_idAgent'] = $rapportAlimentsFound->agents_idAgent;
        $rapportAlimentsData['agents_id'] = $rapportAlimentsFound->agents_idAgent;
        $rapportAlimentsData['date'] = getSiku();

        $etatRID = $rapportAlimentsData['etat_rapportID'];
        $natureID = $rapportAlimentsData['natures_idNature'];
        natureVerify($natureID, DESIGN_ALIMENT);
        if ($rapportAlimentsFound->status_rapport_id == STATUS_RAPPORT_VALIDE) {
            # RAPPORT DEJA TRAITE : VALIDE
            $message = "le Status du Rapport Aliment a deja subi un traitement ";
            return success200($message);
        } elseif ($rapportAlimentsFound->status_rapport_id == STATUS_RAPPORT_ANNULE) {
            # RAPPORT DEJA TRAITE : ANNULE
            $message = "le Status du Rapport Aliment a deja subi un traitement ";

            return success200($message);
        } else {

            $simpleMod = array(STATUS_RAPPORT_ANNULE, STATUS_RAPPORT_REVISE, STATUS_RAPPORT_ETABLI);

            if (in_array($statusRID, $simpleMod)) {
                # RAPPORT SIMPLE A MODIFIER

                $rapportAliments->setStatus_rapport_id($statusRID);
                $rapportAliments->setUpdated_at(getSiku());
                $rapportAlimentsModel->update($rapportAlimentsID, $rapportAliments);
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_ALIMENT);
                $message = "le Status du Rapport Aliment a ete Modifie ";
                return success200($message);
            } elseif ($statusRID == STATUS_RAPPORT_VALIDE) {
                # Verification de l'Etat du Produit GOOD PRODUCT
                if (in_array($etatRID, GOOD_PRODUCT)) {
                    #RAPPORT VALIDE SIMPLE POUR BONNE QUALITE DU PRODUIT
                    $rapportAliments->setStatus_rapport_id($statusRID);
                    $rapportAliments->setUpdated_at(getSiku());
                    $rapportAlimentsModel->update($rapportAlimentsID, $rapportAliments);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_ALIMENT);
                    $message = "le Status du Rapport Aliment a ete Modifie ";
                    return success200($message);
                } elseif ((in_array($etatRID, BAD_PRODUCT)) && (VerifyQteStockAliment($natureID,  $rapportAlimentsData['quantite']))) {

                    sortieAliment($rapportAlimentsData);

                    $rapportAliments->setStatus_rapport_id($statusRID);
                    $rapportAliments->setUpdated_at(getSiku());
                    $rapportAlimentsModel->update($rapportAlimentsID, $rapportAliments);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_ALIMENT);
                    $message = "le Status du Rapport Aliment a ete Modifie ";
                    return success200($message);
                } else {
                    $message = "Veuillez revoir l'etat que vous avez inserer ";
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_ALIMENT);
                    return success205($message);
                }
            } else {
                $message = "Veuillez revoir le status que vous avez inserer ";
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_ALIMENT);
                return success205($message);
            }
        }
    } else {
        $message = "No Rapport Aliment Found ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_ALIMENT);
        return success205($message);
    }
}

function getListRapportAlimentsYear($rapportAlimentsParams)
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = (array)$rapportAlimentsModel->findAll();

    paramsVerifyYear($rapportAlimentsParams, "Rapport Aliment");
    $year = $rapportAlimentsParams['year'];

    if (!empty($rapportAliments)) {
        $listData = getListRapportAlimentDataYear($rapportAliments, $year);
        $message = "Situation Rapport Aliment de l'annee " . $year;
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_ALIMENT);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Aliment";
        return success205($message);
    }
}

function getListRapportAlimentsMonth()
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = (array)$rapportAlimentsModel->findAll();

    if (!empty($rapportAliments)) {
        $listData = getListRapportAlimentDataMonth($rapportAliments);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_ALIMENT);
        $message = "Situation Rapport Aliment de ce Mois ";
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Aliment";
        return success205($message);
    }
}

function getListRapportAlimentsWeek()
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = (array)$rapportAlimentsModel->findAll();
    if (!empty($rapportAliments)) {
        $listData = getListRapportAlimentDataWeek($rapportAliments);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_ALIMENT);
        $message = "Situation Rapport Aliment de la semaine  ";
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Aliment";
        return success205($message);
    }
}

function getListRapportAlimentsDay()
{
    $rapportAlimentsModel = new Rapport_alimentsModel();
    $rapportAliments = (array)$rapportAlimentsModel->findAll();

    if (!empty($rapportAliments)) {
        $listData = getListRapportAlimentDataDay($rapportAliments);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_ALIMENT);
        $message = "Situation Rapport Aliment de jour " . getSiku();
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Aliment";
        return success205($message);
    }
}