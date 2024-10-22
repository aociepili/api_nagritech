<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Commande_fournisseursModel;

Autoloader::register();

# Store
function storeCommandeFournisseur($commandeFournisseursData)
{
    $commandeFournisseursModel = new Commande_fournisseursModel();
    $commandeFournisseurs = $commandeFournisseursModel;

    chargementCommandeFournisseur($commandeFournisseursData);

    $quantite = $commandeFournisseursData["quantite"];
    $status = $commandeFournisseursData["status"];
    $dateBut = $commandeFournisseursData["dateDebut"];
    $dateFin = $commandeFournisseursData["dateFin"];
    $natureID = $commandeFournisseursData["natures_idNature"];
    $fournisseurID = $commandeFournisseursData["Fournisseurs_idFournisseur"];
    $today = getSiku();

    $testFournisseur = testFournisseurbyId($fournisseurID);
    $testNature = testNaturebyId($natureID);

    if ($testFournisseur and $testNature) {
        $commandeFournisseurs->setQuantite($quantite);
        $commandeFournisseurs->setStatus($status);
        $commandeFournisseurs->setDateDebut($dateBut);
        $commandeFournisseurs->setDateFin($dateFin);
        $commandeFournisseurs->setNatures_idNature($natureID);
        $commandeFournisseurs->setFournisseurs_idFournisseur($fournisseurID);
        $commandeFournisseurs->setCreated_at($today);

        $commandeFournisseursModel->create($commandeFournisseurs);
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CMD_FOUR);
        $message = "Commande Fournisseur  created successfully";
        return success201($message);
    }
}

#Delete
function deleteCommandeFournisseur($commandeFournisseursParams)
{
    $commandeFournisseursModel = new Commande_fournisseursModel();
    paramsVerify($commandeFournisseursParams, "Commande Fournisseur");

    $commandeFournisseursID = $commandeFournisseursParams['id'];
    $commandeFournisseursData = $commandeFournisseursModel->find($commandeFournisseursID);

    if ($commandeFournisseursID == $commandeFournisseursData->id) {
        $commandeFournisseursModel->delete($commandeFournisseursID);
        $message = "Commande Fournisseur deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CMD_FOUR);
        return success200($message);
    } else {
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_CMD_FOUR);
        $message = "Commande Fournisseur not Delete  ";
        return error405($message);
    }
}

#Get
function getCommandeFournisseurById($commandeFournisseursParams)
{
    $commandeFournisseursModel = new Commande_fournisseursModel();
    paramsVerify($commandeFournisseursParams, "Commande Fournisseur");
    $commandeFournisseursFound = $commandeFournisseursModel->find($commandeFournisseursParams['id']);

    if (!empty($commandeFournisseursFound)) {
        $dataCF = getCommandeFournisseurDataById($commandeFournisseursFound->id);
        $message = "Commande Fournisseur Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CMD_FOUR);
        return datasuccess200($message, $dataCF);
    } else {
        $message = "No commande Fournisseur Found";
        return success205($message);
    }
}

function getListCommandeFournisseur()
{
    $commandeFournisseursModel = new Commande_fournisseursModel();
    $commandeFournisseurs = (array)$commandeFournisseursModel->findAll();

    if (!empty($commandeFournisseurs)) {
        $dataListCF = getListCommandeFournisseurData($commandeFournisseurs);
        $message = "Liste des Commandes Fournisseur";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CMD_FOUR);
        return dataTableSuccess200($message, $dataListCF);
    } else {
        $message = "Pas de Commandes Fournisseur";
        return success205($message);
    }
}

# Update
function updateCommandeFournisseur($commandeFournisseursData, $commandeFournisseursParams)
{
    $commandeFournisseursModel = new Commande_fournisseursModel();
    $commandeFournisseurs = $commandeFournisseursModel;
    paramsVerify($commandeFournisseursParams, "Commande Fournisseur");

    $commandeFournisseursID = $commandeFournisseursParams['id'];
    $quantite = $commandeFournisseursData["quantite"];
    $status = $commandeFournisseursData["status"];
    $dateBut = $commandeFournisseursData["dateBut"];
    $dateFin = $commandeFournisseursData["dateFin"];
    $natureID = $commandeFournisseursData["natures_idNature"];
    $fournisseurID = $commandeFournisseursData["Fournisseurs_idFournisseur"];
    $today = getSiku();
    $testFournisseur = testFournisseurbyId($fournisseurID);
    $testNature = testNaturebyId($natureID);

    if ($testFournisseur and $testNature) {
        $commandeFournisseurs->setQuantite($quantite);
        $commandeFournisseurs->setStatus($status);
        $commandeFournisseurs->setDateDebut($dateBut);
        $commandeFournisseurs->setDateFin($dateFin);
        $commandeFournisseurs->setNatures_idNature($natureID);
        $commandeFournisseurs->setFournisseurs_idFournisseur($fournisseurID);
        $commandeFournisseurs->setUpdated_at($today);

        $commandeFournisseursFound = $commandeFournisseursModel->find($commandeFournisseursID);
        if ($commandeFournisseursID == $commandeFournisseursFound->id) {
            $commandeFournisseursModel->update($commandeFournisseursID, $commandeFournisseurs);
            # On ajoute l'Adresse  dans la BD
            $message = "Commande Fournisseur updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_FOUR);
            return success200($message);
        } else {
            $message = "No Commande Fournisseur  Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_CMD_FOUR);
            return error404($message);
        }
    }
}