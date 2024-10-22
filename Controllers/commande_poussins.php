<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');
include_once '..\core\Data.php';

use App\Autoloader;
use App\Models\Commande_poussinsModel;
use App\Models\Commande_clientsModel;

Autoloader::register();

# Store
function storeCommandePoussin($commandePoussinsData)
{
    $commandePoussinsModel = new Commande_poussinsModel();
    $commandePoussins = $commandePoussinsModel;
    chargementCommande($commandePoussinsData);
    $montant = $commandePoussinsData["montant"];
    chiffreVerify($montant, "montant");

    if ($commandePoussinsData["montant"] == null) {
        $commandePoussinsData["montant"] = 0;
    }

    $commandePoussinsData["statusCmd_id"] = createStatutCommande($montant);

    // $date = $commandePoussinsData["date"];
    $natureID = $commandePoussinsData["natures_idNature"];
    $clientID = $commandePoussinsData["clients_idClient"];
    $quantite = $commandePoussinsData["quantite"];
    $cmdClientID = $commandePoussinsData["commandeClients_idCommande"];
    $montant = $commandePoussinsData["montant"];
    // $prixtotal = $commandePoussinsData["prixtotal"];
    $today = getSiku();
    $commandePoussinsData["date"] =  $today;

    natureVerify($natureID, DESIGN_POUSSIN);
    chiffreVerify($montant, "Montant");
    chiffreVerify($quantite, "Quantite");

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);
    $testStatusCmd = testStatusCmdbyId($commandePoussinsData["statusCmd_id"]);
    if ($testClient && $testNature && $testStatusCmd) {

        # Creer la Commande Client
        createCommandeClient($commandePoussinsData);
        $cmdClientID = getLastCommandeClient($commandePoussinsData)->id;

        if (empty($cmdClientID)) {
            return success205("Pas d'enregistrement Commande Client");
        } else {
            $prixtotal = getPrixTotal($cmdClientID, $quantite);

            $commandePoussins->setQuantite($quantite);
            $commandePoussins->setCommandeClients_idCommande($cmdClientID);
            $commandePoussins->setMontant($montant);
            $commandePoussins->setPrixtotal($prixtotal);
            $commandePoussins->setCreated_at($today);

            $commandePoussinsModel->create($commandePoussins);
            $message = "Commande Poussins  created successfully";
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CMD_POUSSIN);
            return success201($message);
        }
    }
}

#Delete
function deleteCommandePoussin($commandePoussinsParams)
{
    $commandePoussinsModel = new Commande_poussinsModel();
    paramsVerify($commandePoussinsParams, "Commande Poussin");

    $commandePoussinsID = $commandePoussinsParams['id'];
    $commandePoussinsData = $commandePoussinsModel->find($commandePoussinsID);

    if ($commandePoussinsID == $commandePoussinsData->id) {
        try {
            $commandePoussinsModel->delete($commandePoussinsID);
            $test = deleteCmdClientData($commandePoussinsData->commandeClients_idCommande);
            if ($test) {
                $message = "Commande Poussin deleted successfully";
                createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CMD_POUSSIN);
                return success200($message);
            }
        } catch (\Throwable $th) {
            $message = "Erreur Systeme :" . $th;
            createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_CMD_POUSSIN);
            return error405($message);
        }
    } else {
        $message = "Commande Poussin not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_CMD_POUSSIN);
        return error405($message);
    }
}

#Get
function getCommandePoussinById($commandePoussinsParams)
{
    $commandePoussinsModel = new Commande_poussinsModel();
    paramsVerify($commandePoussinsParams, "Commande Poussin");
    $commandePoussinsFound = $commandePoussinsModel->find($commandePoussinsParams['id']);

    if (!empty($commandePoussinsFound)) {
        $datacmdPoussin = getCommandePoussinDataById($commandePoussinsFound->id);
        $message = "Commande Poussins Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CMD_POUSSIN);
        return datasuccess200($message, $datacmdPoussin);
    } else {
        $message = "No commande Poussins Found";
        return success205($message);
    }
}

function getListCommandePoussin()
{
    $commandePoussinsModel = new Commande_poussinsModel();
    $commandePoussins = (array)$commandePoussinsModel->findAll();

    if (!empty($commandePoussins)) {
        $dataListCO = getListCommandePoussinData($commandePoussins);
        $message = "Liste des Commandes Poussins";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CMD_POUSSIN);
        return dataTableSuccess200($message, $dataListCO);
    } else {
        $message = "Pas de Commandes Poussins";
        return success205($message);
    }
}

