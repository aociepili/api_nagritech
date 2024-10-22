<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('..\Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Reservation_pouletabattageModel;

Autoloader::register();

# Store
function storeReservationPoulet($reservationPouletData)
{
    $reservationPouletModel = new Reservation_pouletabattageModel();
    $reservationPoulet = $reservationPouletModel;

    # On recupere les informations venues de POST
    chargementReservationPoulet($reservationPouletData);

    $today = getSiku();
    $quantite = $reservationPouletData['quantite'];
    $reservationPouletData['date'] = $today;
    $date = $reservationPouletData['date'];
    $clientID = $reservationPouletData['clients_idClient'];

    $testClient = testClientbyId($clientID);
    if ($testClient) {
        $reservationPoulet->setQuantite($quantite);
        $reservationPoulet->setDate($date);
        $reservationPoulet->setClients_idClient($clientID);
        $reservationPoulet->setCreated_at($today);

        # On ajoute la Designation dans la BD
        $reservationPouletModel->create($reservationPoulet);
        $message = "Reservation Poulet  created successfully";
        return success201($message);
    }
}

#Delete
function deleteReservationPoulet($reservationPouletParams)
{
    $reservationPouletModel = new Reservation_pouletabattageModel();
    paramsVerify($reservationPouletParams, "Reservation Poulet");

    $reservationPouletID = $reservationPouletParams['id'];
    $reservationPouletData = $reservationPouletModel->find($reservationPouletID);

    if ($reservationPouletID == $reservationPouletData->id) {
        $res = $reservationPouletModel->delete($reservationPouletID);
        $message = "Reservation Poulet deleted successfully";
        return success200($message);
    } else {
        $message = "Reservation Poulet not delete  ";
        return error405($message);
    }
}

#Get
function getReservationPouletById($reservationPouletParams)
{
    $reservationPouletModel = new Reservation_pouletabattageModel();
    paramsVerify($reservationPouletParams, "Reservation Poulet");
    $reservationPouletFound = $reservationPouletModel->find($reservationPouletParams['id']);

    if (!empty($reservationPouletFound)) {
        $dataRP = getReservationPDataById($reservationPouletFound->id);
        $message = "Reservation Poulet Fetched successfully";
        return datasuccess200($message, $dataRP);
    } else {
        $message = "No Reservation Poulet Found";
        return success205($message);
    }
}

function getListReservationPoulet()
{
    $reservationPouletModel = new Reservation_pouletabattageModel();
    $reservationPoulet = (array)$reservationPouletModel->findAll();

    if (!empty($reservationPoulet)) {
        $dataListRP = getListReservationPouletData($reservationPoulet);
        $message = "Situation Reservation Poulet";
        return dataTableSuccess200($message, $dataListRP);
    } else {
        $message = "Pas de situation de Poulet";
        return success205($message);
    }
}

# Update
function updateReservationPoulet($reservationPouletData, $reservationPouletParams)
{
    $reservationPouletModel = new Reservation_pouletabattageModel();
    $reservationPoulet = $reservationPouletModel;
    paramsVerify($reservationPouletParams, "Reservation Poulet");

    # On recupere les informations venues de POST
    $reservationPouletID = $reservationPouletParams['id'];
    $quantite = $reservationPouletData['quantite'];
    $date = $reservationPouletData['date'];
    $clientID = $reservationPouletData['clients_idClient'];
    $today = getSiku();

    $testClient = testClientbyId($clientID);
    if ($testClient) {
        $reservationPoulet->setQuantite($quantite);
        $reservationPoulet->setDate($date);
        $reservationPoulet->setClients_idClient($clientID);
        $reservationPoulet->setUpdated_at($today);

        $reservationPouletFound = $reservationPouletModel->find($reservationPouletID);

        if ($reservationPouletID == $reservationPouletFound->id) {
            $reservationPouletModel->update($reservationPouletID, $reservationPoulet);
            # On ajoute l'Adresse  dans la BD
            $message = "Reservation Poulet updated successfully";
            return success200($message);
        } else {
            $message = "No Reservation poulet Found ";
            return error404($message);
        }
    }
}

function getReservationPouletByClientID($clientID)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "clients_idClient" => $clientID,
    );
    $reservationPouletModel = new Reservation_pouletabattageModel();
    $dataSP = (object)$reservationPouletModel->findBy($DataEntree);

    if ($clientID == $dataSP->clients_idClient) {
        $test = 1;
    }
    return $test;
}