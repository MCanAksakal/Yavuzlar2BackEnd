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

$taskId = $decodedData['taskId'];
$name = $decodedData['name'];
$status = $decodedData['status'];
$person = $decodedData['person'];
$date = date("d-m-Y");


if ($taskId != null) {
    $stmt = $db->prepare('UPDATE todos SET name = :name, status = :status , person = :person, date = :date WHERE id = :id');
    $stmt->bindValue(':id', $taskId);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':person', $person);
    $stmt->bindValue(':date', $date);

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
