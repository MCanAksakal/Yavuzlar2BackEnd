<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: *");
header("Access-Control-Allow-Methods: *");

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('../Database');
    }
}

$db = new MyDB();

$encodedData = file_get_contents('php://input');
$decodedData = json_decode($encodedData, true);

$table = $_POST['table'];



$stmt = $db->prepare('SELECT username FROM users;');

$result = $stmt->execute();
if ($result) {
    $data = array();
    while ($res = $result->fetchArray()) {
        array_push($data, $res);
    }
    if (!$res) {
        $message = "Success";
        $response = array($message, $data);
        echo json_encode($response);
        exit();
    } else {
        $message = "Failure";
        $response = array($message);
        echo json_encode($response);
        exit();
    }
} else {
    $message = "Failure";
    $response = array($message);
    echo json_encode($response);
    exit();
}
