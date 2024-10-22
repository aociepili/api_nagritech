<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Entree_pouletsModel;


Autoloader::register();

# Store
function storeEntreePoulets($entreePouletsData)
{
    $entreePouletsModel = new Entree_pouletsModel();
    $entreePoulets = $entreePouletsModel;

    # On recupere les informations venues de POST
    chargementEntreeAliments($entreePouletsData);
    $entreePouletsData["etat"] = ETAT_BON;
    $entreePouletsData['motifSorties_idMotif'] = MOTIF_ENTREE_CASH;
    #Entree
    $natureID = $entreePouletsData['natures_idNature'];
    natureVerify($natureID, DESIGN_POULET);
    $motifID = $entreePouletsData['motifSorties_idMotif'];
    $quantite = $entreePouletsData['quantite'];
    $fournisseurID = $entreePouletsData['fournisseur_id'];

    #Stock Poulet
    $etat = $entreePouletsData["etat"];
    $today = getSiku();
    $entreePouletsData["date"] = $today;
    $testNature = testNaturebyId($natureID);
    $testMotif = testMotifbyId($motifID);
    $testEtat = testEtatRapportbyId($etat) && isGoodProduct($etat);
    $testFour = testFournisseurbyId($fournisseurID);

    if ($testNature && $testMotif && $testEtat && $testFour) {
        # Creer Entree
        createEntree($entreePouletsData);
        $entreeData = getLastEntree($entreePouletsData);
        $entreeID = $entreeData->id;

        if (empty($entreeData)) {
            return success205("Pas d'enregistrement de l'Entree");
        } else {
            #Creer Stock Poulet
            createStockPoulet($entreePouletsData);
            $stockPouletData = getLastStockPoulet($entreePouletsData);
            $stockPouleID =  $stockPouletData->id;



            if (empty($stockPouleID)) {
                return success205("Pas d'enregistrement Stock Poulet");
            } else {
                $entreePoulets->setQuantite($quantite);
                $entreePoulets->setEntrees_idEntree($entreeID);
                $entreePoulets->setStock_Poulets_idStock($stockPouleID);
                $entreePoulets->setCreated_at($today);

                $entreePouletsModel->create($entreePoulets);
                createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ENT_POULET);
                $message = "Entree Poulet  created successfully";
                return success201($message);
            }
        }
    }
}

#Delete
function deleteEntreePoulets($entreePouletsParams)
{
    $entreePouletsModel = new Entree_pouletsModel();
    paramsVerify($entreePouletsParams, "Entree Poulet");

    $entreePouletFound = $entreePouletsParams['id'];
    $entreePouletsData = $entreePouletsModel->find($entreePouletFound);

    if ($entreePouletFound == $entreePouletsData->id) {
        $res = $entreePouletsModel->delete($entreePouletFound);
        $message = "Entree Poulet deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ENT_POULET);
        return success200($message);
    } else {
        $message = "Entree Poulet not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_ENT_POULET);
        return success205($message);
    }
}

#Get
function getEntreePouletById($entreePouletsParams)
{
    $entreePouletsModel = new Entree_pouletsModel();
    paramsVerify($entreePouletsParams, "Entree Poulet");

    $entreePouletFound = $entreePouletsModel->find($entreePouletsParams['id']);

    if (!empty($entreePouletFound)) {
        $dataEP = getEntreePouletDataById($entreePouletFound->id);
        $message = "Entree Poulet Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ENT_POULET);
        return datasuccess200($message, $dataEP);
    } else {
        $message = "No Entree Poulet Found";
        return success205($message);
    }
}

function getListEntreePoulets()
{
    $entreePouletsModel = new Entree_pouletsModel();
    $entreePoulets = (array)$entreePouletsModel->findAll();

    if (!empty($entreePoulets)) {
        $dataListEP = getListEntreePouletData($entreePoulets);
        $message = "Situation Entree Poulet";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENT_POULET);
        return dataTableSuccess200($message, $dataListEP);
    } else {
        $message = "Pas de situation dans le Entree Poulet";
        return success205($message);
    }
}

# Update
function updateEntreePoulets($entreePouletsData, $entreePouletsParams)
{
    $entreePouletsModel = new Entree_pouletsModel();
    $entreePoulets = $entreePouletsModel;
    paramsVerify($entreePouletsParams, "Entree Poulet");

    # On recupere les informations venues de POST
    $entreePoulesID = $entreePouletsParams['id'];
    $etat = $entreePouletsData["etat"];

    $quantite = $entreePouletsData['quantite'];
    $stockPouleID = $entreePouletsData['stock_Poules_idStock'];
    $today = getSiku();

    $entreePouletFound = $entreePouletsModel->find($entreePoulesID);
    if ($entreePoulesID == $entreePouletFound->id) {
        $entreeID = $entreePouletFound->entrees_idEntree;
        updateEntree($entreePouletsData, $entreeID);

        $stockPouleID = $entreePouletFound->stock_Poules_idStock;
        updateStockPoulet($entreePouletsData, $stockPouleID);
        $entreePoulets->setQuantite($quantite);
        $entreePoulets->setUpdated_at($today);
        $entreePouletsModel->update($entreePoulesID, $entreePoulets);
        # On ajoute l'Adresse  dans la BD
        $message = "Entree Poulet updated successfully";
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ENT_POULET);
        return success200($message);
    } else {
        createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_ENT_POULET);
        $message = "No Entree Poulet Found ";
        return success205($message);
    }
}