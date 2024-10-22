<?php
error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include('../Controllers/nature.php');
include('../Controllers/middleware.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($_SERVER["REQUEST_METHOD"] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

if ($requestMethod == "PUT") {
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isOnline() && accesAdmin()) {
        if (empty($inputData)) {
            $updateNature = updateNature($_POST, $_GET);
        } else {
            $updateNature = updateNature($inputData, $_GET);
        }

        echo $updateNature;
    }
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . " Method Not Allow",
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}