# Update
function updateCommandePoussin($commandePoussinsData, $commandePoussinsParams)
{
    $commandePoussinsModel = new Commande_poussinsModel();
    $commandePoussins = $commandePoussinsModel;

    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandePoussinsParams, "Commande Poussin");


    $date = $commandePoussinsData["date"];
    $natureID = $commandePoussinsData["natures_idNature"];
    $clientID = $commandePoussinsData["clients_idClient"];

    $quantite = $commandePoussinsData["quantite"];
    $montant = $commandePoussinsData["montant"];
    $today = getSiku();

    $commandePoussinsID = $commandePoussinsParams['id'];

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);

    if ($testNature  && $testClient) {
        $dataCmdPoussin = $commandePoussinsModel->find($commandePoussinsID);
        $cmdClientID = $dataCmdPoussin->commandeClients_idCommande;

        $commandeClients->setDate($date);
        $commandeClients->setNatures_idNature($natureID);
        $commandeClients->setClients_idClient($clientID);
        $commandeClients->setUpdated_at($today);

        $prixtotal = getPrixTotal($cmdClientID, $quantite);
        $commandePoussins->setQuantite($quantite);
        $commandePoussins->setCommandeClients_idCommande($cmdClientID);
        $commandePoussins->setMontant($montant);
        $commandePoussins->setPrixtotal($prixtotal);
        $commandePoussins->setUpdated_at($today);

        $commandePoussinsFound = $commandePoussinsModel->find($commandePoussinsID);
        if ($commandePoussinsID == $commandePoussinsFound->id) {
            if (in_array($commandePoussinsFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
                $message = "Le status de cette Commande Poussin ne peut etre modifie ";
                return  success205($message);
            } else {
                $commandePoussinsModel->update($commandePoussinsID, $commandePoussins);
                if (modCmdClient($commandePoussinsParams) or isset($today)) {
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                }
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POUSSIN);
                $message = "Commande Poussins updated successfully";
                return success200($message);
            }
        } else {
            $message = "No Commande Poussins  Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_CMD_POUSSIN);
            return success205($message);
        }
    }
}

function changeStatutCmdPoussin($commandePoussinsData, $commandePoussinsParams)
{
    require_once 'php-jwt/authentification.php';
    $commandePoussinsModel = new Commande_poussinsModel();
    $commandePoussins = $commandePoussinsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandePoussinsParams, "Commande Poussin");

    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];

    if ($role == IS_ADMIN) {
        $commandePoussinsData["admins_id"] = $auteurID;
        $commandePoussinsData["admins_idAdmin"] = $auteurID;
        $commandePoussinsData["agents_id"] = ID_AGENT_SYSTEME;
        $commandePoussinsData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $commandePoussinsData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $commandePoussinsData["agents_id"] = $auteurID;
        $commandePoussinsData["agents_idAgent"] = $auteurID;
        $commandePoussinsData["admins_id"] = ID_ADMIN_SYSTEME;
        $commandePoussinsData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $commandePoussinsData["role_id"] = IS_AGENT_ID;
    }

    $newStatusCmdID = $commandePoussinsData["statusCmd_id"];
    $commandePoussinsID = $commandePoussinsParams['id'];
    statusCmdVerify($newStatusCmdID);

    $testStatusCmd = testStatusCmdbyId($newStatusCmdID);

    if ($testStatusCmd) {
        $dataCmdPoussinFound = $commandePoussinsModel->find($commandePoussinsID);
        if ($dataCmdPoussinFound->id == $commandePoussinsID) {
            $cmdClientID = $dataCmdPoussinFound->commandeClients_idCommande;
            $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);
            if ($dataCmdClientFound->id == $cmdClientID) {
                $natureID = $dataCmdClientFound->natures_idNature;
                $clientID = $dataCmdClientFound->clients_idClient;
                $statusCmdID = $dataCmdClientFound->statusCmd_id;
                $sortieID = $dataCmdClientFound->id_sortie;

                $quantite = $dataCmdPoussinFound->quantite;
                $montant = $dataCmdPoussinFound->montant;
                $prixtotal = $dataCmdPoussinFound->prixtotal;

                $commandePoussinsData["commandeClients_idCommande"] = $cmdClientID;
                $today = getSiku();
                $commandePoussinsData["date"] = $today;
                $commandePoussinsData["siku"] = $today;
                $commandePoussinsData["etat_rapportID"] = ETAT_BON;
                $commandePoussinsData["natures_idNature"] = $natureID;
                $commandePoussinsData["quantite"] = $quantite;
                $commandePoussinsData["montant"] = $montant;
                $commandePoussinsData["prixtotal"] = $prixtotal;
                $commandePoussinsData["clients_idClient"] = $clientID;
                $commandePoussinsData["statusCmd_id"] = $statusCmdID;

                natureVerify($natureID, DESIGN_POUSSIN);
                statusCmdVerify($statusCmdID);
                chiffreVerify($montant, "Montant");
                chiffreVerify($prixtotal, "Prix Total");

                if ($statusCmdID == STATUS_CMD_ANNULE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Poussin du client a déjà été annulé";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_REGLE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Poussin du client a déjà été reglé";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_E_DETTE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Poussin du client a déjà été reglé à credit";
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
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POUSSIN);
                        $message = "Le Statut de Commande Poussin du client a été modifié";
                        return success200($message);
                    } elseif (in_array($newStatusCmdID, $statusDifficileMod)) {
                        if (($montant < $prixtotal) && ($newStatusCmdID == STATUS_CMD_E_DETTE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandePoussinsData['motifSorties_idMotif'] = MOTIF_SORTIE_CREDIT;
                            sortiePoussin($commandePoussinsData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandePoussinsData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POUSSIN);
                            $message = "Le Statut de Commande Poussin du client a été reglé avec une dette";
                            return success200($message);
                        } elseif (($prixtotal == $montant) && ($newStatusCmdID == STATUS_CMD_REGLE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandePoussinsData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
                            sortiePoussin($commandePoussinsData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandePoussinsData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POUSSIN);
                            $message = "Le Statut de Commande Poussin du client a été reglé";
                            return success200($message);
                        } else {
                            $message = "Veuillez payer d'abord votre Commande ";
                            return success205($message);
                        }
                    } elseif ($newStatusCmdID == STATUS_CMD_ANNULE) {
                        #Pas beaucoup d'operation juste le changement du status
                        $commandeClients->setStatusCmd_id($newStatusCmdID);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POUSSIN);
                        $message = "Le Statut de Commande Poussin du client a été annulée";
                        return success200($message);
                    } else {
                        $message = "Veuillez verifier le Status de la Commande Poussin";
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_CMD_POUSSIN);
                        return success205($message);
                    }
                }
            } else {
                $message = "Cette Commande  Poussin n'est repertorié comme une commande client ";
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_CMD_POUSSIN);
                return success205($message);
            }
        } else {
            $message = "Commande  Poussin not Found ";
            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_CMD_POUSSIN);
            return success205($message);
        }
    }
}

