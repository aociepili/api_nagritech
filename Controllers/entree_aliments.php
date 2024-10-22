<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Entree_alimentsModel;


Autoloader::register();

# Store
function storeEntreeAliments($entreeAlimentsData)
{
    $entreeAlimentsModel = new Entree_alimentsModel();
    $entreeAliments = $entreeAlimentsModel;

    # On recupere les informations venues de POST
    chargementEntreeAliments($entreeAlimentsData);
    $entreeAlimentsData["etat"] = ETAT_BON;
    $entreeAlimentsData['motifSorties_idMotif'] = MOTIF_ENTREE_CASH;
    #Entree
    $natureID = $entreeAlimentsData['natures_idNature'];
    natureVerify($natureID, DESIGN_ALIMENT);
    $motifID = $entreeAlimentsData['motifSorties_idMotif'];
    $quantite = $entreeAlimentsData['quantite'];
    $fournisseurID = $entreeAlimentsData['fournisseur_id'];
    #Stock Aliment
    $etat = $entreeAlimentsData["etat"];
    $today = getSiku();
    $entreeAlimentsData["date"] = $today;
    $testNature = testNaturebyId($natureID);
    $testMotif = testMotifbyId($motifID);
    $testEtat = testEtatRapportbyId($etat) && isGoodProduct($etat);
    $testFour = testFournisseurbyId($fournisseurID);

    if ($testNature && $testMotif && $testEtat && $testFour) {
        # Creer Entree
        createEntree($entreeAlimentsData);
        $entreeData = getLastEntree($entreeAlimentsData);
        $entreeID = $entreeData->id;

        if (empty($entreeData)) {
            return success205("Pas d'enregistrement de l'Entree");
        } else {
            #Creer Stock Aliment
            createStockAliment($entreeAlimentsData);
            $stockAlimentData = getLastStockAliment($entreeAlimentsData);
            $stockAlimentID =  $stockAlimentData->id;



            if (empty($stockAlimentID)) {
                return success205("Pas d'enregistrement Stock Aliment");
            } else {
                $entreeAliments->setQuantite($quantite);
                $entreeAliments->setEntrees_idEntree($entreeID);
                $entreeAliments->setStock_Aliments_idStock($stockAlimentID);
                $entreeAliments->setCreated_at($today);

                $entreeAlimentsModel->create($entreeAliments);
                createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ENT_ALIMENT);
                $message = "Entree Aliment  created successfully";
                return success201($message);
            }
        }
    }
}

#Delete
function deleteEntreeAliments($entreeAlimentsParams)
{
    $entreeAlimentsModel = new Entree_alimentsModel();
    paramsVerify($entreeAlimentsParams, "Entree Aliment");

    $entreeAlimentsFound = $entreeAlimentsParams['id'];
    $entreeAlimentsData = $entreeAlimentsModel->find($entreeAlimentsFound);

    if ($entreeAlimentsFound == $entreeAlimentsData->id) {
        $res = $entreeAlimentsModel->delete($entreeAlimentsFound);
        $message = "Entree Aliment deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ENT_ALIMENT);
        return success200($message);
    } else {
        $message = "Entree Aliment not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_ENT_ALIMENT);
        return success205($message);
    }
}

#Get
function getEntreeAlimentById($entreeAlimentsParams)
{
    $entreeAlimentsModel = new Entree_alimentsModel();
    paramsVerify($entreeAlimentsParams, "Entree Aliment");

    $entreeAlimentsFound = $entreeAlimentsModel->find($entreeAlimentsParams['id']);

    if (!empty($entreeAlimentsFound)) {
        $dataEA = getEntreeAlimentDataById($entreeAlimentsFound->id);
        $message = "Entree Aliment Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ENT_ALIMENT);
        return datasuccess200($message, $dataEA);
    } else {
        $message = "No Entree Aliemnt Found";
        return success205($message);
    }
}

function getListEntreeAliments()
{
    $entreeAlimentsModel = new Entree_alimentsModel();
    $entreeAliments = (array)$entreeAlimentsModel->findAll();

    if (!empty($entreeAliments)) {
        $dataListEA = getListEntreeAlimentData($entreeAliments);
        $message = "Situation Entree Aliment";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ENT_ALIMENT);
        return dataTableSuccess200($message, $dataListEA);
    } else {
        $message = "Pas de situation dans le Entree Aliment";
        return success205($message);
    }
}

# Update
function updateEntreeAliments($entreeAlimentsData, $entreeAlimentsParams)
{
    $entreeAlimentsModel = new Entree_alimentsModel();
    $entreeAliments = $entreeAlimentsModel;
    paramsVerify($entreeAlimentsParams, "Entree Aliment");

    # On recupere les informations venues de POST
    $entreeAlimentsID = $entreeAlimentsParams['id'];
    $etat = $entreeAlimentsData["etat"];

    $quantite = $entreeAlimentsData['quantite'];
    $stockAlimentID = $entreeAlimentsData['stock_Aliments_idStock'];
    $today = getSiku();

    $entreeAlimentsFound = $entreeAlimentsModel->find($entreeAlimentsID);
    if ($entreeAlimentsID == $entreeAlimentsFound->id) {
        $entreeID = $entreeAlimentsFound->entrees_idEntree;
        updateEntree($entreeAlimentsData, $entreeID);
        $stockAlimentID = $entreeAlimentsFound->stock_Aliments_idStock;
        updateStockAliment($entreeAlimentsData, $stockAlimentID);
        $entreeAliments->setQuantite($quantite);
        $entreeAliments->setUpdated_at($today);
        $entreeAlimentsModel->update($entreeAlimentsID, $entreeAliments);
        # On ajoute l'Adresse  dans la BD
        $message = "Entree Aliment updated successfully";
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ENT_ALIMENT);
        return success200($message);
    } else {
        createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_ENT_ALIMENT);
        $message = "No Entree Aliment Found ";
        return success205($message);
    }
}
