<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('..\Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Rapport_incubationsModel;

Autoloader::register();

# Store
function storeRapportInc($rapportIncData)
{
    $rapportIncuModel = new Rapport_incubationsModel();
    $rapportIncubation = $rapportIncuModel;

    # On recupere les informations venues de POST
    chargementRapport($rapportIncData);

    $quantite = $rapportIncData['quantite'];
    $rapportIncData['date'] = getSiku();
    $date = $rapportIncData['date'];
    $statusID = STATUS_RAPPORT_ETABLI;
    $commentaire = $rapportIncData['commentaire'];
    $agentID = $rapportIncData['agents_idAgent'];
    $natureID = $rapportIncData['natures_idNature'];
    $incubationID = $rapportIncData['incubation_id'];
    natureVerify($natureID, DESIGN_OEUF);
    $etat = $rapportIncData['etat_rapportID'];

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testStatutR = testStatutRapportbyId($statusID);
    $testInc = testIncubationbyId($incubationID);

    if ($testNature and $testAgent and $testStatutR and $testInc) {
        $rapportIncubation->setQuantite($quantite);
        $rapportIncubation->setDate($date);
        $rapportIncubation->setEtat_rapportID($etat);
        $rapportIncubation->setCommentaire($commentaire);
        $rapportIncubation->setAgents_idAgent($agentID);
        $rapportIncubation->setNatures_idNature($natureID);
        $rapportIncubation->setIncubation_id($incubationID);
        $rapportIncubation->setStatus_rapport_id($statusID);
        $rapportIncubation->setCreated_at(getSiku());

        # On ajoute la Designation dans la BD
        $rapportIncuModel->create($rapportIncubation);
        $message = "Rapport Incubateur  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_RAP_OEUF);
        return success201($message);
    }
}

#Delete
function deleteRapportInc($rapportIncubParams)
{
    $rapportIncuModel = new Rapport_incubationsModel();
    paramsVerify($rapportIncubParams, "Rapport Incubateur");

    $rapportIncubID = $rapportIncubParams['id'];
    $rapportIncData = $rapportIncuModel->find($rapportIncubID);

    if ($rapportIncubID == $rapportIncData->id) {
        $res = $rapportIncuModel->delete($rapportIncubID);
        $message = "Rapport Incubateur deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_RAP_OEUF);
        return success200($message);
    } else {
        $message = "Rapport Incubateur not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_RAP_OEUF);
        return error405($message);
    }
}

#Get
function getRapportIncById($rapportIncubParams)
{
    $rapportIncuModel = new Rapport_incubationsModel();
    paramsVerify($rapportIncubParams, "Rapport Incubateur");
    $rapportIncubFound = $rapportIncuModel->find($rapportIncubParams['id']);

    if (!empty($rapportIncubFound)) {
        $dataRO = getRapportIncDataById($rapportIncubFound->id);
        $message = "Rapport Incubateur Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_RAP_OEUF);
        return datasuccess200($message, $dataRO);
    } else {
        $message = "No rapport Incubateur Found";
        return success205($message);
    }
}

function getListRapportInc()
{
    $rapportIncuModel = new Rapport_incubationsModel();
    $rapportIncubation = (array)$rapportIncuModel->findAll();

    if (!empty($rapportIncubation)) {
        $dataListRO = getListRapportIncData($rapportIncubation);
        $message = "Situation Rapport Incubateur";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        return dataTableSuccess200($message, $dataListRO);
    } else {
        $message = "Pas de situation dans le Rapport Incubateur";
        return success205($message);
    }
}

# Update
function updateRapportInc($rapportIncData, $rapportIncubParams)
{
    $rapportIncuModel = new Rapport_incubationsModel();
    $rapportIncubation = $rapportIncuModel;
    paramsVerify($rapportIncubParams, "Rapport Incubateur");

    # On recupere les informations venues de POST
    $rapportIncubID = $rapportIncubParams['id'];

    $quantite = $rapportIncData['quantite'];
    $date = $rapportIncData['date'];
    $etat = $rapportIncData['etat_rapportID'];
    $commentaire = $rapportIncData['commentaire'];
    $agentID = $rapportIncData['agents_idAgent'];
    $natureID = $rapportIncData['natures_idNature'];
    $statusID = $rapportIncData['status_rapport_id'];

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testStatutR = testStatutRapportbyId($statusID);

    if ($testNature and $testAgent and $testStatutR) {
        $rapportIncubation->setQuantite($quantite);
        $rapportIncubation->setDate($date);
        $rapportIncubation->setEtat_rapportID($etat);
        $rapportIncubation->setCommentaire($commentaire);
        $rapportIncubation->setAgents_idAgent($agentID);
        $rapportIncubation->setNatures_idNature($natureID);
        $rapportIncubation->setStatus_rapport_id($statusID);
        $rapportIncubation->setUpdated_at(getSiku());

        $rapportIncubFound = $rapportIncuModel->find($rapportIncubID);

        if ($rapportIncubID == $rapportIncubFound->id) {
            $testStatutRapport = testStatutRapport($rapportIncubFound->status_rapport_id);
            if ($testStatutRapport) {
                $rapportIncuModel->update($rapportIncubID, $rapportIncubation);
                # On ajoute l'Adresse  dans la BD
                $message = "Rapport Incubation updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_RAP_OEUF);
                return success200($message);
            }
        } else {
            $message = "No Rapport Incubation Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_RAP_OEUF);
            return success205($message);
        }
    }
}

function changeStatusRapportInc($rapportIncData, $rapportIncubParams)
{
    $rapportIncModel = new Rapport_incubationsModel();
    $rapportIncubation = $rapportIncModel;

    $statusRID = $rapportIncData['status_rapport_id'];
    $rapportIncubID = $rapportIncubParams['id'];
    paramsVerify($rapportIncubParams, "Rapport Incubateur");

    $rapportIncubFound = $rapportIncModel->find($rapportIncubID);
    if ($rapportIncubFound->id == $rapportIncubID) {
        $rapportIncData['quantite'] = $rapportIncubFound->quantite;
        $rapportIncData['incubation_id'] = $rapportIncubFound->incubation_id;
        $rapportIncData['natures_idNature'] = $rapportIncubFound->natures_idNature;
        $rapportIncData['etat_rapportID'] = $rapportIncubFound->etat_rapportID;
        $rapportIncData['agents_idAgent'] = $rapportIncubFound->agents_idAgent;
        $rapportIncData['agents_id'] = $rapportIncubFound->agents_idAgent;
        $rapportIncData['date'] = getSiku();

        $etatRID = $rapportIncData['etat_rapportID'];
        $natureID = $rapportIncData['natures_idNature'];
        $testInc = testIncubationbyId($rapportIncData['incubation_id']);
        $incubationDataFound = getDataIncubationById($rapportIncData['incubation_id']);
        // if (in_array($rapportIncubFound->status_id, STATUS_INC_UPDATED)) {
        if (($testInc) && (in_array($incubationDataFound->status_id, STATUS_INC_UPDATED))) {
            natureVerify($natureID, DESIGN_OEUF);
            if ($rapportIncubFound->status_rapport_id == STATUS_RAPPORT_VALIDE) {
                # RAPPORT DEJA TRAITE : ETABLI
                $message = "le Status du Rapport Incubateur a deja subi un traitement ";
                return success200($message);
            } elseif ($rapportIncubFound->status_rapport_id == STATUS_RAPPORT_ANNULE) {
                # RAPPORT DEJA TRAITE : ANNULE
                $message = "le Status du Rapport Incubation a deja subi un traitement ";
                return success200($message);
            } else {

                $simpleMod = array(STATUS_RAPPORT_ANNULE, STATUS_RAPPORT_REVISE, STATUS_RAPPORT_ETABLI);

                if (in_array($statusRID, $simpleMod)) {
                    # RAPPORT SIMPLE A MODIFIER

                    $rapportIncubation->setStatus_rapport_id($statusRID);
                    $rapportIncubation->setUpdated_at(getSiku());
                    $rapportIncModel->update($rapportIncubID, $rapportIncubation);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_OEUF);
                    $message = "le Status du Rapport Incubateur a ete Modifie ";
                    return success200($message);
                } elseif ($statusRID == STATUS_RAPPORT_VALIDE) {
                    # Verification de l'Etat du Produit GOOD PRODUCT
                    if (in_array($etatRID, GOOD_PRODUCT)) {
                        #RAPPORT VALIDE SIMPLE POUR BONNE QUALITE DU PRODUIT
                        $rapportIncubation->setStatus_rapport_id($statusRID);
                        $rapportIncubation->setUpdated_at(getSiku());
                        $rapportIncModel->update($rapportIncubID, $rapportIncubation);
                        $message = "le Status du Rapport Incubateur a ete Modifie ";
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_OEUF);
                        return success200($message);
                    } elseif (in_array($etatRID, BAD_PRODUCT)) {

                        reduireStockIncubateur($rapportIncData);

                        // sortieOeuf($rapportIncData);

                        $rapportIncubation->setStatus_rapport_id($statusRID);
                        $rapportIncubation->setUpdated_at(getSiku());
                        $rapportIncModel->update($rapportIncubID, $rapportIncubation);
                        $message = "le Status du Rapport Incubateur a ete Modifie ";
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_OEUF);
                        return success200($message);
                    } else {
                        $message = "Veuillez revoir l'etat que vous avez inserer ";
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_OEUF);
                        return success205($message);
                    }
                } else {
                    $message = "Veuillez revoir le status que vous avez inserer ";
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_OEUF);
                    return success205($message);
                }
            }
        } else {
            $message = "le Status de ce rapport ne peut etre changer ";
            return success205($message);
        }
    } else {
        $message = "No Rapport Incubateur Found ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_OEUF);
        return success205($message);
    }
}


