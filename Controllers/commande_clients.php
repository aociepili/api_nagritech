<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Commande_clientsModel;

Autoloader::register();

# Store
function storeCommandeClient($commandeClientsData)
{

    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    chargementCommandeClient($commandeClientsData);

    $status = STATUS_CMD_DEFAUT;
    $date = $commandeClientsData["date"];
    $natureID = $commandeClientsData["natures_idNature"];
    $clientID = $commandeClientsData["clients_idClient"];
    $today = getSiku();

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);

    if ($testClient and $testNature) {
        $commandeClients->setStatusCmd_id($status);
        $commandeClients->setDate($date);
        $commandeClients->setNatures_idNature($natureID);
        $commandeClients->setClients_idClient($clientID);
        $commandeClients->setCreated_at($today);

        $commandeClientsModel->create($commandeClients);
        createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CMD_CLIENT);
        $message = "Commande Client  created successfully";
        return success201($message);
    }
}

#Delete
function deleteCommandeClient($commandeClientsParams)
{
    $commandeClientsModel = new Commande_clientsModel();
    paramsVerify($commandeClientsParams, "Commande Client");

    $commandeClientsID = $commandeClientsParams['id'];
    $commandeClientsData = $commandeClientsModel->find($commandeClientsID);

    if ($commandeClientsID == $commandeClientsData->id) {
        $res = $commandeClientsModel->delete($commandeClientsID);
        $message = "Commande Client deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CMD_CLIENT);
        return success200($message);
    } else {
        $message = "Commande Client not delete  ";
        return error405($message);
    }
}

#Get
function getCommandeClientById($commandeClientsParams)
{
    $commandeClientsModel = new Commande_clientsModel();
    paramsVerify($commandeClientsParams, "Commande Client");
    $commandeClientsFound = $commandeClientsModel->find($commandeClientsParams['id']);

    if (!empty($commandeClientsFound)) {
        $dataCC = getCommandeClientDataById($commandeClientsFound->id);
        $message = "Commande Client Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CMD_CLIENT);
        return datasuccess200($message, $dataCC);
    } else {
        $message = "No commande Clients Found";
        return error404($message);
    }
}
function getCommandeClientByIdClient($idClient)
{
    $commandeClientsModel = new Commande_clientsModel();

    $testClient = testClientbyId($idClient);
    $dataCmdClient = array(
        "clients_idClient" => $idClient,
    );

    if ($testClient) {
        $commandeClientsFound = (array)$commandeClientsModel->findBy($dataCmdClient);

        if (!empty($commandeClientsFound)) {
            $dataListCC = getListCommandeClientData($commandeClientsFound);
            $message = "Liste des Commandes du Client :" . $idClient;
            createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CMD_CLIENT);
            return dataTableSuccess200($message, $dataListCC);
        } else {
            $message = "ce client n'a pas encore effectuee une Commande";
            return error404($message);
        }
    } else {
        $message = "Veuillez renseignée le Client ";
        return success205($message);
    }
}



function getListCommandeClient()
{
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = (array)$commandeClientsModel->findAll();

    if (!empty($commandeClients)) {
        $dataListCC = getListCommandeClientData($commandeClients);
        $message = "Liste des Commandes Client";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CMD_CLIENT);
        return dataTableSuccess200($message, $dataListCC);
    } else {
        $message = "Pas de Commandes Client";
        return error404($message);
    }
}

# Update
function updateCommandeClient($commandeClientsData, $commandeClientsParams)
{
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandeClientsParams, "Commande Client");

    $commandeClientsID = $commandeClientsParams['id'];
    $status = $commandeClientsData["statusCmd_id"];
    $date = $commandeClientsData["date"];
    $natureID = $commandeClientsData["natures_idNature"];
    $clientID = $commandeClientsData["clients_idClient"];
    $today = getSiku();

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);

    if ($testClient and $testNature) {
        $commandeClients->setStatusCmd_id($status);
        $commandeClients->setDate($date);
        $commandeClients->setNatures_idNature($natureID);
        $commandeClients->setClients_idClient($clientID);
        $commandeClients->setUpdated_at($today);

        $commandeClientsFound = $commandeClientsModel->find($commandeClientsID);
        if ($commandeClientsID == $commandeClientsFound->id) {
            $commandeClientsModel->update($commandeClientsID, $commandeClients);
            # On ajoute l'Adresse  dans la BD
            $message = "Commande Client updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_CLIENT);
            return success200($message);
        } else {
            $message = "No Commande Client  Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_CMD_CLIENT);
            return error404($message);
        }
    }
}

