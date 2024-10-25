<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');


use App\Autoloader;
use App\Models\Commande_biogazModel;
use App\Models\Commande_clientsModel;

Autoloader::register();

# Store
function storeCommandeBiogaz($commandeBiogazData)
{
    $commandeBiogazModel = new Commande_biogazModel();
    $commandeBiogaz = $commandeBiogazModel;
    chargementCommande($commandeBiogazData);

    $montant = $commandeBiogazData["montant"];
    chiffreVerify($montant, "montant");

    # Le status est encours par defaut
    if ($commandeBiogazData["montant"] == null) {
        $commandeBiogazData["montant"] = 0;
    }

    $commandeBiogazData["statusCmd_id"] = createStatutCommande($montant);

    $natureID = $commandeBiogazData["natures_idNature"];
    $clientID = $commandeBiogazData["clients_idClient"];
    $quantite = $commandeBiogazData["quantite"];
    $montant = $commandeBiogazData["montant"];
    natureVerify($natureID, DESIGN_BIOGAZ);
    $today = getSiku();
    $commandeBiogazData["date"] =  $today;

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);
    $testStatusCmd = testStatusCmdbyId($commandeBiogazData["statusCmd_id"]);


    if ($testClient && $testNature && $testStatusCmd) {

        # Creer la Commande Client
        createCommandeClient($commandeBiogazData);
        $cmdClientID = getLastCommandeClient($commandeBiogazData)->id;
        if (empty($cmdClientID)) {
            return success205("Pas d'enregistrement Commande Client");
        } else {
            $prixtotal = getPrixTotal($cmdClientID, $quantite);
            $commandeBiogaz->setQuantite($quantite);
            $commandeBiogaz->setCommandeClients_idCommande($cmdClientID);
            $commandeBiogaz->setMontant($montant);
            $commandeBiogaz->setPrixtotal($prixtotal);
            $commandeBiogaz->setCreated_at($today);

            $commandeBiogazModel->create($commandeBiogaz);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
            $message = "Commande Biogaz  created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteCommandeBiogaz($commandeBiogazParams)
{
    $commandeBiogazModel = new Commande_biogazModel();
    paramsVerify($commandeBiogazParams, "Commande Biogaz");

    $commandeBiogazID = $commandeBiogazParams['id'];
    $commandeBiogazData = $commandeBiogazModel->find($commandeBiogazID);

    if ($commandeBiogazID == $commandeBiogazData->id) {
        try {
            $commandeBiogazModel->delete($commandeBiogazID);
            $test = deleteCmdClientData($commandeBiogazData->commandeClients_idCommande);
            if ($test) {
                $message = "Commande Biogaz deleted successfully";
                createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                return success200($message);
            }
        } catch (\Throwable $th) {
            $message = "Erreur Systeme :" . $th;
            createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_CMD_BIOGAZ);
            return error405($message);
        }
    } else {
        $message = "Commande Biogaz not delete ";
        return error405($message);
    }
}

#Get
function getCommandeBiogazById($commandeBiogazParams)
{
    $commandeBiogazModel = new Commande_biogazModel();
    paramsVerify($commandeBiogazParams, "Commande Biogaz");
    $commandeBiogazFound = $commandeBiogazModel->find($commandeBiogazParams['id']);

    if (!empty($commandeBiogazFound)) {
        $datacmdBG = getCommandeBiogazDataById($commandeBiogazFound->id);
        $message = "Commande Biogaz Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
        return datasuccess200($message, $datacmdBG);
    } else {
        $message = "No commande Biogaz Found";
        return  success205($message);
    }
}

function getListCommandeBiogaz()
{
    $commandeBiogazModel = new Commande_biogazModel();
    $commandeBiogaz = (array)$commandeBiogazModel->findAll();

    if (!empty($commandeBiogaz)) {
        $dataListCBG = getListCommandeBiogazData($commandeBiogaz);
        $message = "Liste des Commandes Biogaz";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
        return dataTableSuccess200($message, $dataListCBG);
    } else {
        $message = "Pas de Commandes Biogaz";
        return success205($message);
    }
}

# Update
function updateCommandeBiogaz($commandeBiogazData, $commandeBiogazParams)
{
    $commandeBiogazModel = new Commande_biogazModel();
    $commandeBiogaz = $commandeBiogazModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandeBiogazParams, "Commande Biogaz");


    $date = $commandeBiogazData["date"];
    $natureID = $commandeBiogazData["natures_idNature"];
    $clientID = $commandeBiogazData["clients_idClient"];
    $commandeBiogazID = $commandeBiogazParams['id'];
    $quantite = $commandeBiogazData["quantite"];

    $montant = $commandeBiogazData["montant"];

    $today = getSiku();
    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);


    if ($testNature && $testClient) {
        $dataCmdBiogaz = $commandeBiogazModel->find($commandeBiogazID);
        $cmdClientID = $dataCmdBiogaz->commandeClients_idCommande;

        $commandeClients->setDate($date);
        $commandeClients->setNatures_idNature($natureID);
        $commandeClients->setClients_idClient($clientID);
        $commandeClients->setUpdated_at($today);

        $prixtotal = getPrixTotal($cmdClientID, $quantite);
        $commandeBiogaz->setQuantite($quantite);
        $commandeBiogaz->setCommandeClients_idCommande($cmdClientID);
        $commandeBiogaz->setMontant($montant);
        $commandeBiogaz->setPrixtotal($prixtotal);
        $commandeBiogaz->setUpdated_at($today);

        $commandeBiogazFound = $commandeBiogazModel->find($commandeBiogazID);
        if ($commandeBiogazID == $commandeBiogazFound->id) {
            if (in_array($commandeBiogazFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
                $message = "Le status de cette Commande Biogaz ne peut etre modifie ";
                return  success205($message);
            } else {
                $commandeBiogazModel->update($commandeBiogazID, $commandeBiogaz);

                if (modCmdClient($commandeBiogazData) or isset($today)) {
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                }
                $message = "Commande Biogaz updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                return success200($message);
            }
        } else {
            $message = "Commande Biogaz not update ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_CMD_BIOGAZ);
            return  success205($message);
        }
    }
}

