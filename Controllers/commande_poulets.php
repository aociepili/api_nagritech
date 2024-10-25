<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');


use App\Autoloader;
use App\Models\Commande_pouletsModel;
use App\Models\Commande_clientsModel;

Autoloader::register();

# Store
function storeCommandePoulets($commandePouletsData)
{
    $commandePouletsModel = new Commande_pouletsModel();
    $commandePoulets = $commandePouletsModel;
    chargementCommande($commandePouletsData);
    $montant = $commandePouletsData["montant"];
    chiffreVerify($montant, "montant");


    if ($commandePouletsData["montant"] == null) {
        $commandePouletsData["montant"] = 0;
    }

    $commandePouletsData["statusCmd_id"] = createStatutCommande($montant);

    $natureID = $commandePouletsData["natures_idNature"];
    $clientID = $commandePouletsData["clients_idClient"];
    $quantite = $commandePouletsData["quantite"];
    $montant = $commandePouletsData["montant"];

    natureVerify($natureID, DESIGN_POULET);
    $today = getSiku();
    $commandePouletsData["date"] =  $today;

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);
    $testStatusCmd = testStatusCmdbyId($commandePouletsData["statusCmd_id"]);

    if ($testClient && $testNature && $testStatusCmd) {

        # Creer la Commande Client
        createCommandeClient($commandePouletsData);
        $cmdClientID = getLastCommandeClient($commandePouletsData)->id;

        if (empty($cmdClientID)) {
            return success205("Pas d'enregistrement Commande Client");
        } else {
            $prixtotal = getPrixTotal($cmdClientID, $quantite);
            $commandePoulets->setQuantite($quantite);
            $commandePoulets->setCommandeClients_idCommande($cmdClientID);
            $commandePoulets->setMontant($montant);
            $commandePoulets->setPrixtotal($prixtotal);
            $commandePoulets->setCreated_at($today);

            $commandePouletsModel->create($commandePoulets);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CMD_POULET);
            $message = "Commande Poulet  created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteCommandePoulets($commandePouletsParams)
{
    $commandePouletsModel = new Commande_pouletsModel();
    paramsVerify($commandePouletsParams, "Commande Poulet");

    $commandePouletsID = $commandePouletsParams['id'];
    $commandePouletsData = $commandePouletsModel->find($commandePouletsID);

    if ($commandePouletsID == $commandePouletsData->id) {

        try {
            $commandePouletsModel->delete($commandePouletsID);
            $test = deleteCmdClientData($commandePouletsData->commandeClients_idCommande);
            if ($test) {
                $message = "Commande Poulet deleted successfully";
                createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CMD_POULET);
                return success200($message);
            }
        } catch (\Throwable $th) {
            $message = "Erreur Systeme :" . $th;
            createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_CMD_POULET);
            return error405($message);
        }
    } else {
        $message = "Commande Poulet not Delete  ";
        return error405($message);
    }
}

#Get
function getCommandePouletById($commandePouletsParams)
{
    $commandePouletsModel = new Commande_pouletsModel();
    paramsVerify($commandePouletsParams, "Commande Poulet");
    $commandePouletsFound = $commandePouletsModel->find($commandePouletsParams['id']);

    if (!empty($commandePouletsFound)) {
        $dataCPt = getCommandePouletDataById($commandePouletsFound->id);
        $message = "Commande Poulet Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CMD_POULET);
        return datasuccess200($message, $dataCPt);
    } else {
        $message = "No commande Poulet Found";
        return success205($message);
    }
}

function getListCommandePoulet()
{
    $commandePouletsModel = new Commande_pouletsModel();
    $commandePoulets = (array)$commandePouletsModel->findAll();

    if (!empty($commandePoulets)) {
        $dataListCP = getListCommandePouletData($commandePoulets);
        $message = "Liste des Commandes Poulets";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CMD_POULET);
        return dataTableSuccess200($message, $dataListCP);
    } else {
        $message = "Pas de Commandes Poulets";
        return success205($message);
    }
}

