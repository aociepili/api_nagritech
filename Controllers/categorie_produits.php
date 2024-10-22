<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Categorie_produitModel;

Autoloader::register();

# Store
function storeCatProduit($catProduitData)
{
    $categorieProduitModel = new Categorie_produitModel();
    $catProduit = $categorieProduitModel;

    # On recupere les informations venues de POST
    if (empty(trim($catProduitData['designation']))) {
        return error422("Veuillez completer la designation");
    } else {

        $designation = $catProduitData["designation"];
        $test = isExistCatProduitByDesignation($designation);

        if ($test) {
            $catProduit->setDesignation($designation);
            $catProduit->setCreated_at(getSiku());
            # On ajoute la Designation dans la BD
            $categorieProduitModel->create($catProduit);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CAT_PROD);
            $message = "categorie Produit created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteCatProduit($catProduitParams)
{
    $categorieProduitModel = new Categorie_produitModel();
    paramsVerify($catProduitParams, "categorie Produit");

    $catProduitID = $catProduitParams['id'];
    $catProduitData = $categorieProduitModel->find($catProduitID);

    if ($catProduitID == $catProduitData->id) {
        $res = $categorieProduitModel->delete($catProduitID);
        $message = "categorie Produit deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CAT_PROD);
        return success200($message);
    } else {
        $message = "categorie Produit not delete  ";
        return error405($message);
    }
}

#Get
function getCatProduitById($catProduitParams)
{
    $categorieProduitModel = new Categorie_produitModel();
    paramsVerify($catProduitParams, "categorie Produit");
    $catProduitFound = $categorieProduitModel->find($catProduitParams['id']);

    if (!empty($catProduitFound)) {
        $message = "categorie Produit Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CAT_PROD);
        return datasuccess200($message, $catProduitFound);
    } else {
        $message = "No status categorie Produit Found";
        return success205($message);
    }
}

function getListCatProduitAll()
{
    $categorieProduitModel = new Categorie_produitModel();
    $catProduit = (array)$categorieProduitModel->findAll();

    if (!empty($catProduit)) {
        $message = "Liste des categorie Produit (All)";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CAT_PROD);
        return dataTableSuccess200($message, $catProduit);
    } else {
        $message = "Pas de categorie Produit";
        return success205($message);
    }
}
function getListCatProduit()
{
    $categorieProduitModel = new Categorie_produitModel();
    $data = array(
        "status" => true,
    );
    $catProduit = (array)$categorieProduitModel->findBy($data);
    if (!empty($catProduit)) {
        $message = "Liste des categorie Produit (Actif)";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CAT_PROD);
        return dataTableSuccess200($message, $catProduit);
    } else {
        $message = "Pas de categorie Produit";
        return success205($message);
    }
}

# Update
function updateCatProduit($catProduitData, $catProduitParams)
{
    $categorieProduitModel = new Categorie_produitModel();
    $catProduit = $categorieProduitModel;
    paramsVerify($catProduitParams, "categorie Produit");

    # On recupere les informations venues de POST
    if (empty(trim($catProduitData["designation"]))) {
        return error422("Entree votre Designation");
    } else {
        $designation = $catProduitData["designation"];
        $catProduitID = $catProduitParams['id'];

        $catProduitFound = $categorieProduitModel->find($catProduitID);

        $test = isExistCatProduitByDesignationUpdate($designation, $catProduitID);
        $catProduit->setDesignation($designation);
        $catProduit->setUpdated_at(getSiku());
        if ($test) {

            if ($catProduitID == $catProduitFound->id) {
                $categorieProduitModel->update($catProduitID, $catProduit);
                # On ajoute l'Adresse  dans la BD
                $message = "categorie Produit updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CAT_PROD);
                return success200($message);
            } else {
                $message = "No categorie Produit Rapport Found ";
                return success205($message);
            }
        }
    }
}

function archiveCatProduit($catProdParams)
{
    $categorieProduitModel = new Categorie_produitModel();
    $catProduit = $categorieProduitModel;
    paramsVerify($catProdParams, "Type Operation");

    $catProduitID = $catProdParams['id'];
    $catProduitData = $categorieProduitModel->find($catProduitID);

    if ($catProduitID == $catProduitData->id) {
        $catProduit->setStatus(false);
        $catProduit->setUpdated_at(getSiku());
        $categorieProduitModel->update($catProduitID, $catProduit);
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CAT_PROD);
        $message = "Type Operation Archive successfully";
        return success200($message);
    } else {
        $message = "Type Operation not Archive  ";
        return success205($message);
    }
}
function activeCatProduit($catProdParams)
{
    $categorieProduitModel = new Categorie_produitModel();
    $catProduit = $categorieProduitModel;
    paramsVerify($catProdParams, "Type Operation");

    $catProduitID = $catProdParams['id'];
    $catProduitData = $categorieProduitModel->find($catProduitID);

    if ($catProduitID == $catProduitData->id) {
        $catProduit->setStatus(true);
        $catProduit->setUpdated_at(getSiku());
        $categorieProduitModel->update($catProduitID, $catProduit);
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CAT_PROD);
        $message = "Type Operation Archive successfully";
        return success200($message);
    } else {
        $message = "Type Operation not Archive  ";
        return success205($message);
    }
}

function isExistCatProduitByDesignation($designation)
{
    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $categorieProduitModel = new Categorie_produitModel();
    $statusData = (object)$categorieProduitModel->findBy($data);

    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette Designation existe deja");
    }
}
function isExistCatProduitByDesignationUpdate($designation, $id)
{

    $test = false;
    $data = array(
        "designation" => $designation,
    );

    $categorieProduitModel = new Categorie_produitModel();
    $statusData = $categorieProduitModel->findBy($data);
    if (empty((array)$statusData)) {
        # Status n'existe pas
        $test = true;
        return $test;
    } else {
        if ($id == $statusData[0]->id) {
            $test = true;
            return $test;
        } else {
            success203(" Cette Designation existe deja");
        }
    }
}