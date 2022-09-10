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

function RandomToken($length)
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

if ($username != null && $password != null) {
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username AND password = :password;');
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password);

    $result = $stmt->execute();
    if ($result) {
        $rows = $result->fetchArray();
        if ($rows != false) {
            $stmt1 = $db->prepare('UPDATE users SET token = :token WHERE username = :username AND password = :password');
            $stmt1->bindValue(':username', $username);
            $stmt1->bindValue(':password', $password);
            $stmt1->bindValue(':token', RandomToken(36));
            $result1 = $stmt1->execute();
            if ($result1) {
                $stmt2 = $db->prepare('SELECT * FROM users WHERE username = :username AND password = :password;');
                $stmt2->bindValue(':username', $username);
                $stmt2->bindValue(':password', $password);
                $result2 = $stmt2->execute();
                if ($result2) {
                    $rows2 = $result2->fetchArray();
                    if ($rows2 != false) {
                        $message = "Success";
                        $response = array($message, $rows2);
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
    } else {
        $message = "Failure";
        $response = array($message);
        echo json_encode($response);
        exit();
    }
}
