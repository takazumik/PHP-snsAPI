<?php

require_once('dbconnect.php');

//jsonエンコードファンクション
function sendResponse($data) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Methods: *');
    echo json_encode($data);
}

//ルーティング
$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];

$router = explode("/", $url);

if ($router[2] === 'sign_up') {    
    // jsonをデコードした配列からデータを取り出し
    $name = $signupData['sign_up_user_params']['name'];
    $bio = $signupData['sign_up_user_params']['bio'];
    $email = $signupData['sign_up_user_params']['email'];
    $password = $signupData['sign_up_user_params']['password'];
    
    // SQL操作
    $sql = "INSERT INTO users (name, bio, email, password)";
    $sql .= " VALUES (:name, :bio, :email, :password)";
    
    //準備
    $stmt = $pdo->prepare($sql);
    
    //bindprama
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    
    //execute
    $stmt->execute();

    sendResponse('success!');
    } else if ($router[2] === 'posts') {

    // jsonをデコードした配列からデータを取り出し
    $message = $postData['post_params']['text'];

    // SQL操作
    $sql = "INSERT INTO posts (message) VALUES (:message);

    //準備
    $stmt = $pdo->prepare($sql);

    //bindprama
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);

    //execute
    $stmt->execute();
    }