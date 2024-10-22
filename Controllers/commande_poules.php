<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');


use App\Autoloader;
use App\Models\Commande_poulesModel;
use App\Models\Commande_clientsModel;

Autoloader::register();

# Store
function storeCommandePoules($commandePoulesData)
{
    $commandePoulesModel = new Commande_poulesModel();
    $commandePoules = $commandePoulesModel;
    chargementCommande($commandePoulesData);
    $montant = $commandePoulesData["montant"];
    chiffreVerify($montant, "montant");


    if ($commandePoulesData["montant"] == null) {
        $commandePoulesData["montant"] = 0;
    }

    $commandePoulesData["statusCmd_id"] = createStatutCommande($montant);

    $natureID = $commandePoulesData["natures_idNature"];
    $clientID = $commandePoulesData["clients_idClient"];
    $quantite = $commandePoulesData["quantite"];
    $montant = $commandePoulesData["montant"];

    natureVerify($natureID, DESIGN_POULE);
    $today = getSiku();
    $commandePoulesData["date"] =  $today;

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);
    $testStatusCmd = testStatusCmdbyId($commandePoulesData["statusCmd_id"]);

    if ($testClient && $testNature && $testStatusCmd) {

        # Creer la Commande Client
        createCommandeClient($commandePoulesData);
        $cmdClientID = getLastCommandeClient($commandePoulesData)->id;

        if (empty($cmdClientID)) {
            return success205("Pas d'enregistrement Commande Client");
        } else {
            $prixtotal = getPrixTotal($cmdClientID, $quantite);
            $commandePoules->setQuantite($quantite);
            $commandePoules->setCommandeClients_idCommande($cmdClientID);
            $commandePoules->setMontant($montant);
            $commandePoules->setPrixtotal($prixtotal);
            $commandePoules->setCreated_at($today);

            $commandePoulesModel->create($commandePoules);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CMD_POULE);
            $message = "Commande Poule  created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteCommandePoules($commandePoulesParams)
{
    $commandePoulesModel = new Commande_poulesModel();
    paramsVerify($commandePoulesParams, "Commande Poule");

    $commandePoulesID = $commandePoulesParams['id'];
    $commandePoulesData = $commandePoulesModel->find($commandePoulesID);

    if ($commandePoulesID == $commandePoulesData->id) {

        try {
            $commandePoulesModel->delete($commandePoulesID);
            $test = deleteCmdClientData($commandePoulesData->commandeClients_idCommande);
            if ($test) {
                $message = "Commande Poule deleted successfully";
                createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CMD_POULE);
                return success200($message);
            }
        } catch (\Throwable $th) {
            $message = "Erreur Systeme :" . $th;
            createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_CMD_POULE);
            return error405($message);
        }
    } else {
        $message = "Commande Poule not Delete  ";
        return error405($message);
    }
}

#Get
function getCommandePouleById($commandePoulesParams)
{
    $commandePoulesModel = new Commande_poulesModel();
    paramsVerify($commandePoulesParams, "Commande Poule");
    $commandePoulesFound = $commandePoulesModel->find($commandePoulesParams['id']);

    if (!empty($commandePoulesFound)) {
        $dataCP = getCommandePouleDataById($commandePoulesFound->id);
        $message = "Commande Poule Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CMD_POULE);
        return datasuccess200($message, $dataCP);
    } else {
        $message = "No commande Poule Found";
        return success205($message);
    }
}

function getListCommandePoule()
{
    $commandePoulesModel = new Commande_poulesModel();
    $commandePoules = (array)$commandePoulesModel->findAll();

    if (!empty($commandePoules)) {
        $dataListCP = getListCommandePouleData($commandePoules);
        $message = "Liste des Commandes Poules";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CMD_POULE);
        return dataTableSuccess200($message, $dataListCP);
    } else {
        $message = "Pas de Commandes Poules";
        return success205($message);
    }
}

