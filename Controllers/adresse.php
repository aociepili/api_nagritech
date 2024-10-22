<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('..\Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\AdressesModel;

Autoloader::register();

# Store
function storeAdresse($adresseData)
{
    #test de chargement dans Adresse
    chargementAdresse($adresseData);

    #Creation d'une adresse
    createAdresse($adresseData);
    #Message de reussite de creation
    $message = "Address created successfully";
    return success201($message);
}

#Delete
function deleteAdresse($adresseParams)
{
    $adresseModel = new AdressesModel();

    #test chargement parametre
    paramsVerify($adresseParams, "Adresse");
    # On recupere les informations venues de POST
    $adresseID = $adresseParams['id'];
    #rechercher puis tester s'il existe 
    $adresseData = $adresseModel->find($adresseID);

    if ($adresseID == $adresseData->id) {
        try {
            $adresseModel->delete($adresseID);
            $message = "Address deleted successfully";
            return success200($message);
        } catch (\Throwable $th) {
            $message = "Vous ne pouvez pas supprimer cette Addresse";
            return  error405($message);
        }
    } else {
        $message = "Address not delete  ";
        return  error405($message);
    }
}

#Get
function getAdressebyId($adresseParams)
{
    $adresseModel = new AdressesModel();

    #test de chargement
    paramsVerify($adresseParams, "Adresse");

    $res = $adresseModel->find($adresseParams['id']);

    if (!empty($res)) {
        $message = "Address Fetched successfully";
        return datasuccess200($message, $res);
    } else {
        $message = "No Address found";
        return success205($message);
    }
}

function getListAdresse()
{
    $adresseModel = new AdressesModel();
    $adresses = $adresseModel->findAll();

    if (!empty($adresses)) {
        $message = "Liste des adresses";
        return dataTableSuccess200($message, $adresses);
    } else {
        $message = "Pas d'adresse dans la base";
        return success205($message);
    }
}

# Update
function updateAdresse($adresseData, $adresseParams)
{
    $adresseModel = new AdressesModel();
    $adresse = $adresseModel;

    #test chargement Parametre 
    chargementAdresse($adresseData);
    #test chargement ID Params
    paramsVerify($adresseParams, "Adresse");


    $pays = $adresseData["pays"];
    $ville = $adresseData["ville"];
    $commune = $adresseData["commune"];
    $quartier = $adresseData["quartier"];
    $avenue = $adresseData["avenue"];
    $adresseID = $adresseParams['id'];

    $adresse->setPays($pays);
    $adresse->setVille($ville);
    $adresse->setCommune($commune);
    $adresse->setQuartier($quartier);
    $adresse->setAvenue($avenue);

    $adresseData = $adresseModel->find($adresseID);

    if ($adresseID == $adresseData->id) {
        $adresseModel->update($adresseID, $adresse);
        $message = "Address updated successfully";
        return success200($message);
    } else {
        $message = "No Address found ";
        return success205($message);
    }
}