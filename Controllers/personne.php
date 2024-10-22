<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('..\Autoloader.php');
include('controllers.php');
// include('adresse.php');

use App\Autoloader;
use App\Models\PersonnesModel;
use App\Models\AdressesModel;

Autoloader::register();


# Store
function storePersonne($personneData)
{
    #test chargement Personne (Y compirs le data de Adresse) 
    chargementPersonne($personneData);
    createAdresse($personneData);
    #recuperation de l'ID du dernier enregistrement
    $idAdresse = getLastAdresse($personneData)->id;

    if (empty($idAdresse)) {
        $message = "Pas d'enregistrement d'adresse";
        success205($message);
    } else {
        #Personne
        createPersonne($personneData, $idAdresse);
        $message = "Personne created successfully";
        return success201($message);
    }
}


#Delete
function deletepersonne($personneParams)
{
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();


    #test Chargement parametre
    paramsVerify($personneParams, "Personne");
    # On recupere les informations venues de POST

    $personneID = $personneParams['id'];
    #Verification de l'ID de la personne
    $personneData = $personneModel->find($personneID);
    $idAdresse = $personneData->adresses_idAdresse;

    if ($personneID == $personneData->id) {
        try {
            #Suppression de l'ID personne et son Adresse
            $personneModel->delete($personneID);
            $adresseModel->delete($idAdresse);
            $message = "Person deleted successfully";
            return success200($message);
        } catch (\Throwable $th) {
            //throw $th;
            $message = "Impossible de supprime cette Personne ";
            return error405($message);
        }
    } else {
        $message = "Person not delete ";
        return error405($message);
    }
}

#Get
function getpersonnebyId($personneParams)
{
    $personneModel = new PersonnesModel();
    #Test Chargement parametre
    paramsVerify($personneParams, "Personne");

    // $res = $personneModel->find($personneParams['id']);
    $personne = getPersonneDataById($personneParams['id']);
    if (!empty($personne)) {
        $message = "Person Fetched successfully";
        return datasuccess200($message, $personne);
    } else {
        $message = "No Person Found";
        return success205($message);
    }
}

function getListpersonne()
{
    $personneModel = new PersonnesModel();
    $personnes = (array)$personneModel->findAll();

    if (!empty($personnes)) {
        $dataPersonne = getListPersonneData($personnes);
        $message = "Liste des personnes";
        return dataTableSuccess200($message, $dataPersonne);
    } else {
        $message = "Pas de Personne";
        return success205($message);
    }
}

# Update
function updatepersonne($personneData, $personneParams)
{
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();
    $adresse = $adresseModel;
    $personne = $personneModel;

    #test Chargement 
    paramsVerify($personneParams, "Personne");
    #Personne
    $nom = $personneData["nom"];
    $postnom = $personneData["postnom"];
    $prenom = $personneData["prenom"];
    $sexe = $personneData["sexe"];
    $personneID = $personneParams['id'];

    #adresse
    $pays = $personneData["pays"];
    $ville = $personneData["ville"];
    $commune = $personneData["commune"];
    $quartier = $personneData["quartier"];
    $avenue = $personneData["avenue"];


    $personne->setNom($nom);
    $personne->setPostnom($postnom);
    $personne->setPrenom($prenom);
    $personne->setSexe($sexe);
    $personData = $personneModel->find($personneID);
    $adresseID = $personData->adresses_idAdresse;

    $adresse->setPays($pays);
    $adresse->setVille($ville);
    $adresse->setCommune($commune);
    $adresse->setQuartier($quartier);
    $adresse->setAvenue($avenue);

    if ($personneID == $personData->id) {
        $adresseModel->update($adresseID, $adresse);
        $personneModel->update($personneID, $personne);

        # On modifie l'Adresse et personne  dans la BD
        $message = "person updated successfully";
        return success200($message);
    } else {
        $message = "No Person Found ";
        return success205($message);
    }
}
