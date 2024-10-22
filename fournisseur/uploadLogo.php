<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../Controllers/fournisseur.php";
include "../Controllers/middleware.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($_SERVER["REQUEST_METHOD"] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

if ($requestMethod == "POST") {
    $inputData = json_decode(file_get_contents("php://input"), true);
    if (isOnline()) {
        if (empty($inputData)) {
            $logoFour = chargerLogo($_FILES['logo'], $_GET);
        } else {
            $logoFour = chargerLogo($inputData, $_GET);
        }
        echo $logoFour;
    }
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . " Method Not Allow",
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