# Update
function updateCommandePoule($commandePoulesData, $commandePoulesParams)
{
    $commandePoulesModel = new Commande_poulesModel();
    $commandePoules = $commandePoulesModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandePoulesParams, "Commande Poule");


    $date = $commandePoulesData["date"];
    $natureID = $commandePoulesData["natures_idNature"];
    $clientID = $commandePoulesData["clients_idClient"];
    $commandePoulesID = $commandePoulesParams['id'];
    $quantite = $commandePoulesData["quantite"];

    $montant = $commandePoulesData["montant"];
    $today = getSiku();
    $commandePoulesData["siku"] = $today;

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);


    if ($testNature && $testClient) {
        $dataCmdPoule = $commandePoulesModel->find($commandePoulesID);
        $cmdClientID = $dataCmdPoule->commandeClients_idCommande;

        $commandeClients->setDate($date);
        $commandeClients->setNatures_idNature($natureID);
        $commandeClients->setClients_idClient($clientID);
        $commandeClients->setUpdated_at($today);

        $prixtotal = getPrixTotal($cmdClientID, $quantite);
        $commandePoules->setQuantite($quantite);
        $commandePoules->setCommandeClients_idCommande($cmdClientID);
        $commandePoules->setMontant($montant);
        $commandePoules->setPrixtotal($prixtotal);
        $commandePoules->setUpdated_at($today);

        $commandePoulesFound = $commandePoulesModel->find($commandePoulesID);
        if ($commandePoulesID == $commandePoulesFound->id) {
            if (in_array($commandePoulesFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
                $message = "Le status de cette Commande Poule ne peut etre modifie ";
                return  success205($message);
            } else {
                $commandePoulesModel->update($commandePoulesID, $commandePoules);

                if (modCmdClient($commandePoulesData) or isset($today)) {
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                }
                $message = "Commande Poule updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POULE);
                return success200($message);
            }
        } else {
            $message = "Commande Poule not update";
            return success205($message);
        }
    }
}

function changeStatutCmdPoule($commandePoulesData, $commandePoulesParams)
{
    require_once 'php-jwt/authentification.php';
    $commandePoulesModel = new Commande_poulesModel();
    $commandePoules = $commandePoulesModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;

    # test de chargement de parametre
    paramsVerify($commandePoulesParams, "Commande Poule");

    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];

    if ($role == IS_ADMIN) {
        $commandePoulesData["admins_id"] = $auteurID;
        $commandePoulesData["admins_idAdmin"] = $auteurID;
        $commandePoulesData["agents_id"] = ID_AGENT_SYSTEME;
        $commandePoulesData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $commandePoulesData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $commandePoulesData["agents_id"] = $auteurID;
        $commandePoulesData["agents_idAgent"] = $auteurID;
        $commandePoulesData["admins_id"] = ID_ADMIN_SYSTEME;
        $commandePoulesData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $commandePoulesData["role_id"] = IS_AGENT_ID;
    }

    $newStatusCmdID = $commandePoulesData["statusCmd_id"];
    $commandePoulesID = $commandePoulesParams['id'];
    statusCmdVerify($newStatusCmdID);

    $testStatusCmd = testStatusCmdbyId($newStatusCmdID);

    if ($testStatusCmd) {
        $dataCmdPouleFound = $commandePoulesModel->find($commandePoulesID);
        if ($dataCmdPouleFound->id == $commandePoulesID) {
            $cmdClientID = $dataCmdPouleFound->commandeClients_idCommande;
            $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);
            if ($dataCmdClientFound->id == $cmdClientID) {
                $natureID = $dataCmdClientFound->natures_idNature;
                $clientID = $dataCmdClientFound->clients_idClient;
                $statusCmdID = $dataCmdClientFound->statusCmd_id;
                $sortieID = $dataCmdClientFound->id_sortie;

                $quantite = $dataCmdPouleFound->quantite;
                $montant = $dataCmdPouleFound->montant;
                $prixtotal = $dataCmdPouleFound->prixtotal;

                $commandePoulesData["commandeClients_idCommande"] = $cmdClientID;
                $today = getSiku();
                $commandePoulesData["date"] = $today;
                $commandePoulesData["siku"] = $today;
                $commandePoulesData["etat_rapportID"] = ETAT_BON;
                $commandePoulesData["natures_idNature"] = $natureID;
                $commandePoulesData["quantite"] = $quantite;
                $commandePoulesData["montant"] = $montant;
                $commandePoulesData["prixtotal"] = $prixtotal;
                $commandePoulesData["clients_idClient"] = $clientID;
                $commandePoulesData["statusCmd_id"] = $statusCmdID;


                natureVerify($natureID, DESIGN_POULE);
                statusCmdVerify($statusCmdID);
                chiffreVerify($montant, "Montant");
                chiffreVerify($prixtotal, "Prix Total");

                if ($statusCmdID == STATUS_CMD_ANNULE) {
                    #Commande Deja annule Pas d'operation 
                    $message = "Cette Commande Poule du client a déjà été annulé";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_E_DETTE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Poule du client a déjà été reglé à credit";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_REGLE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Poule du client a déjà été reglé";
                    return success200($message);
                } else {
                    #Status Modifiable facilement i.e Pas d'operation
                    $statutSimpleMod = array(STATUS_CMD_ETABLI, STATUS_CMD_RESERVE, STATUS_CMD_E_PAIEMENT);
                    #Status Difficilement a Modifier i.e Beaucoup d'operation
                    $statusDifficileMod = array(STATUS_CMD_REGLE, STATUS_CMD_E_DETTE);

                    if (in_array($newStatusCmdID, $statutSimpleMod)) {
                        #Pas beaucoup d'operation juste le changement du status
                        $commandeClients->setStatusCmd_id($newStatusCmdID);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POULE);
                        $message = "Le Statut de Commande Poule du client a été modifié";
                        return success200($message);
                    } elseif (in_array($newStatusCmdID, $statusDifficileMod)) {
                        if (($montant < $prixtotal) && ($newStatusCmdID == STATUS_CMD_E_DETTE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandePoulesData['motifSorties_idMotif'] = MOTIF_SORTIE_CREDIT;
                            sortiePoule($commandePoulesData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandePoulesData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POULE);
                            $message = "Le Statut de Commande Poule du client a été reglé avec une dette";
                            return success200($message);
                        }
                        if (($prixtotal == $montant) && ($newStatusCmdID == STATUS_CMD_REGLE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandePoulesData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
                            sortiePoule($commandePoulesData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandePoulesData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POULE);
                            $message = "Le Statut de Commande Poule du client a été reglé";
                            return success200($message);
                        } else {
                            $message = "Veuillez payer d'abord votre Commande ";
                            return success205($message);
                        }
                    } elseif ($newStatusCmdID == STATUS_CMD_ANNULE) {
                        $commandeClients->setStatusCmd_id($newStatusCmdID);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POULE);
                        $message = "Le Statut de Commande Poule du client a été annulée";
                        return success200($message);
                    } else {
                        $message = "Veuillez verifier le Status de la Commande Poule";
                        return success205($message);
                    }
                }
            } else {
                $message = "Cette Commande  Poule n'est repertorié comme une commande client ";
                return success205($message);
            }
        } else {
            $message = "Commande  Poule not Found ";
            return success205($message);
        }
    }
}