function chargerPaiement($imgData, $commandeClientsParams)
{
    $cmdeClientModel = new Commande_clientsModel();
    $cmdClient = $cmdeClientModel;

    paramsVerify($commandeClientsParams, "Commande Client");
    $commandeClientID = $commandeClientsParams["id"];
    $cmdeClientFound = $cmdeClientModel->find($commandeClientID);


    if (empty($cmdeClientFound)) {
        $message = "No commande Client Found or Internal Server Error";
        createActivity(TYPE_OP_UP_PAIEMENT, STATUS_OP_NOT, TABLE_CMD_CLIENT);
        return success205($message);
    } else {
        #Image
        $nbre = generateNumber();

        #Nouvelle denomination du fichier
        $title = "Paiement_cmde_" . $commandeClientID . "_" . $nbre;

        # Traitement de donnees du fichier
        $titreImg = $imgData['name'];
        $type = $imgData['type'];

        $directory = $imgData['tmp_name'];
        $extension = strrchr($titreImg, ".");
        $directorysend = "../public/img/paiment_cmd/" . $title . "" . $extension;
        #Extension prise en charge
        $valideExtension = array('.jpg', '.png', '.jpeg');

        $test = in_array($extension, $valideExtension);

        if ($test) {

            if (move_uploaded_file($directory, $directorysend)) {
                # Nouveau chemin d'acces a l image de profil
                $pathLogo = "../public/img/paiment_cmd/" . $title . "" . $extension;
                $cmdClient->setStatusCmd_id(STATUS_CMD_E_PAIEMENT);
                $cmdClient->setPaiement_img($pathLogo);
                $cmdClient->setUpdated_at(getSiku());
                $cmdeClientModel->update($commandeClientID, $cmdClient);

                $message = "La preuve de paiement a été chargée  avec succès";
                createActivity(TYPE_OP_UP_PAIEMENT, STATUS_OP_OK, TABLE_CMD_CLIENT);
                return success200($message);
            } else {

                $message = "Un Problème de télechargement du fichier";
                createActivity(TYPE_OP_UP_PAIEMENT, STATUS_OP_NOT, TABLE_CMD_CLIENT);
                error415($message);
            }
        } else {
            $message = "Le fichier charge n'est pas prise en charge";
            createActivity(TYPE_OP_UP_PAIEMENT, STATUS_OP_NOT, TABLE_CMD_CLIENT);
            success205($message);
        }
    }
}

function livrerCommande($commandeClientParams)
{
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandeClientParams, "Commande Client");
    $commandeClientID = $commandeClientParams['id'];

    $today = getSiku();
    $commandeClients->setIs_delivered(true);
    $commandeClients->setUpdated_at($today);

    $commandeClientsFound = $commandeClientsModel->find($commandeClientID);
    //debug400('test',  $commandeClientsFound);
    if ($commandeClientID == $commandeClientsFound->id) {
        if ($commandeClientsFound->is_delivered == true) {
            $message = "Ce lot a déjà été livré au client";
            return success205($message);
        } else {
            if (in_array($commandeClientsFound->statusCmd_id, STATUS_CMD_NON_LIVRABLE)) {
                $message = "Veuiller REGLE d'abord la commande";
                return  success205($message);
            } elseif ($commandeClientsFound->statusCmd_id == STATUS_CMD_REGLE) {
                $commandeClientsModel->update($commandeClientID, $commandeClients);
                $message = "Ce lot a été livré au client ";
                return success205($message);
            }
        }
    } else {
        $message = "Commande Introuvable : Veuillez bien  renseignée la commande du client ";
        return success205($message);
    }
}


function chargerPaiementOther($imgData, $commandeClientsParams)
{
    $cmdeClientModel = new Commande_clientsModel();
    $cmdClient = $cmdeClientModel;

    paramsVerify($commandeClientsParams, "Commande Client");
    $commandeClientID = $commandeClientsParams["id"];
    $cmdeClientFound = $cmdeClientModel->find($commandeClientID);


    if (empty($cmdeClientFound)) {
        $message = "No commande Client Found or Internal Server Error";
        createActivity(TYPE_OP_UP_PAIEMENT, STATUS_OP_NOT, TABLE_CMD_CLIENT);
        return success205($message);
    } else {
        #Image
        $nbre = generateNumber();

        #Nouvelle denomination du fichier
        $title = "Paiement_cmde_" . $commandeClientID . "_" . $nbre;
        $directorysend = "../public/img/paiment_cmd/" . $title . ".png";
        $imageText = $imgData['img_text'];
        $imageDecode = base64_decode($imageText);
        if (file_put_contents($directorysend, $imageDecode)) {
            # Nouveau chemin d'acces a l image de profil
            $pathLogo = "../public/img/paiment_cmd/" . $title . ".png";
            $cmdClient->setStatusCmd_id(STATUS_CMD_E_PAIEMENT);
            $cmdClient->setPaiement_img($pathLogo);
            $cmdClient->setUpdated_at(getSiku());
            $cmdeClientModel->update($commandeClientID, $cmdClient);

            $message = "La preuve de paiement a été chargée  avec succès";
            createActivity(TYPE_OP_UP_PAIEMENT, STATUS_OP_OK, TABLE_CMD_CLIENT);
            return success200($message);
        } else {

            $message = "Un Problème de télechargement du fichier";
            createActivity(TYPE_OP_UP_PAIEMENT, STATUS_OP_NOT, TABLE_CMD_CLIENT);
            error415($message);
        }
    }
}