function getListRapportIncYear($rapportOeufsParams)
{
    $rapportIncModel = new Rapport_incubationsModel();
    $rapportOeufs = (array)$rapportIncModel->findAll();

    paramsVerifyYear($rapportOeufsParams, "Rapport Incubateur");
    $year = $rapportOeufsParams['year'];

    if (!empty($rapportOeufs)) {
        $listData = getListRapportIncDataYear($rapportOeufs, $year);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        $message = "Situation Rapport Incubateur de l'annee " . $year;
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Incubateur";
        return success205($message);
    }
}

function getListRapportIncMonth()
{
    $rapportIncModel = new Rapport_incubationsModel();
    $rapportOeufs = (array)$rapportIncModel->findAll();

    if (!empty($rapportOeufs)) {
        $listData = getListRapportIncDataMonth($rapportOeufs);
        $message = "Situation Rapport Incubateur de ce Mois ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Incubateur";
        return success205($message);
    }
}

function getListRapportIncWeek()
{
    $rapportIncModel = new Rapport_incubationsModel();
    $rapportOeufs = (array)$rapportIncModel->findAll();
    if (!empty($rapportOeufs)) {
        $listData = getListRapportIncDataWeek($rapportOeufs);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        $message = "Situation Rapport Incubateur de la semaine  ";
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Incubateur";
        return success205($message);
    }
}

function getListRapportIncDay()
{
    $rapportIncModel = new Rapport_incubationsModel();
    $rapportOeufs = (array)$rapportIncModel->findAll();

    if (!empty($rapportOeufs)) {
        $listData = getListRapportIncDataDay($rapportOeufs);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        $message = "Situation Rapport Incubateur de jour " . getSiku();
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Incubateur";
        return success205($message);
    }
}