function updateMontantPoule($commandePoulesData, $commandePoulesParams)
{
    $commandePoulesModel = new Commande_poulesModel();
    $commandePoules = $commandePoulesModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;

    paramsVerify($commandePoulesParams, "Commande Poule");

    $newMontant = $commandePoulesData["montant"];
    chiffreVerify($newMontant, "montant");
    $commandePoulesID = $commandePoulesParams['id'];
    $today = getSiku();
    $commandePoulesData["siku"] = $today;

    $commandePoulesFound = $commandePoulesModel->find($commandePoulesID);
    if ($commandePoulesID == $commandePoulesFound->id) {
        $cmdClientFound = $commandeClientsModel->find($commandePoulesFound->commandeClients_idCommande);
        $cmdClientID = $cmdClientFound->id;
        if (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
            $message = "Les informations de cette Commande Poule ne peuvent etre modifie ";
            return  success205($message);
        } else {
            $oldMontant = $commandePoulesFound->montant;
            $prixTotal = $commandePoulesFound->prixtotal;

            if ($oldMontant < $prixTotal) {
                $montant = cumulMontant($newMontant, $oldMontant, $prixTotal);
                $commandePoules->setMontant($montant);
                $commandePoules->setUpdated_at($today);

                #Si le client paie la totalite de sa dette, on modifie le montant et le statut de sa cmde change REGLE 
                if (($montant == $prixTotal) && ($cmdClientFound->statusCmd_id == STATUS_CMD_E_DETTE)) {
                    $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                    $commandeClients->setUpdated_at($today);
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                    $commandePoulesModel->update($commandePoulesID, $commandePoules);
                    $message = "Le montant de la Commande Poule updated successfully";
                    createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POULE);
                    return success200($message);
                } else {

                    $commandeClients->setStatusCmd_id(STATUS_CMD_RESERVE);
                    $commandeClients->setUpdated_at($today);
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                    $commandePoulesModel->update($commandePoulesID, $commandePoules);
                    $message = "Le montant de la Commande Poule updated successfully";
                    createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POULE);
                    return success200($message);
                }
            } elseif ($oldMontant == $prixTotal) {
                $message = "le montant de cette commande est deja soldé";
                return success205($message);
            } else {
                $message = "Veuillez verifier le montant de la commande";
                return success205($message);
            }
        }
    } else {
        $message = "Commande Poule not update";
        return success205($message);
    }
}