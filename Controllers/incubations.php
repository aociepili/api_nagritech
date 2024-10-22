<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\IncubationsModel;

Autoloader::register();

# Store
function storeIncubation($incubationsData)
{
    updateStatusIncubateur();
    $incubationsModel = new IncubationsModel();
    $incubations = $incubationsModel;
    chargementIncubation($incubationsData);

    $dateEntree = $incubationsData["dateEntree"];
    $datePrevue = dateIncubation($dateEntree);
    $today = getSiku();

    $quantite = $incubationsData["quantite"];
    $statusID = STATUS_INC_ENCOURS;
    $agentID = $incubationsData["agents_idAgent"];
    $natureID = $incubationsData["natures_idNature"];
    natureVerify($natureID, DESIGN_OEUF);

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);

    if ($testAgent and $testNature) {

        $incubationsData['etat_rapportID'] = ETAT_BON;
        $incubationsData['clients_idClient'] = ID_CLIENT_SYSTEME;
        $incubationsData['agents_id'] = $agentID;
        $incubationsData['motifSorties_idMotif'] = MOTIF_ENTREE_INCUBATEUR;
        $incubationsData['date'] = $today;

        sortieOeuf($incubationsData);

        $incubations->setDateEntree($dateEntree);
        $incubations->setDatePrevue($datePrevue);

        $incubations->setStatus_id($statusID);
        $incubations->setQuantite($quantite);
        $incubations->setAgents_idAgent($agentID);
        $incubations->setNatures_idNature($natureID);
        $incubations->setCreated_at($today);
        $incubationsModel->create($incubations);

        $message = "Incubations  created successfully";
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_INCUB);
        return success201($message);
    }
}

#Delete
function deleteIncubation($incubationsParams)
{
    updateStatusIncubateur();
    $incubationsModel = new IncubationsModel();
    paramsVerify($incubationsParams, "Incubation");

    $incubationsID = $incubationsParams['id'];
    $incubationsData = $incubationsModel->find($incubationsID);

    if ($incubationsID == $incubationsData->id) {
        $res = $incubationsModel->delete($incubationsID);
        $message = "Incubation deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_INCUB);
        return success200($message);
    } else {
        $message = "Incubation not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_INCUB);
        return error405($message);
    }
}

#Get
function getIncubationById($incubationsParams)
{
    updateStatusIncubateur();
    $incubationsModel = new IncubationsModel();
    paramsVerify($incubationsParams, "Incubation");
    $incubationsFound = $incubationsModel->find($incubationsParams['id']);

    if (!empty($incubationsFound)) {
        $dataIncubation = getIncubationDataById($incubationsFound->id);
        $message = "Produit en Incubation";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_INCUB);
        return datasuccess200($message, $dataIncubation);
    } else {
        $message = "No Incubations Found";
        return success205($message);
    }
}

function getListIncubation()
{
    updateStatusIncubateur();
    $incubationsModel = new IncubationsModel();
    $incubations = (array)$incubationsModel->findAll();

    if (!empty($incubations)) {
        $dataListIncubation = getListIncubationData($incubations);
        $message = "Liste des Produits en Incubation";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_INCUB);
        return dataTableSuccess200($message, $dataListIncubation);
    } else {
        $message = "Pas de Incubations";
        return success205($message);
    }
}

