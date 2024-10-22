<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('..\Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Rapport_oeufsModel;

Autoloader::register();

# Store
function storeRapportOeuf($rapportOeufData)
{

    $rapportOeufsModel = new Rapport_oeufsModel();
    $rapportOeuf = $rapportOeufsModel;

    # On recupere les informations venues de POST
    chargementRapport($rapportOeufData);


    $quantite = $rapportOeufData['quantite'];
    $rapportOeufData['date'] = getSiku();
    $date = $rapportOeufData['date'];
    $statusID = STATUS_RAPPORT_ETABLI;
    $commentaire = $rapportOeufData['commentaire'];
    $agentID = $rapportOeufData['agents_idAgent'];
    $natureID = $rapportOeufData['natures_idNature'];
    natureVerify($natureID, DESIGN_OEUF);
    $etat = $rapportOeufData['etat_rapportID'];

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    $testStatutR = testStatutRapportbyId($statusID);

    if ($testNature and $testAgent and $testStatutR) {
        $rapportOeuf->setQuantite($quantite);
        $rapportOeuf->setDate($date);
        $rapportOeuf->setEtat_rapportID($etat);
        $rapportOeuf->setCommentaire($commentaire);
        $rapportOeuf->setAgents_idAgent($agentID);
        $rapportOeuf->setNatures_idNature($natureID);
        $rapportOeuf->setStatus_rapport_id($statusID);
        $rapportOeuf->setCreated_at(getSiku());

        # On ajoute la Designation dans la BD
        $rapportOeufsModel->create($rapportOeuf);
        $message = "Rapport Oeuf  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_RAP_OEUF);
        return success201($message);
    }
}

#Delete
function deleteRapportOeuf($rapportOeufParams)
{
    $rapportOeufsModel = new Rapport_oeufsModel();
    paramsVerify($rapportOeufParams, "Rapport Oeuf");

    $rapportOeufID = $rapportOeufParams['id'];
    $rapportOeufData = $rapportOeufsModel->find($rapportOeufID);

    if ($rapportOeufID == $rapportOeufData->id) {
        $res = $rapportOeufsModel->delete($rapportOeufID);
        $message = "Rapport Oeuf deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_RAP_OEUF);
        return success200($message);
    } else {
        $message = "Rapport Oeuf not delete  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_RAP_OEUF);
        return error405($message);
    }
}

#Get
function getRapportOeufById($rapportOeufParams)
{
    $rapportOeufsModel = new Rapport_oeufsModel();
    paramsVerify($rapportOeufParams, "Rapport Oeuf");
    $rapportOeufFound = $rapportOeufsModel->find($rapportOeufParams['id']);

    if (!empty($rapportOeufFound)) {
        $dataRO = getRapportOeufDataById($rapportOeufFound->id);
        $message = "Rapport Oeuf Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_RAP_OEUF);
        return datasuccess200($message, $dataRO);
    } else {
        $message = "No rapport Oeuf Found";
        return success205($message);
    }
}

function getListRapportOeuf()
{
    $rapportOeufsModel = new Rapport_oeufsModel();
    $rapportOeuf = (array)$rapportOeufsModel->findAll();

    if (!empty($rapportOeuf)) {
        $dataListRO = getListRapportOeufData($rapportOeuf);
        $message = "Situation Rapport Oeuf";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        return dataTableSuccess200($message, $dataListRO);
    } else {
        $message = "Pas de situation dans le Rapport Oeuf";
        return success205($message);
    }
}

# Update
function updateRapportOeuf($rapportOeufData, $rapportOeufParams)
{
    $rapportOeufsModel = new Rapport_oeufsModel();
    $rapportOeuf = $rapportOeufsModel;
    paramsVerify($rapportOeufParams, "Rapport Oeuf");

    # On recupere les informations venues de POST
    $rapportOeufID = $rapportOeufParams['id'];

    $quantite = $rapportOeufData['quantite'];
    $date = $rapportOeufData['date'];
    $etat = $rapportOeufData['etat_rapportID'];
    $commentaire = $rapportOeufData['commentaire'];
    $agentID = $rapportOeufData['agents_idAgent'];
    $natureID = $rapportOeufData['natures_idNature'];
    $statusID = $rapportOeufData['status_rapport_id'];

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);
    // $testStatutR = testStatutRapportbyId($statusID);
    $testEtatR = testEtatRapportbyId($etat);

    if ($testNature and $testAgent and $testEtatR) {
        $rapportOeuf->setQuantite($quantite);
        $rapportOeuf->setDate($date);
        $rapportOeuf->setEtat_rapportID($etat);
        $rapportOeuf->setCommentaire($commentaire);
        $rapportOeuf->setAgents_idAgent($agentID);
        $rapportOeuf->setNatures_idNature($natureID);
        $rapportOeuf->setStatus_rapport_id($statusID);
        $rapportOeuf->setUpdated_at(getSiku());

        $rapportOeufFound = $rapportOeufsModel->find($rapportOeufID);

        if ($rapportOeufID == $rapportOeufFound->id) {
            $testStatutRapport = testStatutRapport($rapportOeufFound->status_rapport_id);
            if ($testStatutRapport) {
                $rapportOeufsModel->update($rapportOeufID, $rapportOeuf);
                # On ajoute l'Adresse  dans la BD
                $message = "Rapport oeuf updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_RAP_OEUF);
                return success200($message);
            }
        } else {
            $message = "No Rapport oeuf Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_RAP_OEUF);
            return success205($message);
        }
    }
}

