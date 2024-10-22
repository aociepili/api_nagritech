<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Entree_oeufsModel;

Autoloader::register();

# Store
function storeEntreeOeufs($entreeOeufsData)
{
    $entreeOeufsModel = new Entree_oeufsModel();
    $entreeOeufs = $entreeOeufsModel;

    # On recupere les informations venues de POST
    chargementEntreeOeufs($entreeOeufsData);
    $entreeOeufsData["etat"] = ETAT_BON;
    $entreeOeufsData['motifSorties_idMotif'] = MOTIF_ENTREE_CASH;

    $quantite = $entreeOeufsData['quantite'];
    $today = getSiku();
    $entreeOeufsData["date"] = $today;

    $natureID = $entreeOeufsData['natures_idNature'];
    natureVerify($natureID, DESIGN_OEUF);
    $motifID = $entreeOeufsData['motifSorties_idMotif'];
    $date = $entreeOeufsData["date"];
    $etat = $entreeOeufsData["etat"];
    $fournisseurID = $entreeOeufsData["fournisseur_id"];


    $testNature = testNaturebyId($natureID);
    $testMotif = testMotifbyId($motifID);
    $testEtat = testEtatRapportbyId($etat) && isGoodProduct($etat);
    $testFour = testFournisseurbyId($fournisseurID);


    if ($testNature && $testMotif && $testEtat && $testFour) {
        #creer Entree
        createEntree($entreeOeufsData);
        $entreeData = getLastEntree($entreeOeufsData);
        $entreeID = $entreeData->id;
        if (empty($entreeData)) {
            return success205("Pas d'enregistrement de l'Entree");
        } else {
            createStockOeuf($entreeOeufsData);
            $dataStockoeuf = getLastStockOeuf($entreeOeufsData);
            $stockOeufID = $dataStockoeuf->id;
            if (empty($dataStockoeuf)) {
                return success205("Pas d'enregistrement du Stock Oeuf");
            } else {
                $entreeOeufs->setQuantite($quantite);
                $entreeOeufs->setEntrees_idEntree($entreeID);
                $entreeOeufs->setStock_Oeufs_idStock($stockOeufID);
                $entreeOeufs->setCreated_at($today);

                # On ajoute la Designation dans la BD
                $entreeOeufsModel->create($entreeOeufs);
                createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ENT_OEUF);
                $message = "Entree oeufs  created successfully";
                return success201($message);
            }
        }
    }
}

#Delete
function deleteEntreeOeuf($entreeOeufsParams)
{
    $entreeOeufsModel = new Entree_oeufsModel();
    paramsVerify($entreeOeufsParams, "Entree Oeuf");

    $entreeOeufID = $entreeOeufsParams['id'];
    $entreeOeufsData = $entreeOeufsModel->find($entreeOeufID);

    if ($entreeOeufID == $entreeOeufsData->id) {
        $entreeOeufsModel->delete($entreeOeufID);
        $message = "Entree Oeuf deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ENT_OEUF);
        return success200($message);
    } else {
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_ENT_OEUF);
        $message = "Entree Oeuf not delete  ";
        return error405($message);
    }
}

#Get
function getEntreeOeufById($entreeOeufsParams)
{
    $entreeOeufsModel = new Entree_oeufsModel();
    paramsVerify($entreeOeufsParams, "Entree Oeuf");
    $entreeOeufFound = $entreeOeufsModel->find($entreeOeufsParams['id']);

    if (!empty($entreeOeufFound)) {
        $dataEO = getEntreeOeufDataById($entreeOeufFound->id);
        $message = "Entree Oeuf Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ENT_OEUF);
        return datasuccess200($message, $dataEO);
    } else {
        $message = "No Entree Oeuf Found";

        return success205($message);
    }
}

function getListEntreeOeuf()
{
    $entreeOeufsModel = new Entree_oeufsModel();
    $entreeOeufs = (array)$entreeOeufsModel->findAll();

    if (!empty($entreeOeufs)) {
        $dataListEO = getListEntreeOeufData($entreeOeufs);

        $message = "Situation Entree Oeuf";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENT_OEUF);
        return dataTableSuccess200($message, $dataListEO);
    } else {
        $message = "Pas de situation dans le Entree Oeuf";
        return success205($message);
    }
}

# Update
function updateEntreeOeuf($entreeOeufsData, $entreeOeufsParams)
{
    $entreeOeufsModel = new Entree_oeufsModel();
    $entreeOeufs = $entreeOeufsModel;
    paramsVerify($entreeOeufsParams, "Entree Oeuf");

    # On recupere les informations venues de POST
    $entreeOeufID = $entreeOeufsParams['id'];

    $quantite = $entreeOeufsData['quantite'];
    $entreeID = $entreeOeufsData["entrees_idEntree"];
    $stockOeufID = $entreeOeufsData['stock_Oeufs_idStock'];
    $today = getSiku();

    $entreeOeufFound = $entreeOeufsModel->find($entreeOeufID);
    if ($entreeOeufID == $entreeOeufFound->id) {
        $entreeID = $entreeOeufFound->entrees_idEntree;
        updateEntree($entreeOeufsData, $entreeID);

        $stockOeufID = $entreeOeufFound->stock_Oeufs_idStock;

        updateStockOeuf($entreeOeufsData, $stockOeufID);

        $entreeOeufs->setQuantite($quantite);
        $entreeOeufs->setUpdated_at($today);
        $entreeOeufsModel->update($entreeOeufID, $entreeOeufs);
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ENT_OEUF);
        # On ajoute l'Adresse  dans la BD
        $message = "Entree Oeuf updated successfully";
        return success200($message);
    } else {
        $message = "No Entree Oeuf Found ";
        return success205($message);
    }
}