function changeStatutCmdBiogaz($commandeBiogazData, $commandeBiogazParans)
{
    require_once 'php-jwt/authentification.php';
    $commandeBiogazModel = new Commande_biogazModel();
    $commandeBiogaz = $commandeBiogazModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandeBiogazParans, "Commande Biogaz");

    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];

    if ($role == IS_ADMIN) {
        $commandeBiogazData["admins_id"] = $auteurID;
        $commandeBiogazData["admins_idAdmin"] = $auteurID;
        $commandeBiogazData["agents_id"] = ID_AGENT_SYSTEME;
        $commandeBiogazData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $commandeBiogazData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $commandeBiogazData["agents_id"] = $auteurID;
        $commandeBiogazData["agents_idAgent"] = $auteurID;
        $commandeBiogazData["admins_id"] = ID_ADMIN_SYSTEME;
        $commandeBiogazData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $commandeBiogazData["role_id"] = IS_AGENT_ID;
    }

    $newStatusCmdID = $commandeBiogazData["statusCmd_id"];
    $commandeBiogazID = $commandeBiogazParans['id'];
    statusCmdVerify($newStatusCmdID);

    $testStatusCmd = testStatusCmdbyId($newStatusCmdID);

    if ($testStatusCmd) {
        $dataCmdBiogazFound = $commandeBiogazModel->find($commandeBiogazID);
        if ($dataCmdBiogazFound->id == $commandeBiogazID) {
            $cmdClientID = $dataCmdBiogazFound->commandeClients_idCommande;
            $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);
            if ($dataCmdClientFound->id == $cmdClientID) {
                $natureID = $dataCmdClientFound->natures_idNature;
                $clientID = $dataCmdClientFound->clients_idClient;
                $statusCmdID = $dataCmdClientFound->statusCmd_id;
                $sortieID = $dataCmdClientFound->id_sortie;

                $quantite = $dataCmdBiogazFound->quantite;
                $montant = $dataCmdBiogazFound->montant;
                $prixtotal = $dataCmdBiogazFound->prixtotal;

                $commandeBiogazData["commandeClients_idCommande"] = $cmdClientID;
                $today = getSiku();
                $commandeBiogazData["date"] = $today;
                $commandeBiogazData["siku"] = $today;
                $commandeBiogazData["etat_rapportID"] = ETAT_BON;
                $commandeBiogazData["natures_idNature"] = $natureID;
                $commandeBiogazData["quantite"] = $quantite;
                $commandeBiogazData["montant"] = $montant;
                $commandeBiogazData["prixtotal"] = $prixtotal;
                $commandeBiogazData["clients_idClient"] = $clientID;
                $commandeBiogazData["statusCmd_id"] = $statusCmdID;


                natureVerify($natureID, DESIGN_BIOGAZ);
                statusCmdVerify($statusCmdID);
                chiffreVerify($montant, "Montant");
                chiffreVerify($prixtotal, "Prix Total");


                if ($statusCmdID == STATUS_CMD_ANNULE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Biogaz du client a déjà été annulé";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_E_DETTE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Biogaz du client a déjà été reglé à credit";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_REGLE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Biogaz du client a déjà été reglé";
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
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                        $message = "Le Statut de Commande Biogaz du client a été modifié";
                        return success200($message);
                    } elseif (in_array($newStatusCmdID, $statusDifficileMod)) {
                        if (($montant < $prixtotal) && ($newStatusCmdID == STATUS_CMD_E_DETTE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandeBiogazData['motifSorties_idMotif'] = MOTIF_SORTIE_CREDIT;
                            sortieBiogaz($commandeBiogazData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandeBiogazData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                            $message = "Le Statut de Commande Biogaz du client a été reglé avec une dette";
                            return success200($message);
                        } elseif (($prixtotal == $montant) && ($newStatusCmdID == STATUS_CMD_REGLE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandeBiogazData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
                            sortieBiogaz($commandeBiogazData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandeBiogazData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                            $message = "Le Statut de Commande Biogaz du client a été reglé";
                            return success200($message);
                        } else {
                            $message = "Veuillez payer d'abord votre Commande";
                            return success205($message);
                        }
                    } elseif ($newStatusCmdID == STATUS_CMD_ANNULE) {
                        $commandeClients->setStatusCmd_id($newStatusCmdID);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                        $message = "Le Statut de Commande Biogaz du client a été annulée";
                        return success200($message);
                    } else {
                        $message = "Veuillez verifier le Status de la Commande Biogaz";
                        return success205($message);
                    }
                }
            } else {
                $message = "Cette Commande  Biogaz n'est repertorié comme une commande client ";
                return success205($message);
            }
        } else {
            $message = "Commande  Biogaz not Found ";
            return success205($message);
        }
    }
}

function updateMontantBiogaz($commandeBiogazData, $commandeBiogazParams)
{
    $CommandeBiogazModel = new Commande_biogazModel();
    $commandeBiogaz = $CommandeBiogazModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandeBiogazParams, "Commande Biogaz");

    $newMontant = $commandeBiogazData["montant"];
    chiffreVerify($newMontant, "montant");
    $commandeBiogazID = $commandeBiogazParams['id'];
    $today = getSiku();
    $commandeBiogazData["siku"] = $today;

    $commandeBiogazFound = $CommandeBiogazModel->find($commandeBiogazID);

    if ($commandeBiogazID == $commandeBiogazFound->id) {
        $cmdClientFound = $commandeClientsModel->find($commandeBiogazFound->commandeClients_idCommande);
        $cmdClientID = $cmdClientFound->id;

        if (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
            $message = "Les informations de cette Commande Biogaz ne peuvent etre modifie ";
            return  success205($message);
        } else {
            $oldMontant = $commandeBiogazFound->montant;
            $prixTotal = $commandeBiogazFound->prixtotal;


            if ($oldMontant < $prixTotal) {
                $montant = cumulMontant($newMontant, $oldMontant, $prixTotal);
                $commandeBiogaz->setMontant($montant);
                $commandeBiogaz->setUpdated_at($today);
                #Si le client paie la totalite de sa dette, on modifie le montant et le statut de sa cmde change REGLE
                if (($montant == $prixTotal)) {

                    if (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_NO_STOCK_IMPACT)) {
                        $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                        $CommandeBiogazModel->update($commandeBiogazID, $commandeBiogaz);
                        $message = "Le montant de la Commande Biogaz updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                        return success200($message);
                    } else {
                        // $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                        $CommandeBiogazModel->update($commandeBiogazID, $commandeBiogaz);
                        $message = "Le montant de la Commande Biogaz updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                        return success200($message);
                    }
                } elseif (($montant < $prixTotal) && (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_PAYABLE))) {
                    $somme = sommeMontant($oldMontant, $newMontant);

                    if (($somme >= $prixTotal) && (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_NO_STOCK_IMPACT))) {
                        $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $CommandeBiogazModel->update($commandeBiogazID, $commandeBiogaz);
                        $message = "Le montant de la Commande Biogaz updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                        return success200($message);
                    } else {
                        // $commandeClients->setStatusCmd_id(STATUS_CMD_RESERVE);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $CommandeBiogazModel->update($commandeBiogazID, $commandeBiogaz);
                        $message = "Le montant de la Commande Biogaz updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
                        return success200($message);
                    }
                }else{
                    $commandeClients->setStatusCmd_id(STATUS_CMD_RESERVE);
                    $commandeClients->setUpdated_at($today);
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                    $CommandeBiogazModel->update($commandeBiogazID, $commandeBiogaz);
                    $message = "Le montant de la Commande Biogaz updated successfully";
                    createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_BIOGAZ);
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
        $message = "Commande Biogaz not update";
        return success205($message);
    }
}

