<?php

// ini_set('display_errors', 1);
// ini_set('error_reporting', E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json; charset=utf-8');

//jsonエンコードファンクション
function sendResponse($data)
{
    http_response_code(200);
    echo json_encode($data);
    exit();
}

//ルーティング
$method = strtoupper($_SERVER['REQUEST_METHOD']);
$url = urldecode($_SERVER['REQUEST_URI']);

// jsonを受け取っている
$json = file_get_contents("php://input");

$urlWithoutQueryParam = explode('?', $url)[0];
$router = explode("/", $urlWithoutQueryParam);

$urlQueryParam = explode('?', $url)[1];
$queryParam = explode("&", $urlQueryParam);
$minimumID01 = explode("=", $queryParam[0]);
$minimumID02 = explode("=", $queryParam[1]);
$minimumID03 = explode("=", $queryParam[2]);
$minimumID04 = explode("=", $queryParam[3]);


$page = intval($minimumID01[1]);
$limit = intval($minimumID02[1]);
$query = $minimumID03[1];

//データベース接続と例外処理
try {
    $pdo = new PDO('mysql:dbname=php_sns;host=localhost;
    charset=utf8', 'root', 'root');
} catch (PDOException $e) {
    print('DB接続エラー:' . $e->getMessage());
}

// tokenに紐づくユーザを取得する
function selectUsersByToken($pdo)
{
    $headers = getallheaders();
    $authorization = $headers['Authorization'];
    $tokenFromRequest = explode(' ', $authorization)[1]; // d0183...

    $sql = 'SELECT * FROM users WHERE token = :token';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $tokenFromRequest, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// okだったら何もしない。ngだったらエラーを返却してexit
function tokenCheck($pdo)
{
    $usersByToken = selectUsersByToken($pdo);
    if (0 === count($usersByToken)) {
        /* リクエストされたトークンが使われていない場合 */
        sendResponse('トークンが存在しないよ');
        exit();
    }
}

function tokenAndIdCheck($pdo, $userId)
{
    $usersByToken = selectUsersByToken($pdo);
    if (0 === count($usersByToken)) {
        sendResponse('トークンが存在しないよ');
    }
    $user = $usersByToken[0];
    if (intval($userId) !== intval($user['id'])) {
        sendResponse('トークンとuserIdの組み合わせが不正です');
    }
}

//空かどうかチェックする
function emptyCheck($json)
{
    $signupData = json_decode($json, true);

    // jsonをデコードした配列からデータを取り出し
    $name = $signupData['sign_up_user_params']['name'];
    $bio = $signupData['sign_up_user_params']['bio'];
    $email = $signupData['sign_up_user_params']['email'];
    $password = $signupData['sign_up_user_params']['password'];

    if (empty($name) || empty($bio) || empty($email) || empty($password)) {
        sendResponse('項目を入力して！！');
    }
}

//パスが確認用と合ってるかチェックする
function passCheck($json)
{
    $signupData = json_decode($json, true);
    $password = $signupData['sign_up_user_params']['password'];
    $password_confirmation = $signupData['sign_up_user_params']['password_confirmation'];

    if ($password !== $password_confirmation) {
        sendResponse('確認パスワードと一致しません！！');
    }
}

//新規登録
if ($router[2] === 'sign_up') {

    // jsonを解凍している
    $signupData = json_decode($json, true);

    // jsonをデコードした配列からデータを取り出し
    $name = $signupData['sign_up_user_params']['name'];
    $bio = $signupData['sign_up_user_params']['bio'];
    $email = $signupData['sign_up_user_params']['email'];
    $password = $signupData['sign_up_user_params']['password'];
    $password_confirmation = $signupData['sign_up_user_params']['password_confirmation'];

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    emptyCheck($json);
    passCheck($json);

    $secret = 'takazumi';
    $seed = $email . $password . $secret;
    $token = hash('sha256', $seed);


    //メアド重複チェック
    $checkSql = 'SELECT * FROM users WHERE email = :email';
    $stmt3 = $pdo->prepare($checkSql);
    $stmt3->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt3->execute();
    $result = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    if (0 !== count($result)) {
        sendResponse('アドレスが重複してる！！');
    }

    // SQL操作
    $sql = "INSERT INTO users (name, bio, email, password, token)";
    $sql .= " VALUES (:name, :bio, :email, :password, :token)";
    
    //準備
    $stmt = $pdo->prepare($sql);

    
    //bindpram
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashPassword, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);

    
    //execute
    $stmt->execute();

    $selectSql = 'SELECT * FROM users WHERE email = :email';

    $prepare = $pdo->prepare($selectSql);
    $prepare->bindParam(':email', $email, PDO::PARAM_STR);

    $prepare->execute();
    $result = $prepare->fetch(PDO::FETCH_ASSOC);

    sendResponse($result);
    // echo json_encode($prepare->fetch(PDO::FETCH_ASSOC));
    // return;
}

//ログイン
if ($router[2] === 'sign_in') {
    $signupData = json_decode($json, true);

    $email = $signupData['sign_in_user_params']['email'];
    $password = $signupData['sign_in_user_params']['password'];
    $password_confirmation = $signupData['sign_in_user_params']['password_confirmation'];
    
    if (empty($email) || empty($password) || empty($password_confirmation)) {
        sendResponse('項目を入力して！！');
    }

    if ($password !== $password_confirmation) {
        sendResponse('確認パスワードと一致しません！！');
    }

    $sql = 'SELECT * FROM users WHERE email = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!password_verify($password, $result[0]['password'])) {
        sendResponse('ログインに失敗！ざまあ！！！');
    }

    // 値を返す
    $sql = 'SELECT * FROM users WHERE email = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    sendResponse($stmt->fetch(PDO::FETCH_ASSOC));
}

