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

$name = $decodedData['name'];
$person = $decodedData['person'];
$status = $decodedData['status'];
$date = date("d-m-Y");


if ($name != null && $status != null && $person != null) {
    $stmt = $db->prepare('INSERT INTO todos (name,status,date,person) values (:name,:status,:date,:person)');
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':person', $person);

    if ($stmt->execute()) {
        $message = "Success";
        $response = array($message);
        echo json_encode($response);
        exit();
    } else {
        $message = "Failure";
        $response = array($message);
        echo json_encode($response);
        exit();
    }
}