# Update
function updateIncubation($incubationsData, $incubationsParams)
{
    updateStatusIncubateur();
    $incubationsModel = new IncubationsModel();
    $incubations = $incubationsModel;
    paramsVerify($incubationsParams, "Incubation");

    $incubationsID = $incubationsParams['id'];
    $dateEntree = $incubationsData["dateEntree"];
    $datePrevue = $incubationsData["datePrevue"];
    $dateSortie = $incubationsData["dateSortie"];
    $quantite = $incubationsData["quantite"];
    $status = $incubationsData["status"];
    $agentID = $incubationsData["agent_idAgent"];
    $natureID = $incubationsData["natures_idNature"];

    $testNature = testNaturebyId($natureID);
    $testAgent = testAgentbyId($agentID);

    if ($testAgent and $testNature) {
        $incubations->setDateEntree($dateEntree);
        $incubations->setDatePrevue($datePrevue);
        $incubations->setDateSortie($dateSortie);
        $incubations->setStatus_id($status);
        $incubations->setQuantite($quantite);
        $incubations->setAgents_idAgent($agentID);
        $incubations->setNatures_idNature($natureID);
        $incubations->setUpdated_at(getSiku());

        $incubationsFound = $incubationsModel->find($incubationsID);
        if ($incubationsID == $incubationsFound->id) {
            $incubationsModel->update($incubationsID, $incubations);
            # On ajoute l'Adresse  dans la BD
            $message = "Incubation updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_INCUB);
            return success200($message);
        } else {
            $message = "No Incubation  Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_INCUB);
            return success205($message);
        }
    }
}
function sortieIncubateur($incubationsData, $incubationsParams)
{
    $incubationsModel = new IncubationsModel();
    $incubations = $incubationsModel;
    paramsVerify($incubationsParams, "Incubation");

    $incubationsID = $incubationsParams['id'];
    // $dateSortie = $incubationsData["dateSortie"];
    $dateSortie = getSiku();
    $statusInc = STATUS_INC_SORTIE;

    $incubations->setDateSortie($dateSortie);
    $incubations->setStatus_id($statusInc);
    $incubations->setUpdated_at(getSiku());

    $incubationsFound = $incubationsModel->find($incubationsID);
    if ($incubationsID == $incubationsFound->id) {

        if (testStatusInc($incubationsFound->status_id)) {

            $incubationsData['natures_idNature'] = heritageRace($incubationsFound->natures_idNature);
            $incubationsData['motifSorties_idMotif'] = MOTIF_SORTIE_INCUBATEUR;
            $incubationsData['fournisseur_id'] = ID_FOURNISSEUR_SYSTEME;
            $incubationsData['date'] = getSiku();
            $incubationsData['etat'] = ETAT_BON;
            $incubationsData['quantite'] = $incubationsFound->quantite;
            $incubationsData['designation_lot'] = "Lot Incubateur du " . $dateSortie;
            //debug400('Binakatal', $incubationsData);
            entreePoussin($incubationsData);
            $incubationsModel->update($incubationsID, $incubations);
            createActivity(TYPE_OP_OUT_INCUB, STATUS_OP_OK, TABLE_INCUB);
            # On ajoute l'Adresse  dans la BD
            $message = "Incubation updated successfully";
            return success200($message);
        } else {
            $message = "ce lot n'est plus dans l'incubateur";
            createActivity(TYPE_OP_OUT_INCUB, STATUS_OP_NOT, TABLE_INCUB);
            return success205($message);
        }
    } else {
        $message = "No Incubation  Found ";
        createActivity(TYPE_OP_OUT_INCUB, STATUS_OP_NOT, TABLE_INCUB);
        return success205($message);
    }
}

function updateStatusIncubateur()
{
    $incubationsModel = new IncubationsModel();
    $incubations = (array)$incubationsModel->findAll();

    if (!empty($incubations)) {
        for ($i = 0, $size = count($incubations); $i < $size; ++$i) {
            $test = (int)jourIntervalleDate($incubations[$i]->datePrevue);

            if ((in_array($incubations[$i]->status_id, STATUS_INC_UPDATED))) {
                // debug400("test", $test);
                if ($test > (-7) && $test < (-1)) {
                    changeStatusInc($incubations[$i], STATUS_INC_BIENTOT);
                } elseif (($test === 0) || ($test >= 0)) {
                    changeStatusInc($incubations[$i], STATUS_INC_TERMINE);
                }
            }
        }
    }
}