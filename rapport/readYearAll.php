<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include('../Controllers/rapport.php');
include('../Controllers/middleware.php');
$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($_SERVER["REQUEST_METHOD"] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

if ($requestMethod == "GET") {
    if (isset($_GET['year'])) {
        if (isOnline()) {
            $rapport_poussins = getListRapportYear($_GET);
            echo $rapport_poussins;
        }
    } else {
        $data = [
            'status' => 422,
            'message' => "Enter your Rapport Year",
        ];
        header("HTTP/1.0 422 Unprocessable Entity");
        echo json_encode($data);
    }
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . " Method Not Allow",
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}