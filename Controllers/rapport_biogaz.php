<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('..\Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Rapport_biogazModel;

Autoloader::register();

# Store
function storeRapportBiogaz($rapportBiogazdata)
{

    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = $rapportBiogazModel;

    # On recupere les informations venues de POST
    chargementRapport($rapportBiogazdata);

    $quantite = $rapportBiogazdata['quantite'];
    $rapportBiogazdata['date'] = getSiku();
    $date = $rapportBiogazdata['date'];
    $statusID = STATUS_RAPPORT_ETABLI;
    $commentaire = $rapportBiogazdata['commentaire'];
    $agentID = $rapportBiogazdata['agents_idAgent'];
    $natureID = $rapportBiogazdata['natures_idNature'];
    $etat = $rapportBiogazdata['etat_rapportID'];
    natureVerify($natureID, DESIGN_BIOGAZ);

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testStatutR = testStatutRapportbyId($statusID);

    if ($testNature and $testAgent and $testStatutR) {
        $rapportBiogaz->setQuantite($quantite);
        $rapportBiogaz->setDate($date);
        $rapportBiogaz->setEtat_rapportID($etat);
        $rapportBiogaz->setCommentaire($commentaire);
        $rapportBiogaz->setAgents_idAgent($agentID);
        $rapportBiogaz->setNatures_idNature($natureID);
        $rapportBiogaz->setStatus_rapport_id($statusID);
        $rapportBiogaz->setCreated_at(getSiku());


        # On ajoute la Designation dans la BD
        $rapportBiogazModel->create($rapportBiogaz);
        $message = "Rapport Biogaz  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
        return success201($message);
    }
}

#Delete
function deleteRapportBiogaz($rapportBiogazParams)
{
    $rapportBiogazModel = new Rapport_biogazModel();
    paramsVerify($rapportBiogazParams, "Rapport Biogaz");

    $rapportBiogazID = $rapportBiogazParams['id'];
    $rapportBiogazdata = $rapportBiogazModel->find($rapportBiogazID);

    if ($rapportBiogazID == $rapportBiogazdata->id) {
        $res = $rapportBiogazModel->delete($rapportBiogazID);
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
        $message = "Rapport Biogaz deleted successfully";
        return success200($message);
    } else {
        $message = "Rapport Biogaz not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_RAP_BIOGAZ);
        return error405($message);
    }
}

#Get
function getRapportBiogazById($rapportBiogazParams)
{
    $rapportBiogazModel = new Rapport_biogazModel();
    paramsVerify($rapportBiogazParams, "Rapport Biogaz");
    $rapportBiogazFound = $rapportBiogazModel->find($rapportBiogazParams['id']);

    if (!empty($rapportBiogazFound)) {
        $dataRBG = getRapportBiogazDataById($rapportBiogazFound->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
        $message = "Rapport Biogaz Fetched successfully";
        return datasuccess200($message, $dataRBG);
    } else {
        $message = "No rapport Biogaz Found";
        return success205($message);
    }
}

function getListRapportBiogaz()
{
    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = (array)$rapportBiogazModel->findAll();

    if (!empty($rapportBiogaz)) {
        $dataListRBG = getListRapportBiogazData($rapportBiogaz);
        $message = "Situation Rapport Biogaz";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
        return dataTableSuccess200($message, $dataListRBG);
    } else {
        $message = "Pas de situation dans le Rapport Biogaz";
        return success205($message);
    }
}

# Update
function updateRapportBiogaz($rapportBiogazdata, $rapportBiogazParams)
{
    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = $rapportBiogazModel;
    paramsVerify($rapportBiogazParams, "Rapport Biogaz");

    # On recupere les informations venues de POST
    $rapportBiogazID = $rapportBiogazParams['id'];

    $quantite = $rapportBiogazdata['quantite'];
    $date = $rapportBiogazdata['date'];
    $etat = $rapportBiogazdata['etat_rapportID'];
    $commentaire = $rapportBiogazdata['commentaire'];
    $agentID = $rapportBiogazdata['agents_idAgent'];
    $natureID = $rapportBiogazdata['natures_idNature'];
    $statusID = $rapportBiogazdata['status_rapport_id'];

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    // $testStatutR = testStatutRapportbyId($statusID);
    $testEtatR = testEtatRapportbyId($etat);

    if ($testNature and $testAgent and $testEtatR) {
        $rapportBiogaz->setQuantite($quantite);
        $rapportBiogaz->setDate($date);
        $rapportBiogaz->setEtat_rapportID($etat);
        $rapportBiogaz->setCommentaire($commentaire);
        $rapportBiogaz->setAgents_idAgent($agentID);
        $rapportBiogaz->setNatures_idNature($natureID);
        $rapportBiogaz->setStatus_rapport_id($statusID);
        $rapportBiogaz->setUpdated_at(getSiku());


        $rapportBiogazFound = $rapportBiogazModel->find($rapportBiogazID);

        if ($rapportBiogazID == $rapportBiogazFound->id) {
            $testStatutRapport = testStatutRapport($rapportBiogazFound->status_rapport_id);
            if ($testStatutRapport) {
                $rapportBiogazModel->update($rapportBiogazID, $rapportBiogaz);
                # On ajoute l'Adresse  dans la BD
                $message = "Rapport Biogaz updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
                return success200($message);
            }
        } else {
            $message = "No Rapport Biogaz Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_RAP_BIOGAZ);
            return success205($message);
        }
    }
}

function changeStatusRapportBiogaz($rapportBiogazData, $rapportBiogazParams)
{
    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = $rapportBiogazModel;

    $statusRID = $rapportBiogazData['status_rapport_id'];
    $rapportBiogazID = $rapportBiogazParams['id'];
    paramsVerify($rapportBiogazParams, "Rapport Biogaz");

    $rapportBiogazFound = $rapportBiogazModel->find($rapportBiogazID);
    if ($rapportBiogazFound->id == $rapportBiogazID) {
        $rapportBiogazData['quantite'] = $rapportBiogazFound->quantite;
        $rapportBiogazData['natures_idNature'] = $rapportBiogazFound->natures_idNature;
        $rapportBiogazData['etat_rapportID'] = $rapportBiogazFound->etat_rapportID;
        $rapportBiogazData['agents_idAgent'] = $rapportBiogazFound->agents_idAgent;
        $rapportBiogazData['agents_id'] = $rapportBiogazFound->agents_idAgent;
        $rapportBiogazData['date'] = getSiku();

        $etatRID = $rapportBiogazData['etat_rapportID'];
        $natureID = $rapportBiogazData['natures_idNature'];
        natureVerify($natureID, DESIGN_BIOGAZ);
        if ($rapportBiogazFound->status_rapport_id == STATUS_RAPPORT_VALIDE) {
            # RAPPORT DEJA TRAITE : ETABLI
            $message = "le Status du Rapport Biogaz a deja subi un traitement ";
            return success200($message);
        } elseif ($rapportBiogazFound->status_rapport_id == STATUS_RAPPORT_ANNULE) {
            # RAPPORT DEJA TRAITE : ANNULE
            $message = "le Status du Rapport Biogaz a deja subi un traitement ";
            return success200($message);
        } else {

            $simpleMod = array(STATUS_RAPPORT_ANNULE, STATUS_RAPPORT_REVISE, STATUS_RAPPORT_ETABLI);

            if (in_array($statusRID, $simpleMod)) {
                # RAPPORT SIMPLE A MODIFIER

                $rapportBiogaz->setStatus_rapport_id($statusRID);
                $rapportBiogaz->setUpdated_at(getSiku());
                $rapportBiogazModel->update($rapportBiogazID, $rapportBiogaz);
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
                $message = "le Status du Rapport Biogaz a ete Modifie ";
                return success200($message);
            } elseif ($statusRID == STATUS_RAPPORT_VALIDE) {
                # Verification de l'Etat du Produit GOOD PRODUCT
                if (in_array($etatRID, GOOD_PRODUCT)) {
                    #RAPPORT VALIDE SIMPLE POUR BONNE QUALITE DU PRODUIT
                    $rapportBiogaz->setStatus_rapport_id($statusRID);
                    $rapportBiogaz->setUpdated_at(getSiku());
                    $rapportBiogazModel->update($rapportBiogazID, $rapportBiogaz);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
                    $message = "le Status du Rapport Biogaz a ete Modifie ";
                    return success200($message);
                } elseif ((in_array($etatRID, BAD_PRODUCT)) && (VerifyQteStockBiogaz($natureID,  $rapportBiogazData['quantite']))) {

                    sortieBiogaz($rapportBiogazData);
                    // debug400('Ime rudiya MBOVU', $rapportBiogazData);
                    $rapportBiogaz->setStatus_rapport_id($statusRID);
                    $rapportBiogaz->setUpdated_at(getSiku());
                    $rapportBiogazModel->update($rapportBiogazID, $rapportBiogaz);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
                    $message = "le Status du Rapport Biogaz a ete Modifie ";
                    return success200($message);
                } else {
                    $message = "Veuillez revoir l'etat que vous avez inserer ";
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_BIOGAZ);
                    return success205($message);
                }
            } else {
                $message = "Veuillez revoir le status que vous avez inserer ";
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_BIOGAZ);
                return success205($message);
            }
        }
    } else {
        $message = "No Rapport Biogaz Found ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_BIOGAZ);
        return success205($message);
    }
}