function changeStatusRapportOeuf($rapportOeufData, $rapportOeufParams)
{
    $rapportOeufModel = new Rapport_oeufsModel();
    $rapportOeuf = $rapportOeufModel;

    $statusRID = $rapportOeufData['status_rapport_id'];
    $rapportOeufID = $rapportOeufParams['id'];
    paramsVerify($rapportOeufParams, "Rapport Oeuf");

    $rapportOeufFound = $rapportOeufModel->find($rapportOeufID);
    if ($rapportOeufFound->id == $rapportOeufID) {
        $rapportOeufData['quantite'] = $rapportOeufFound->quantite;
        $rapportOeufData['quantite'] = $rapportOeufFound->quantite;
        $rapportOeufData['natures_idNature'] = $rapportOeufFound->natures_idNature;
        $rapportOeufData['etat_rapportID'] = $rapportOeufFound->etat_rapportID;
        $rapportOeufData['agents_idAgent'] = $rapportOeufFound->agents_idAgent;
        $rapportOeufData['agents_id'] = $rapportOeufFound->agents_idAgent;
        $rapportOeufData['date'] = getSiku();

        $etatRID = $rapportOeufData['etat_rapportID'];
        $natureID = $rapportOeufData['natures_idNature'];
        natureVerify($natureID, DESIGN_OEUF);
        if ($rapportOeufFound->status_rapport_id == STATUS_RAPPORT_VALIDE) {
            # RAPPORT DEJA TRAITE : ETABLI
            $message = "le Status du Rapport Oeuf a deja subi un traitement ";
            return success200($message);
        } elseif ($rapportOeufFound->status_rapport_id == STATUS_RAPPORT_ANNULE) {
            # RAPPORT DEJA TRAITE : ANNULE
            $message = "le Status du Rapport oeuf a deja subi un traitement ";
            return success200($message);
        } else {

            $simpleMod = array(STATUS_RAPPORT_ANNULE, STATUS_RAPPORT_REVISE, STATUS_RAPPORT_ETABLI);

            if (in_array($statusRID, $simpleMod)) {
                # RAPPORT SIMPLE A MODIFIER

                $rapportOeuf->setStatus_rapport_id($statusRID);
                $rapportOeuf->setUpdated_at(getSiku());
                $rapportOeufModel->update($rapportOeufID, $rapportOeuf);
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_OEUF);
                $message = "le Status du Rapport Oeuf a ete Modifie ";
                return success200($message);
            } elseif ($statusRID == STATUS_RAPPORT_VALIDE) {
                # Verification de l'Etat du Produit GOOD PRODUCT
                if (in_array($etatRID, GOOD_PRODUCT)) {
                    #RAPPORT VALIDE SIMPLE POUR BONNE QUALITE DU PRODUIT
                    $rapportOeuf->setStatus_rapport_id($statusRID);
                    $rapportOeuf->setUpdated_at(getSiku());
                    $rapportOeufModel->update($rapportOeufID, $rapportOeuf);
                    $message = "le Status du Rapport Oeuf a ete Modifie ";
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_RAP_OEUF);
                    return success200($message);
                } elseif ((in_array($etatRID, BAD_PRODUCT)) && (VerifyQteStockOeuf($natureID,  $rapportOeufData['quantite']))) {

                    sortieOeuf($rapportOeufData);
                    // debug400('Ime rudiya MBOVU', $rapportOeufData);
                    $rapportOeuf->setStatus_rapport_id($statusRID);
                    $rapportOeuf->setUpdated_at(getSiku());
                    $rapportOeufModel->update($rapportOeufID, $rapportOeuf);
                    $message = "le Status du Rapport Oeuf a ete Modifie ";
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
        $message = "No Rapport Oeuf Found ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_RAP_OEUF);
        return success205($message);
    }
}


function getListRapportOeufYear($rapportOeufsParams)
{
    $rapportOeufModel = new Rapport_oeufsModel();
    $rapportOeufs = (array)$rapportOeufModel->findAll();

    paramsVerifyYear($rapportOeufsParams, "Rapport Oeuf");
    $year = $rapportOeufsParams['year'];

    if (!empty($rapportOeufs)) {
        $listData = getListRapportOeufDataYear($rapportOeufs, $year);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        $message = "Situation Rapport Oeuf de l'annee " . $year;
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Oeuf";
        return success205($message);
    }
}

function getListRapportOeufMonth()
{
    $rapportOeufModel = new Rapport_oeufsModel();
    $rapportOeufs = (array)$rapportOeufModel->findAll();

    if (!empty($rapportOeufs)) {
        $listData = getListRapportOeufDataMonth($rapportOeufs);
        $message = "Situation Rapport Oeuf de ce Mois ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Oeuf";
        return success205($message);
    }
}

function getListRapportOeufWeek()
{
    $rapportOeufModel = new Rapport_oeufsModel();
    $rapportOeufs = (array)$rapportOeufModel->findAll();
    if (!empty($rapportOeufs)) {
        $listData = getListRapportOeufDataWeek($rapportOeufs);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        $message = "Situation Rapport Oeuf de la semaine  ";
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Oeuf";
        return success205($message);
    }
}

function getListRapportOeufDay()
{
    $rapportOeufModel = new Rapport_oeufsModel();
    $rapportOeufs = (array)$rapportOeufModel->findAll();

    if (!empty($rapportOeufs)) {
        $listData = getListRapportOeufDataDay($rapportOeufs);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAP_OEUF);
        $message = "Situation Rapport Oeuf de jour " . getSiku();
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport Oeuf";
        return success205($message);
    }
}