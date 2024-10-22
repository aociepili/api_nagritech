<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Rapport_pouletsModel;

Autoloader::register();

# Store
function storeRapportPoulets($rapportPouletsData)
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    $rapportPoulets = $rapportPouletsModel;

    # On recupere les informations venues de POST
    chargementRapport($rapportPouletsData);

    $quantite = $rapportPouletsData['quantite'];
    $rapportPouletsData['date'] = getSiku();
    $date = $rapportPouletsData['date'];
    $etat = $rapportPouletsData['etat_rapportID'];
    $commentaire = $rapportPouletsData['commentaire'];
    $agentID = $rapportPouletsData['agents_idAgent'];
    $natureID = $rapportPouletsData['natures_idNature'];
    $statusID = STATUS_RAPPORT_ETABLI;
    natureVerify($natureID, DESIGN_POULET);

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testStatutR = testStatutRapportbyId($statusID);
    $testEtatR = testEtatRapportbyId($etat);

    if ($testNature && $testAgent && $testStatutR && $testEtatR) {
        $rapportPoulets->setQuantite($quantite);
        $rapportPoulets->setDate($date);
        $rapportPoulets->setEtat_rapportID($etat);
        $rapportPoulets->setCommentaire($commentaire);
        $rapportPoulets->setAgents_idAgent($agentID);
        $rapportPoulets->setNatures_idNature($natureID);
        $rapportPoulets->setStatus_rapport_id($statusID);
        $rapportPoulets->setCreated_at(getSiku());

        # On ajoute la Designation dans la BD
        $rapportPouletsModel->create($rapportPoulets);
        $message = "Rapport Poulet  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_RAP_POULET);
        return success201($message);
    }
}

#Delete
function deleteRapportPoulets($rapportPouletsParams)
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    paramsVerify($rapportPouletsParams, "Rapport Poulet");

    $rapportPouletsID = $rapportPouletsParams['id'];
    $rapportPouletsData = $rapportPouletsModel->find($rapportPouletsID);

    if ($rapportPouletsID == $rapportPouletsData->id) {
        $res = $rapportPouletsModel->delete($rapportPouletsID);
        $message = "Rapport Poulet deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_RAP_POULET);
        return success200($message);
    } else {
        $message = "Rapport Poulet not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_RAP_POULET);
        return success205($message);
    }
}

#Get
function getRapportPouletById($rapportPouletsParams)
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    paramsVerify($rapportPouletsParams, "Rapport Poulet");

    $rapportPouletsFound = $rapportPouletsModel->find($rapportPouletsParams['id']);

    if (!empty($rapportPouletsFound)) {
        $dataRPt = getRapportPouletDataById($rapportPouletsFound->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_RAP_POULET);
        $message = "Rapport Poulet Fetched successfully";
        return datasuccess200($message, $dataRPt);
    } else {
        $message = "No rapport Poule Found";
        return success205($message);
    }
}

function getListRapportPoulets()
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    $rapportPoulets = (array)$rapportPouletsModel->findAll();

    if (!empty($rapportPoulets)) {
        $dataListRA = getListRapportPouletData($rapportPoulets);
        $message = "Situation Rapport Poulet";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULET);
        return dataTableSuccess200($message, $dataListRA);
    } else {
        $message = "Pas de situation dans le Rapport Poulet";
        return success205($message);
    }
}

# Update
function updateRapportPoulets($rapportPouletsData, $rapportPouletsParams)
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    $rapportPoulets = $rapportPouletsModel;
    paramsVerify($rapportPouletsParams, "Rapport Poulet");

    # On recupere les informations venues de POST
    $rapportPouletsID = $rapportPouletsParams['id'];

    $quantite = $rapportPouletsData['quantite'];
    $date = $rapportPouletsData['date'];
    $etat = $rapportPouletsData['etat_rapportID'];
    $commentaire = $rapportPouletsData['commentaire'];
    $agentID = $rapportPouletsData['agents_idAgent'];
    $natureID = $rapportPouletsData['natures_idNature'];


    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testEtatR = testEtatRapportbyId($etat);

    if ($testNature && $testAgent && $testEtatR) {
        $rapportPoulets->setQuantite($quantite);
        $rapportPoulets->setDate($date);
        $rapportPoulets->setEtat_rapportID($etat);
        $rapportPoulets->setCommentaire($commentaire);
        $rapportPoulets->setAgents_idAgent($agentID);
        $rapportPoulets->setNatures_idNature($natureID);
        $rapportPoulets->setUpdated_at(getSiku());


        $rapportPouletsFound = $rapportPouletsModel->find($rapportPouletsID);

        if ($rapportPouletsID == $rapportPouletsFound->id) {
            $testStatutRapport = testStatutRapport($rapportPouletsFound->status_rapport_id);
            if ($testStatutRapport) {
                $rapportPouletsModel->update($rapportPouletsID, $rapportPoulets);
                # On ajoute l'Adresse  dans la BD
                $message = "Rapport Poulet updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_RAP_POULET);
                return success200($message);
            }
        } else {
            $message = "No Rapport Poulet Found ";
            return success205($message);
        }
    }
}