# Update
function updateCommandePoulet($commandePouletsData, $commandePouletsParams)
{
    $commandePouletsModel = new Commande_pouletsModel();
    $commandePoulets = $commandePouletsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandePouletsParams, "Commande Poulet");


    $date = $commandePouletsData["date"];
    $natureID = $commandePouletsData["natures_idNature"];
    $clientID = $commandePouletsData["clients_idClient"];
    $commandePouletsID = $commandePouletsParams['id'];
    $quantite = $commandePouletsData["quantite"];

    $montant = $commandePouletsData["montant"];
    $today = getSiku();
    $commandePouletsData["siku"] = $today;

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);


    if ($testNature  && $testClient) {
        $dataCmdPoulet = $commandePouletsModel->find($commandePouletsID);
        $cmdClientID = $dataCmdPoulet->commandeClients_idCommande;


        $commandeClients->setDate($date);
        $commandeClients->setNatures_idNature($natureID);
        $commandeClients->setClients_idClient($clientID);
        $commandeClients->setUpdated_at($today);

        $prixtotal = getPrixTotal($cmdClientID, $quantite);
        $commandePoulets->setQuantite($quantite);
        $commandePoulets->setCommandeClients_idCommande($cmdClientID);
        $commandePoulets->setMontant($montant);
        $commandePoulets->setPrixtotal($prixtotal);
        $commandePoulets->setUpdated_at($today);

        $commandePouletsFound = $commandePouletsModel->find($commandePouletsID);
        if ($commandePouletsID == $commandePouletsFound->id) {
            if (in_array($commandePouletsFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
                $message = "Le status de cette Commande Poulet ne peut etre modifie ";
                return  success205($message);
            } else {
                $commandePouletsModel->update($commandePouletsID, $commandePoulets);

                if (modCmdClient($commandePouletsData) or isset($today)) {
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                }
                $message = "Commande Poulet updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POULET);
                return success200($message);
            }
        } else {
            $message = "Commande Poulet not update";
            return success205($message);
        }
    }
}

