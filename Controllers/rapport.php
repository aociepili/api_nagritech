<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');

use App\Autoloader;

Autoloader::register();

#Get
function getListRapportYear($rapportParams)
{
    paramsVerifyYear($rapportParams, "Rapport Aliment");
    $rapports = getListRapportDataYear($rapportParams);
    $year  = $rapportParams['year'];

    if (!empty($rapports)) {

        $message = "Situation Rapport de l'annee" . $year;
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $rapports);
    } else {
        $message = "Pas de situation dans le Rapport";
        return success205($message);
    }
}

function getListRapportMonth()
{
    $listData = getListRapportDataMonth();

    if (!empty($listData)) {
        $message = "Situation Rapport de ce Mois";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport";
        return success205($message);
    }
}

function getListRapportWeek()
{
    $listData = getListRapportDataWeek();
    if (!empty($listData)) {

        $message = "Situation Rapport de la semaine";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport ";
        return success205($message);
    }
}

function getListRapportDay()
{
    $listData = getListRapportDataDay();
    if (!empty($listData)) {
        $message = "Situation Rapport de jour" . getSiku();
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport";
        return success205($message);
    }
}

function getListRapport()
{
    $listData = getRapportData();
    if (!empty($listData)) {
        $message = "Situation Rapport";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans Rapport";
        return success205($message);
    }
}
function getListRapportByAgent()
{
    $idAgent = getAgentOnlineID();
    $listData = getRapportDataByAgentID($idAgent);
    if (!empty($listData)) {
        $message = "Situation Rapport";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans Rapport";
        return success205($message);
    }
}

function getListRapportDayByAgent()
{
    $idAgent = getAgentOnlineID();
    $listData = getListRapportDataDayByAgentID($idAgent);
    if (!empty($listData)) {
        $message = "Situation Rapport de jour" . getSiku();
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport";
        return success205($message);
    }
}

function getListRapportWeekByAgent()
{
    $idAgent = getAgentOnlineID();
    $listData = getListRapportDataWeekByAgentID($idAgent);
    if (!empty($listData)) {

        $message = "Situation Rapport de la semaine";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport ";
        return success205($message);
    }
}

function getListRapportMonthByAgentID()
{
    $idAgent = getAgentOnlineID();
    $listData = getListRapportDataMonthByAgentID($idAgent);

    if (!empty($listData)) {
        $message = "Situation Rapport de ce Mois";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $listData);
    } else {
        $message = "Pas de situation dans le Rapport";
        return success205($message);
    }
}

function getListRapportYearByAgentID($rapportParams)
{
    $idAgent = getAgentOnlineID();
    paramsVerifyYear($rapportParams, "Annee");
    $rapports = getListRapportDataYearByAgentID($rapportParams, $idAgent);
    $year  = $rapportParams['year'];

    if (!empty($rapports)) {

        $message = "Situation Rapport de l'annee" . $year;
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_RAPPORT);
        return dataTableSuccess200($message, $rapports);
    } else {
        $message = "Pas de situation dans le Rapport";
        return success205($message);
    }
}