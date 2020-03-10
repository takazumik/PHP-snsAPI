<?php
ini_set('display_errors', 1);

//jsonエンコードファンクション
function sendResponse($data) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Methods: *');
    echo json_encode($data);
}

//データベース接続と例外処理
try {
    $pdo = new PDO('mysql:dbname=php_sns;host=localhost;
    charset=utf8', 'root', 'root');
    // echo '接続できたよ';
} catch(PDOException $e) {
    print('DB接続エラー:' . $e->getMessage()); 
}

// jsonを受け取っている
$json = file_get_contents("php://input");

// jsonを解凍している
$signupData = json_decode($json, true);

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