function changeStatutCmdPoulet($commandePouletsData, $commandePouletsParams)
{
    require_once 'php-jwt/authentification.php';
    $commandePouletsModel = new Commande_pouletsModel();
    $commandePoulets = $commandePouletsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;

    # test de chargement de parametre
    paramsVerify($commandePouletsParams, "Commande Poulet");

    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];

    if ($role == IS_ADMIN) {
        $commandePouletsData["admins_id"] = $auteurID;
        $commandePouletsData["admins_idAdmin"] = $auteurID;
        $commandePouletsData["agents_id"] = ID_AGENT_SYSTEME;
        $commandePouletsData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $commandePouletsData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $commandePouletsData["agents_id"] = $auteurID;
        $commandePouletsData["agents_idAgent"] = $auteurID;
        $commandePouletsData["admins_id"] = ID_ADMIN_SYSTEME;
        $commandePouletsData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $commandePouletsData["role_id"] = IS_AGENT_ID;
    }

    $newStatusCmdID = $commandePouletsData["statusCmd_id"];
    $commandePouletsID = $commandePouletsParams['id'];
    statusCmdVerify($newStatusCmdID);

    $testStatusCmd = testStatusCmdbyId($newStatusCmdID);

    if ($testStatusCmd) {
        $dataCmdPouletFound = $commandePouletsModel->find($commandePouletsID);
        if ($dataCmdPouletFound->id == $commandePouletsID) {
            $cmdClientID = $dataCmdPouletFound->commandeClients_idCommande;
            $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);
            if ($dataCmdClientFound->id == $cmdClientID) {
                $natureID = $dataCmdClientFound->natures_idNature;
                $clientID = $dataCmdClientFound->clients_idClient;
                $statusCmdID = $dataCmdClientFound->statusCmd_id;
                $sortieID = $dataCmdClientFound->id_sortie;

                $quantite = $dataCmdPouletFound->quantite;
                $montant = $dataCmdPouletFound->montant;
                $prixtotal = $dataCmdPouletFound->prixtotal;

                $commandePouletsData["commandeClients_idCommande"] = $cmdClientID;
                $today = getSiku();
                $commandePouletsData["date"] = $today;
                $commandePouletsData["siku"] = $today;
                $commandePouletsData["etat_rapportID"] = ETAT_BON;
                $commandePouletsData["natures_idNature"] = $natureID;
                $commandePouletsData["quantite"] = $quantite;
                $commandePouletsData["montant"] = $montant;
                $commandePouletsData["prixtotal"] = $prixtotal;
                $commandePouletsData["clients_idClient"] = $clientID;
                $commandePouletsData["statusCmd_id"] = $statusCmdID;


                natureVerify($natureID, DESIGN_POULET);
                statusCmdVerify($statusCmdID);
                chiffreVerify($montant, "Montant");
                chiffreVerify($prixtotal, "Prix Total");

                if ($statusCmdID == STATUS_CMD_ANNULE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Poulet du client a déjà été annulé";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_E_DETTE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Poulet du client a déjà été reglé à credit";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_REGLE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Poulet du client a déjà été reglé";
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
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POULET);
                        $message = "Le Statut de Commande Poulet du client a été modifié";
                        return success200($message);
                    } elseif (in_array($newStatusCmdID, $statusDifficileMod)) {
                        if (($montant < $prixtotal) && ($newStatusCmdID == STATUS_CMD_E_DETTE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandePouletsData['motifSorties_idMotif'] = MOTIF_SORTIE_CREDIT;
                            sortiePoulet($commandePouletsData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandePouletsData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POULET);
                            $message = "Le Statut de Commande Poulet du client a été reglé avec une dette";
                            return success200($message);
                        } elseif (($prixtotal == $montant) && ($newStatusCmdID == STATUS_CMD_REGLE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandePouletsData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
                            sortiePoulet($commandePouletsData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandePouletsData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POULET);
                            $message = "Le Statut de Commande Poulet du client a été reglé";
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
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_POULET);
                        $message = "Le Statut de Commande Poulet du client a été annulée";
                        return success200($message);
                    } else {
                        $message = "Veuillez verifier le Status de la Commande Poulet";
                        return success205($message);
                    }
                }
            } else {
                $message = "Cette Commande  Poulet n'est repertorié comme une commande client ";
                return success205($message);
            }
        } else {
            $message = "Commande  Poulet not Found ";
            return success205($message);
        }
    }
}

function updateMontantPoulet($commandePouletsData, $commandePouletsParams)
{
    $commandePouletsModel = new Commande_pouletsModel();
    $commandePoulets = $commandePouletsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;

    paramsVerify($commandePouletsParams, "Commande Poulet");

    $newMontant = $commandePouletsData["montant"];
    chiffreVerify($newMontant, "montant");
    $commandePouletsID = $commandePouletsParams['id'];
    $today = getSiku();
    $commandePouletsData["siku"] = $today;

    $commandePouletsFound = $commandePouletsModel->find($commandePouletsID);
    if ($commandePouletsID == $commandePouletsFound->id) {
        $cmdClientFound = $commandeClientsModel->find($commandePouletsFound->commandeClients_idCommande);
        $cmdClientID = $cmdClientFound->id;
        if (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
            $message = "Les informations de cette Commande Poulet ne peuvent etre modifie ";
            return  success205($message);
        } else {
            $oldMontant = $commandePouletsFound->montant;
            $prixTotal = $commandePouletsFound->prixtotal;

            if ($oldMontant < $prixTotal) {
                $montant = cumulMontant($newMontant, $oldMontant, $prixTotal);
                $commandePoulets->setMontant($montant);
                $commandePoulets->setUpdated_at($today);

                #Si le client paie la totalite de sa dette, on modifie le montant et le statut de sa cmde change REGLE 
                if (($montant == $prixTotal)) {
                    if ((in_array($cmdClientFound->statusCmd_id, STATUS_CMD_NO_STOCK_IMPACT))) {
                        $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $commandePouletsModel->update($commandePouletsID, $commandePoulets);
                        $message = "Le montant de la Commande Poulet updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POULET);
                        return success200($message);
                    } else {
                        // $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $commandePouletsModel->update($commandePouletsID, $commandePoulets);
                        $message = "Le montant de la Commande Poulet updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POULET);
                        return success200($message);
                    }
                } elseif (($montant < $prixTotal) && (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_PAYABLE))) {
                    $somme = sommeMontant($oldMontant, $newMontant);
                    if (($somme >= $prixTotal) && (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_NO_STOCK_IMPACT))) {
                        $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $commandePouletsModel->update($commandePouletsID, $commandePoulets);
                        $message = "Le montant de la Commande Poulet updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POULET);
                        return success200($message);
                    } else {
                        // $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $commandePouletsModel->update($commandePouletsID, $commandePoulets);
                        $message = "Le montant de la Commande Poulet updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POULET);
                        return success200($message);
                    }
                } else {
                    $commandeClients->setStatusCmd_id(STATUS_CMD_RESERVE);
                    $commandeClients->setUpdated_at($today);
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                    $commandePouletsModel->update($commandePouletsID, $commandePoulets);
                    $message = "Le montant de la Commande Poulet updated successfully";
                    createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_POULET);
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
        $message = "Commande Poulet not update";
        return success205($message);
    }
}