function changeStatusRapportPoulet($rapportPouletsData, $rapportPouletsParams)
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    $rapportPoulets = $rapportPouletsModel;

    $statusRID = $rapportPouletsData['status_rapport_id'];
    $rapportPouletsID = $rapportPouletsParams['id'];
    paramsVerify($rapportPouletsParams, "Rapport Poulet");

    $rapportPouletsFound = $rapportPouletsModel->find($rapportPouletsID);
    if ($rapportPouletsFound->id == $rapportPouletsID) {
        $rapportPouletsData['quantite'] = $rapportPouletsFound->quantite;
        $rapportPouletsData['natures_idNature'] = $rapportPouletsFound->natures_idNature;
        $rapportPouletsData['etat_rapportID'] = $rapportPouletsFound->etat_rapportID;
        $rapportPouletsData['agents_idAgent'] = $rapportPouletsFound->agents_idAgent;
        $rapportPouletsData['agents_id'] = $rapportPouletsFound->agents_idAgent;
        $rapportPouletsData['date'] = getSiku();

        $etatRID = $rapportPouletsData['etat_rapportID'];
        $natureID = $rapportPouletsData['natures_idNature'];
        natureVerify($natureID, DESIGN_POULET);
        if ($rapportPouletsFound->status_rapport_id == STATUS_RAPPORT_VALIDE) {
            # RAPPORT DEJA TRAITE : VALIDE
            $message = "le Status du Rapport Poulet a deja subi un traitement ";
            return success200($message);
        } elseif ($rapportPouletsFound->status_rapport_id == STATUS_RAPPORT_ANNULE) {
            # RAPPORT DEJA TRAITE : ANNULE
            $message = "le Status du Rapport Poulet a deja subi un traitement ";

            return success200($message);
        } else {

            $simpleMod = array(STATUS_RAPPORT_ANNULE, STATUS_RAPPORT_REVISE, STATUS_RAPPORT_ETABLI);

            if (in_array($statusRID, $simpleMod)) {
                # RAPPORT SIMPLE A MODIFIER

                $rapportPoulets->setStatus_rapport_id($statusRID);
                $rapportPoulets->setUpdated_at(getSiku());
                $rapportPouletsModel->update($rapportPouletsID, $rapportPoulets);
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_POULET);
                $message = "le Status du Rapport Poulet a ete Modifie ";
                return success200($message);
            } elseif ($statusRID == STATUS_RAPPORT_VALIDE) {
                # Verification de l'Etat du Produit GOOD PRODUCT
                if (in_array($etatRID, GOOD_PRODUCT)) {
                    #RAPPORT VALIDE SIMPLE POUR BONNE QUALITE DU PRODUIT
                    $rapportPoulets->setStatus_rapport_id($statusRID);
                    $rapportPoulets->setUpdated_at(getSiku());
                    $rapportPouletsModel->update($rapportPouletsID, $rapportPoulets);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_POULET);
                    $message = "le Status du Rapport Poulet a ete Modifie ";
                    return success200($message);
                } elseif ((in_array($etatRID, BAD_PRODUCT)) && (VerifyQteStockPoulet($natureID,  $rapportPouletsData['quantite']))) {

                    sortiePoulet($rapportPouletsData);

                    $rapportPoulets->setStatus_rapport_id($statusRID);
                    $rapportPoulets->setUpdated_at(getSiku());
                    $rapportPouletsModel->update($rapportPouletsID, $rapportPoulets);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_POULET);
                    $message = "le Status du Rapport Poulet a ete Modifie ";
                    return success200($message);
                } else {
                    $message = "Veuillez revoir l'etat que vous avez inserer ";
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_POULET);
                    return success205($message);
                }
            } else {
                $message = "Veuillez revoir le status que vous avez inserer ";
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_POULET);
                return success205($message);
            }
        }
    } else {
        $message = "No Rapport Poulet Found ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_POULET);
        return success205($message);
    }
}

function getListRapportPouletsYear($rapportPouletsParams)
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    $rapportPoulets = (array)$rapportPouletsModel->findAll();

    paramsVerifyYear($rapportPouletsParams, "Rapport Poulet");
    $year = $rapportPouletsParams['year'];

    if (!empty($rapportPoulets)) {
        $listData = getListRapportPouletDataYear($rapportPoulets, $year);
        $message = "Situation Rapport Poulet de l'annee " . $year;
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULET);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poulet";
        return success205($message);
    }
}

function getListRapportPouletsMonth()
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    $rapportPoulets = (array)$rapportPouletsModel->findAll();

    if (!empty($rapportPoulets)) {
        $listData = getListRapportPouletDataMonth($rapportPoulets);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULET);
        $message = "Situation Rapport Poulet de ce Mois ";
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poulet";
        return success205($message);
    }
}

function getListRapportPouletsWeek()
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    $rapportPoulets = (array)$rapportPouletsModel->findAll();
    if (!empty($rapportPoulets)) {
        $listData = getListRapportPouletDataWeek($rapportPoulets);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULET);
        $message = "Situation Rapport Poulet de la semaine  ";
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poulet";
        return success205($message);
    }
}

function getListRapportPouletsDay()
{
    $rapportPouletsModel = new Rapport_pouletsModel();
    $rapportPoulets = (array)$rapportPouletsModel->findAll();

    if (!empty($rapportPoulets)) {
        $listData = getListRapportPouletDataDay($rapportPoulets);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_POULET);
        $message = "Situation Rapport Poulet de jour " . getSiku();
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Poulet";
        return success205($message);
    }
}