<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: *");
header("Access-Control-Allow-Methods: *");
include "./Crypt.php";

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

$username = $decodedData['uname'];
$pwd = $decodedData['pass'];
$password = CryptWare($pwd);



if ($username != null && $password != null) {
    $stmt = $db->prepare('INSERT INTO users (username,password) values (:username,:password)');
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password);

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