function statutCmdBiogaz($cmdBiogazID)
{
    require_once 'php-jwt/authentification.php';
    $commandeBiogazModel = new commande_biogazModel();
    $commandeAliments = $commandeBiogazModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    # test de chargement de parametre
    // paramsVerify($commandeAlimentsParams, "Commande Aliment");

    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];
    $cmdBiogazData = array();

    if ($role == IS_ADMIN) {
        $cmdBiogazData["admins_id"] = $auteurID;
        $cmdBiogazData["admins_idAdmin"] = $auteurID;
        $cmdBiogazData["agents_id"] = ID_AGENT_SYSTEME;
        $cmdBiogazData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $cmdBiogazData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $cmdBiogazData["agents_id"] = $auteurID;
        $cmdBiogazData["agents_idAgent"] = $auteurID;
        $cmdBiogazData["admins_id"] = ID_ADMIN_SYSTEME;
        $cmdBiogazData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $cmdBiogazData["role_id"] = IS_AGENT_ID;
    }

    $commandeBiogazID = $cmdBiogazID;
    $dataCmdBiogazFound = $commandeBiogazModel->find($commandeBiogazID);

    $cmdClientID = $dataCmdBiogazFound->commandeClients_idCommande;
    $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);

    $natureID = $dataCmdClientFound->natures_idNature;
    $clientID = $dataCmdClientFound->clients_idClient;
    $statusCmdID = $dataCmdClientFound->statusCmd_id;
    $sortieID = $dataCmdClientFound->id_sortie;

    $quantite = $dataCmdBiogazFound->quantite;
    $montant = $dataCmdBiogazFound->montant;
    $prixtotal = $dataCmdBiogazFound->prixtotal;

    $cmdBiogazData["commandeClients_idCommande"] = $cmdClientID;
    $today = getSiku();
    $cmdBiogazData["date"] = $today;
    $cmdBiogazData["siku"] = $today;
    $cmdBiogazData["etat_rapportID"] = ETAT_BON;
    $cmdBiogazData["natures_idNature"] = $natureID;
    $cmdBiogazData["quantite"] = $quantite;
    $cmdBiogazData["montant"] = $montant;
    $cmdBiogazData["prixtotal"] = $prixtotal;
    $cmdBiogazData["clients_idClient"] = $clientID;
    $cmdBiogazData["statusCmd_id"] = $statusCmdID;

    $cmdBiogazData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
    sortieBiogaz($cmdBiogazData);

    // $commandeClients->setStatusCmd_id($newStatusCmdID);
    // $commandeClients->setUpdated_at($today);

    $sortieID = getLastSortie($cmdBiogazData)->id;
    $commandeClients->setId_sortie($sortieID);
    $commandeClientsModel->update($cmdClientID, $commandeClients);
    // createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_ALIMENT);
    // $message = "Le Statut de Commande Aliment du client a été reglé";
    // return success200($message);
}