function getListRapportBiogazYear($rapportBiogazParams)
{
    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = (array)$rapportBiogazModel->findAll();

    paramsVerifyYear($rapportBiogazParams, "Rapport Biogaz");
    $year = $rapportBiogazParams['year'];

    if (!empty($rapportBiogaz)) {
        $listData = getListRapportBiogazDataYear($rapportBiogaz, $year);
        $message = "Situation Rapport Biogaz de l'annee " . $year;
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Biogaz";
        return success205($message);
    }
}

function getListRapportBiogazMonth()
{
    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = (array)$rapportBiogazModel->findAll();

    if (!empty($rapportBiogaz)) {
        $listData = getListRapportBiogazDataMonth($rapportBiogaz);
        $message = "Situation Rapport Biogaz de ce Mois ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Biogaz";
        return success205($message);
    }
}

function getListRapportBiogazWeek()
{
    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = (array)$rapportBiogazModel->findAll();
    if (!empty($rapportBiogaz)) {
        $listData = getListRapportBiogazDataWeek($rapportBiogaz);
        $message = "Situation Rapport Biogaz de la semaine  ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Biogaz";
        return success205($message);
    }
}

function getListRapportBiogazDay()
{
    $rapportBiogazModel = new Rapport_biogazModel();
    $rapportBiogaz = (array)$rapportBiogazModel->findAll();

    if (!empty($rapportBiogaz)) {
        $listData = getListRapportBiogazDataDay($rapportBiogaz);
        $message = "Situation Rapport Biogaz de jour " . getSiku();
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_BIOGAZ);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Biogaz";
        return success205($message);
    }
}