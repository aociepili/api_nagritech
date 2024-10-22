<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('..\Autoloader.php');
include('controllers.php');

use App\Autoloader;
use App\Models\Reservation_cfModel;

Autoloader::register();

# Store
function storeReservationCF($reservationCFData)
{

    $reservationCFModel = new Reservation_cfModel();
    $reservationCF = $reservationCFModel;

    # On recupere les informations venues de POST
    chargementReservationCF($reservationCFData);

    $dateEntree = $reservationCFData['dateEntree'];
    $dateFin = $reservationCFData['dateFin'];
    $dateSortie = $reservationCFData['dateSortie'];
    $libelle = $reservationCFData['libelle'];
    $detail = $reservationCFData['detail'];
    $status = "En cours ...";
    $clientID = $reservationCFData['clients_idClient'];
    $today = getSiku();

    $testClient = testClientbyId($clientID);

    if ($testClient) {
        $reservationCF->setDateEntree($dateEntree);
        $reservationCF->setDateFin($dateFin);
        $reservationCF->setDateSortie($dateSortie);
        $reservationCF->setLibelle($libelle);
        $reservationCF->setDetail($detail);
        $reservationCF->setStatus($status);
        $reservationCF->setClients_idClient($clientID);
        $reservationCF->setCreated_at($today);
        # On ajoute la Designation dans la BD
        $reservationCFModel->create($reservationCF);

        $message = "Reservation CF  created successfully";
        return success201($message);
    }
}

#Delete
function deleteReservationCF($reservationCFParams)
{
    $reservationCFModel = new Reservation_cfModel();
    paramsVerify($reservationCFParams, "Reservation Chambre Froide");

    $reservationCFID = $reservationCFParams['id'];
    $reservationCFData = $reservationCFModel->find($reservationCFID);

    if ($reservationCFID == $reservationCFData->id) {
        $res = $reservationCFModel->delete($reservationCFID);
        $message = "Reservation CF deleted successfully";
        return success200($message);
    } else {
        $message = "Reservation CF not Found  ";
        return error405($message);
    }
}

#Get
function getReservationCFById($reservationCFParams)
{
    $reservationCFModel = new Reservation_cfModel();
    paramsVerify($reservationCFParams, "Reservation Chambre Froide");
    $reservationCFFound = $reservationCFModel->find($reservationCFParams['id']);

    if (!empty($reservationCFFound)) {
        $dataRCF = getReservationCFDataById($reservationCFFound->id);
        $message = "Reservation CF Fetched successfully";
        return datasuccess200($message, $dataRCF);
    } else {
        $message = "No Reservation CF Found";
        return success205($message);
    }
}

function getListReservationCF()
{
    $reservationCFModel = new Reservation_cfModel();
    $reservationCF = (array)$reservationCFModel->findAll();

    if (!empty($reservationCF)) {
        $dataListReservation = getListReservationCFData($reservationCF);
        $message = "Situation Reservation Chambre Froide";
        return dataTableSuccess200($message, $dataListReservation);
    } else {
        $message = "Pas de situation de la chambre Froide";
        return success205($message);
    }
}

# Update
function updateReservationCF($reservationCFData, $reservationCFParams)
{
    $reservationCFModel = new Reservation_cfModel();
    $reservationCF = $reservationCFModel;
    paramsVerify($reservationCFParams, "Reservation Chambre Froide");

    # On recupere les informations venues de POST
    $reservationCFID = $reservationCFParams['id'];
    $dateEntree = $reservationCFData['dateEntree'];
    $dateFin = $reservationCFData['dateFin'];
    $dateSortie = $reservationCFData['dateSortie'];
    $libelle = $reservationCFData['libelle'];
    $detail = $reservationCFData['detail'];
    // $status = "En cours ...";
    $clientID = $reservationCFData['clients_idClient'];
    $today = getSiku();

    $testClient = testClientbyId($clientID);

    if ($testClient) {
        $reservationCF->setDateEntree($dateEntree);
        $reservationCF->setDateFin($dateFin);
        $reservationCF->setDateSortie($dateSortie);
        $reservationCF->setLibelle($libelle);
        $reservationCF->setDetail($detail);
        // $reservationCF->setStatus($status);
        $reservationCF->setClients_idClient($clientID);
        $reservationCF->setUpdated_at($today);

        $reservationCFFound = $reservationCFModel->find($reservationCFID);

        if ($reservationCFID == $reservationCFFound->id) {
            $reservationCFModel->update($reservationCFID, $reservationCF);
            # On ajoute l'Adresse  dans la BD
            $message = "Reservation CF updated successfully";
            return success200($message);
        } else {
            $message = "No Reservation CF Found ";
            return success205($message);
        }
    }
}

function getReservationCFByClientID($clientID)
{
    #rechercher de l'ID de l'adresse
    $test = 0;
    $DataEntree = array(
        "clients_idClient" => $clientID,
    );
    $reservationCFModel = new Reservation_cfModel();
    $dataSP = (object)$reservationCFModel->findBy($DataEntree);

    if ($clientID == $dataSP->clients_idClient) {
        $test = 1;
    }
    return $test;
}