//ユーザー一覧
if ($router[2] === 'users' && $method === 'GET') {
    // token check start ==========================================================
    tokenCheck($pdo);
    // token check end ============================================================

    if (!isset($query)) {
        /* query off */
        $sql = 'SELECT id,name,created_at,updated_at FROM users';
        $stmt = $pdo->prepare($sql);
    } else {
        /* query on */
        $sql = 'SELECT id,name,created_at,updated_at FROM users WHERE name LIKE :name';
        $stmt = $pdo->prepare($sql);
        $name = '%' . $query . '%';
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    if ($page === 0) {
        $page = 1;
    }
    if ($limit === 0) {
        $limit = 25;
    }
    $userResults = [];
    $start = 1 + $limit * ($page -1) -1;
    $end =  $limit * ($page -1) + $limit -1;
    for ($i = $start; $i <= $end && $i < count($result); $i++) {
        $userResults[] = $result[$i];
    }
    
    sendResponse($userResults);
}

//ユーザー削除
if ($router[2] === 'users' && $method === 'DELETE') {
    $id = $router[3];
    tokenAndIdCheck($pdo, $id);
    $sql = 'DELETE FROM users WHERE id = :id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);

    $stmt->execute();
    sendResponse('ID:'. $id . 'を削除しました');
}

//ユーザー編集
if ($router[2] === 'users' && $method === 'PUT') {
    $editData = json_decode($json, true);
    $name = $editData['user_params']['name'];
    $bio = $editData['user_params']['bio'];
    $id = $router[3];

    tokenAndIdCheck($pdo, $id);

    $sql = 'UPDATE users SET name = :name, bio = :bio WHERE id = :id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);

    $stmt->execute();
    sendResponse('ID:'. $id . 'を編集しました');
}

//タイムライン
if ($router[3] === 'timeline') {
    $timelineId = intval($router[2]);

    if (empty($timelineId) || $timelineId === 0) {
        sendResponse('IDを指定しろ！');
    }

    tokenCheck($pdo);

    if (!isset($query)) {
        $sql = 'SELECT * FROM posts WHERE user_id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $timelineId, PDO::PARAM_STR);
    } else {
        /* query on */
        $sql = 'SELECT * FROM posts WHERE message LIKE :message AND user_id = :id';
        $stmt = $pdo->prepare($sql);
        $query = '%' . $query . '%';
        $stmt->bindValue(':message', $query, PDO::PARAM_STR);
        $stmt->bindParam(':id', $timelineId, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($page === 0) {
        $page = 1;
    }
    if ($limit === 0) {
        $limit = 25;
    }
    $timelineResults = [];
    $start = 1 + $limit * ($page -1) -1;
    $end =  $limit * ($page -1) + $limit -1;
    for ($i = $start; $i <= $end && $i < count($result); $i++) {
        $timelineResults[] = $result[$i];
    }
    sendResponse($timelineResults);
}


//新規投稿
if ($router[2] === 'posts' && $method === 'POST') {
    // 認証
    tokenCheck($pdo);

    // トークンの持ち主を取得する
    $user = selectUsersByToken($pdo)[0];

    // jsonを解凍している
    $postData = json_decode($json, true);

    // jsonをデコードした配列からデータを取り出し
    $message = $postData['post_params']['text'];
    $user_id = $user['id'];

    // SQL操作
    $sql = 'INSERT INTO posts (message, user_id) VALUES (:message, :user_id)';

    //準備
    $stmt = $pdo->prepare($sql);

    //bindpram
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);

    //execute
    $ret = $stmt->execute();

    // 投稿後の処理
    $selectSql = 'SELECT * FROM posts ORDER BY date DESC LIMIT 1';
    $prepare = $pdo->prepare($selectSql);
    $prepare->execute();
    sendResponse($prepare->fetch(PDO::FETCH_ASSOC));
}
    
//投稿一覧
if ($router[2] === 'posts' && $method === 'GET') {
    tokenCheck($pdo);

    if (!isset($query)) {
        /* query off */
        $sql = 'SELECT * FROM posts';
        $stmt = $pdo->prepare($sql);
    } else {
        /* query on */
        $sql = 'SELECT * FROM posts WHERE message LIKE :message';
        $stmt = $pdo->prepare($sql);
        $query = '%' . $query . '%';
        $stmt->bindValue(':message', $query, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($page === 0) {
        $page = 1;
    }
    if ($limit === 0) {
        $limit = 25;
    }
    $a = [];
    $start = 1 + $limit * ($page -1) -1;
    $end =  $limit * ($page -1) + $limit -1;
    for ($i = $start; $i <= $end && $i < count($result); $i++) {
        unset($result[$i]['password']);
        unset($result[$i]['token']);
        $a[] = $result[$i];
    }
    sendResponse($a);
}

//投稿削除
if ($router[2] === 'posts' && $method === 'DELETE') {
    // 認証
    tokenCheck($pdo);

    // トークンの持ち主を取得する
    $user = selectUsersByToken($pdo)[0];
    $userIdOfToken = $user['id'];

    //ログインユーザーのトークン・IDと、入力IDのチェック
    $id = $router[3];
    $sql = 'SELECT * FROM posts WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $userIdOfPost = $post['user_id'];

    if ($userIdOfToken !== $userIdOfPost) {
        sendResponse('トークンとuserIdの組み合わせが不正です');
    }

    $sql = 'DELETE FROM posts WHERE id = :id';
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    sendResponse('ID:'. $id . 'の投稿を削除しました');
}

//投稿編集
if ($router[2] === 'posts' && $method === 'PUT') {

    // 認証
    tokenCheck($pdo);

    // トークンの持ち主を取得する
    $user = selectUsersByToken($pdo)[0];
    $userIdOfToken = $user['id'];

    $postData = json_decode($json, true);
    $message = $postData['post_params']['text'];
    $post_id = $router[3];

    $sql = 'SELECT * FROM posts WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    $userIdOfPost = $post['user_id'];

    if ($userIdOfToken === $userIdOfPost) {
        $sql = 'UPDATE posts SET message = :message WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
        $stmt->execute();

        $selectSql = 'SELECT * FROM posts WHERE id = :id';
        $stmt2 = $pdo->prepare($selectSql);
        $stmt2->bindParam(':id', $post_id, PDO::PARAM_INT);
        $stmt2->execute();
        sendResponse($stmt2->fetch(PDO::FETCH_ASSOC));
    }
    if ($userIdOfToken !== $userIdOfPost) {
        sendResponse('トークンとuserIdの組み合わせが不正です');
    }
}