function updateMontantPoussin($commandePoussinsData, $commandePoussinsParams)
{
    $commandePoussinsModel = new Commande_poussinsModel();
    $commandePoussins = $commandePoussinsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;

    paramsVerify($commandePoussinsParams, "Commande Poussin");

    $newMontant = $commandePoussinsData["montant"];
    chiffreVerify($newMontant, "montant");
    $commandePoussinsID = $commandePoussinsParams['id'];
    $today = getSiku();
    $commandePoussinsData["siku"] = $today;

    $commandePoussinsFound = $commandePoussinsModel->find($commandePoussinsID);
    if ($commandePoussinsID == $commandePoussinsFound->id) {
        $cmdClientFound = $commandeClientsModel->find($commandePoussinsFound->commandeClients_idCommande);
        $cmdClientID = $cmdClientFound->id;
        if (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
            $message = "Les informations de cette Commande Poussin ne peuvent etre modifie ";
            return  success205($message);
        } else {
            $oldMontant = $commandePoussinsFound->montant;
            $prixTotal = $commandePoussinsFound->prixtotal;

            if ($oldMontant < $prixTotal) {
                $montant = cumulMontant($newMontant, $oldMontant, $prixTotal);
                $commandePoussins->setMontant($montant);
                $commandePoussins->setUpdated_at($today);

                #Si le client paie la totalite de sa dette, on modifie le montant et le statut de sa cmde change REGLE 
                if (($montant == $prixTotal) && ($cmdClientFound->statusCmd_id == STATUS_CMD_E_DETTE)) {
                    $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                    $commandeClients->setUpdated_at($today);

                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                    $commandePoussinsModel->update($commandePoussinsID, $commandePoussins);
                    $message = "Le montant de la Commande Poussin updated successfully";
                    createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POUSSIN);
                    return success200($message);
                } else {
                    $commandeClients->setStatusCmd_id(STATUS_CMD_RESERVE);
                    $commandeClients->setUpdated_at($today);
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                    $commandePoussinsModel->update($commandePoussinsID, $commandePoussins);
                    $message = "Le montant de la Commande Poussin updated successfully";
                    createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POUSSIN);
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
        $message = "Commande Poussin not update";
        return success205($message);
    }
}