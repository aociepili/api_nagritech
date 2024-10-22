<?php
function getSiku()
{
    date_default_timezone_set('Africa/Kigali');
    $today = date("Y-m-d H:i:s");
    return $today;
}

function debug400($message, $donnees)
{
    $data = [
        'status' => 400,
        'message' => $message,
        'data' => $donnees

    ];
    header("HTTP/1.0 400 Bad Request");
    echo json_encode($data);
    exit();
}
function debug200($message, $donnees1, $donnees)
{
    $data = [
        'status' => 200,
        'message' => $message,
        'data1' => $donnees1,
        'data' => $donnees

    ];
    header("HTTP/1.0 200 okay");
    echo json_encode($data);
    exit();
}

function generateNumber()
{
    #generation d'un nombre 9 et 100000000
    $nbreMax = 10000;
    $nbreMin = 9;
    $nbre = rand($nbreMin, $nbreMax);
    return $nbre;
}

#Verification de parametre ID
function paramsVerify($params, $designation)
{
    if (!isset($params['id'])) {
        return error422($designation . "ID not found in URL");
    } elseif ($params['id'] == null) {
        return error422("Enter the " . $designation . "  ID");
    }
}
function chiffreVerify($montant, $designation)
{
    if (!($montant >= 0)) {
        return error422(" le {$designation} de la Commande est negatif ");
    }
}
function statusCmdVerify($statusCmdID)
{
    if (!in_array($statusCmdID, STATUS_CMD)) {
        return error422("Ce Statut de Commande n'existe pas ");
    }
}


#Verification de parametre Year
function paramsVerifyYear($params, $designation)
{
    if (!isset($params['year'])) {
        return error422($designation . " Year not found in URL");
    } elseif ($params['year'] == null) {
        return error422("Veuillez renseigner pour une information sur " . $designation);
    } else {
        if (!preg_match("#^(20)[0-9]{2}$#", $params['year'])) {
            return error422("Veuillez entree une information valide sur " . $designation);
        }
    }
}

// function natureVerify($natureID, $designation)
// {
//     $aliment = 0;
//     $biogaz = 0;
//     $oeuf = 0;
//     $poussin = 0;
//     $poule = 0;
//     $poulet = 0;


//     switch ($designation) {
//         case DESIGN_ALIMENT: {

//                 if ($natureID != ALIMENT) {

//                     return error422("La nature du produit n'est pas " . $designation);
//                 }
//                 break;
//             }

//         case DESIGN_BIOGAZ: {
//                 if (!in_array($natureID, BIOGAZ)) {
//                     return error422("La nature du produit n'est pas " . $designation);
//                 }
//                 break;
//             }
//         case DESIGN_POUSSIN: {
//                 if (!in_array($natureID, POUSSIN)) {
//                     return error422("La nature du produit n'est pas " . $designation);
//                 }
//                 break;
//             }
//         case DESIGN_OEUF: {
//                 if (!in_array($natureID, OEUF)) {
//                     return error422("La nature du produit n'est pas " . $designation);
//                 }
//                 break;
//             }
//     }
// }

function moisFin()
{
    // include_once '../../core/Data.php';
    $formatToday = formatToday();
    $formatFin = $formatToday - MOIS;
    return $formatFin;
}
function semaineFin()
{
    // include_once '../../core/Data.php';
    $formatToday = formatToday();
    $formatFin = $formatToday - SEMAINE;
    return $formatFin;
}
function journee()
{   #Dans le 24 heures
    //include_once '../../core/Data.php';
    $formatToday = formatToday();
    $formatFin = $formatToday - JOUR;
    return $formatFin;
}

function formatToday()
{
    $today = getSiku();
    $formatToday = strtotime($today);
    return $formatToday;
}

#INCUBATION

function dateIncubation($dateEntree)
{
    // include_once '../../core/Data.php';
    $formatDateEntree = strtotime($dateEntree);
    $formatFinIncubation = $formatDateEntree + INCUBATION;
    $dateFinIncubation = date('Y-m-d H:i:s', $formatFinIncubation);
    return $dateFinIncubation;
}

function jourIntervalleDate($date)
{
    $formatDate = strtotime($date);
    $formatToday = strtotime(getSiku());

    $diff =   $formatToday - $formatDate;
    $jour = floor($diff / 86400);
    // $datePrevue = new DateTime($date);
    // $Today = new DateTime(getSiku());
    // $intervalle = $Today->diff($datePrevue);
    // $jour = $intervalle->format('%a');
    return $jour;
}

function changeAttribut($data, $designation_table)
{
    $dataKey = array_keys($data);
    $dataValues = array_values($data);
    foreach ($dataKey as $key => $value) {
        if ($value == "id") {
            $dataKey[$key] = $designation_table . "_id";
        } elseif ($value == "designation") {
            $dataKey[$key] = $designation_table . "_designation";
        } elseif ($value == "abrege") {
            $dataKey[$key] = $designation_table . "_abrege";
        }
    }
    $data = array_combine($dataKey, $dataValues);
    return $data;
}
function changeAttributRapport($data, $designation_table)
{
    $dataKey = array_keys($data);
    $dataValues = array_values($data);
    $rapportID = $data['id'];
    $dataRapport = array(
        "rapport_id" => $rapportID,
    );
    foreach ($dataKey as $key => $value) {
        if ($value == "id") {
            $dataKey[$key] = $designation_table . "_id";
        } elseif ($value == "designation") {
            $dataKey[$key] = $designation_table . "_designation";
        } elseif ($value == "abrege") {
            $dataKey[$key] = $designation_table . "_abrege";
        }
    }
    $data = array_combine($dataKey, $dataValues);
    $rapportData = array_merge((array)$dataRapport, (array)$data);
    return $rapportData;
}

function reduireChiffre($nombre)
{
    $chiffre = floor($nombre * 1000) / 1000;
    return $chiffre;
}