function statutCmdPoulet($cmdPouletID)
{
    require_once 'php-jwt/authentification.php';
    $commandePouletsModel = new Commande_pouletsModel();
    $commandeAliments = $commandePouletsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    # test de chargement de parametre
    // paramsVerify($commandeAlimentsParams, "Commande Aliment");

    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];
    $cmdPouletsData = array();

    if ($role == IS_ADMIN) {
        $cmdPouletsData["admins_id"] = $auteurID;
        $cmdPouletsData["admins_idAdmin"] = $auteurID;
        $cmdPouletsData["agents_id"] = ID_AGENT_SYSTEME;
        $cmdPouletsData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $cmdPouletsData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $cmdPouletsData["agents_id"] = $auteurID;
        $cmdPouletsData["agents_idAgent"] = $auteurID;
        $cmdPouletsData["admins_id"] = ID_ADMIN_SYSTEME;
        $cmdPouletsData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $cmdPouletsData["role_id"] = IS_AGENT_ID;
    }

    $commandePouletID = $cmdPouletID;
    $dataCmdPouletFound = $commandePouletsModel->find($commandePouletID);

    $cmdClientID = $dataCmdPouletFound->commandeClients_idCommande;
    $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);

    $natureID = $dataCmdClientFound->natures_idNature;
    $clientID = $dataCmdClientFound->clients_idClient;
    $statusCmdID = $dataCmdClientFound->statusCmd_id;
    $sortieID = $dataCmdClientFound->id_sortie;

    $quantite = $dataCmdPouletFound->quantite;
    $montant = $dataCmdPouletFound->montant;
    $prixtotal = $dataCmdPouletFound->prixtotal;

    $cmdPouletsData["commandeClients_idCommande"] = $cmdClientID;
    $today = getSiku();
    $cmdPouletsData["date"] = $today;
    $cmdPouletsData["siku"] = $today;
    $cmdPouletsData["etat_rapportID"] = ETAT_BON;
    $cmdPouletsData["natures_idNature"] = $natureID;
    $cmdPouletsData["quantite"] = $quantite;
    $cmdPouletsData["montant"] = $montant;
    $cmdPouletsData["prixtotal"] = $prixtotal;
    $cmdPouletsData["clients_idClient"] = $clientID;
    $cmdPouletsData["statusCmd_id"] = $statusCmdID;

    $cmdPouletsData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
    sortiePoulet($cmdPouletsData);

    // $commandeClients->setStatusCmd_id($newStatusCmdID);
    // $commandeClients->setUpdated_at($today);

    $sortieID = getLastSortie($cmdPouletsData)->id;
    $commandeClients->setId_sortie($sortieID);
    $commandeClientsModel->update($cmdClientID, $commandeClients);
    // createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_ALIMENT);
    // $message = "Le Statut de Commande Aliment du client a été reglé";
    // return success200($message);
}
