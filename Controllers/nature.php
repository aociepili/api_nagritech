<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\NaturesModel;

Autoloader::register();
# Store
function storeNature($natureData)
{
    $natureModel = new NaturesModel();
    $nature = $natureModel;

    # On recupere les informations venues de POST
    chargementNature($natureData);

    $designation = $natureData["designation"];
    $type = $natureData["type"];
    $categorie = $natureData["categorie"];
    $mode = $natureData["mode"];
    $catProduitId = $natureData["cat_produit_id"];
    $prixunitaire = (float)$natureData["prixunitaire"];
    $devise = $natureData["devise"];
    $today = getSiku();

    $nature->setDesignation($designation);
    $nature->setType($type);
    $nature->setCategorie($categorie);
    $nature->setMode($mode);
    $nature->setCat_produit_id($catProduitId);
    $nature->setPrixunitaire($prixunitaire);
    $nature->setDevise($devise);
    $nature->setCreated_at($today);

    # On ajoute la Designation dans la BD
    $natureModel->create($nature);
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_NATURE);
    $message = "Nature created successfully";
    return success201($message);
}

#Delete
function deleteNature($natureParams)
{
    $natureModel = new NaturesModel();
    paramsVerify($natureParams, "Nature");
    # On recupere les informations venues de POST
    $natureID = $natureParams['id'];
    $natureData = $natureModel->find($natureID);

    if ($natureID == $natureData->id) {
        $res = $natureModel->delete($natureID);
        $message = "Nature deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_NATURE);
        return success200($message);
    } else {
        $message = "Nature not delete ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_NATURE);
        return error405($message);
    }
}

#Get
function getNatureOnebyId($natureParams)
{
    $natureModel = new NaturesModel();
    paramsVerify($natureParams, "Nature");

    $res = $natureModel->find($natureParams['id']);

    if (!empty($res)) {
        $message = "Nature Fetched successfully";
        return datasuccess200($message, $res);
    } else {
        $message = "Nature not Found";
        return success205($message);
    }
}

function getListNatureAll()
{
    $natureModel = new NaturesModel();

    $natures = (array)$natureModel->findAll();

    if (!empty($natures)) {
        $message = "Liste des Natures";
        return dataTableSuccess200($message, $natures);
    } else {
        $message = "Pas de data de Nature dans la BD ";
        return success205($message);
    }
}

function getListNature()
{
    $natureModel = new NaturesModel();
    $data = array(
        "status" => true,
    );
    $natures = (array)$natureModel->findBy($data);

    if (!empty($natures)) {
        $message = "Liste des Natures actives";
        return dataTableSuccess200($message, $natures);
    } else {
        $message = "Pas des Natures actives";
        return success205($message);
    }
}
function getListNatureAliment()
{
    $natureModel = new NaturesModel();
    $data = array(
        "cat_produit_id" => CAT_PRO_ALIMENT
    );
    $natures = (array)$natureModel->findBy($data);

    if (!empty($natures)) {
        $message = "Liste des Natures Aliment";
        return dataTableSuccess200($message, $natures);
    } else {
        $message = "Pas des Natures Aliment";
        return success205($message);
    }
}
function getListNatureBiogaz()
{
    $natureModel = new NaturesModel();
    $data = array(
        "cat_produit_id" => CAT_PRO_BIOGAZ
    );
    $natures = (array)$natureModel->findBy($data);

    if (!empty($natures)) {
        $message = "Liste des Natures Biogaz";
        return dataTableSuccess200($message, $natures);
    } else {
        $message = "Pas des Natures Biogaz";
        return success205($message);
    }
}
function getListNatureOeuf()
{
    $natureModel = new NaturesModel();
    $data = array(
        "cat_produit_id" => CAT_PRO_OEUF
    );
    $natures = (array)$natureModel->findBy($data);

    if (!empty($natures)) {
        $message = "Liste des Natures Oeuf";
        return dataTableSuccess200($message, $natures);
    } else {
        $message = "Pas des Natures Oeuf";
        return success205($message);
    }
}
function getListNaturePoussin()
{
    $natureModel = new NaturesModel();
    $data = array(
        "cat_produit_id" => CAT_PRO_POUSSIN
    );
    $natures = (array)$natureModel->findBy($data);

    if (!empty($natures)) {
        $message = "Liste des Natures Poussin";
        return dataTableSuccess200($message, $natures);
    } else {
        $message = "Pas des Natures Poussin";
        return success205($message);
    }
}
function getListNaturePoule()
{
    $natureModel = new NaturesModel();
    $data = array(
        "cat_produit_id" => CAT_PRO_POULE
    );
    $natures = (array)$natureModel->findBy($data);

    if (!empty($natures)) {
        $message = "Liste des Natures Poule";
        return dataTableSuccess200($message, $natures);
    } else {
        $message = "Pas des Natures Poule";
        return success205($message);
    }
}
function getListNaturePoulet()
{
    $natureModel = new NaturesModel();
    $data = array(
        "cat_produit_id" => CAT_PRO_POULET
    );
    $natures = (array)$natureModel->findBy($data);

    if (!empty($natures)) {
        $message = "Liste des Natures Poulet";
        return dataTableSuccess200($message, $natures);
    } else {
        $message = "Pas des Natures Poulet";
        return success205($message);
    }
}

function archiveNature($natureParams)
{

    $natureModel = new NaturesModel();
    $nature = $natureModel;
    paramsVerify($natureParams, "Nature");

    $natureID = $natureParams['id'];
    $natureData = $natureModel->find($natureID);

    if ($natureID == $natureData->id) {
        $nature->setStatus(false);
        $nature->setUpdated_at(getSiku());
        $natureModel->update($natureID, $nature);
        $message = "nature Archive successfully";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_NATURE);
        return success200($message);
    } else {
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_NATURE);
        $message = "Nature not Archive  ";
        return error405($message);
    }
}
function activeNature($natureParams)
{

    $natureModel = new NaturesModel();
    $nature = $natureModel;
    paramsVerify($natureParams, "Nature");

    $natureID = $natureParams['id'];
    $natureData = $natureModel->find($natureID);

    if ($natureID == $natureData->id) {
        $nature->setStatus(true);
        $nature->setUpdated_at(getSiku());
        $natureModel->update($natureID, $nature);
        $message = "nature Active successfully";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_NATURE);
        return success200($message);
    } else {
        $message = "Nature not Active  ";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_NATURE);
        return error405($message);
    }
}

# Update
function updateNature($natureData, $natureParams)
{
    $natureModel = new NaturesModel();
    $nature = $natureModel;
    paramsVerify($natureParams, "Nature");

    $designation = $natureData["designation"];
    $type = $natureData["type"];
    $categorie = $natureData["categorie"];
    $mode = $natureData["mode"];
    $catProduitId = $natureData["cat_produit_id"];
    $prixunitaire = $natureData["prixunitaire"];
    $devise = $natureData["devise"];
    $today = getSiku();

    $nature->setDesignation($designation);
    $nature->setType($type);
    $nature->setCategorie($categorie);
    $nature->setMode($mode);
    $nature->setCat_produit_id($catProduitId);
    $nature->setPrixunitaire($prixunitaire);

    $nature->setDevise($devise);
    $nature->setUpdated_at($today);
    $natureID = $natureParams['id'];

    $natureFoundData = $natureModel->find($natureID);

    if (empty($natureFoundData)) {
        $message = "No Nature Found ";
        createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_NATURE);
        return success205($message);
    } else {

        if ($natureID == $natureFoundData->id) {
            $natureModel->update($natureID, $nature);
            # On ajoute l'Adresse  dans la BD
            $message = "Nature updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_NATURE);
            return success200($message);
        } else {
            $message = "Nature not update ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_NATURE);
            return success205($message);
        }
    }
}