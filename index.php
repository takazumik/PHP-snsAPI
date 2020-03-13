<?php

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
$QueryParam = explode("&", $urlQueryParam);
$minimumID01 = explode("=", $QueryParam[0]);
$minimumID02 = explode("=", $QueryParam[1]);
$minimumID03 = explode("=", $QueryParam[2]);
$page = intval($minimumID01[1]);
$limit = intval($minimumID02[1]);
$query = $minimumID03[1];

// sendResponse($ID01);

//データベース接続と例外処理
try {
    $pdo = new PDO('mysql:dbname=php_sns;host=localhost;
    charset=utf8', 'root', 'root');
} catch (PDOException $e) {
    print('DB接続エラー:' . $e->getMessage());
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
    
    $secret = 'takazumi';
    $seed = $name . $password . $secret;
    $token = hash('sha256', $seed);

    // SQL操作
    $sql = "INSERT INTO users (name, bio, email, password, token)";
    $sql .= " VALUES (:name, :bio, :email, :password, :token)";
    
    //準備
    $stmt = $pdo->prepare($sql);

    
    //bindpram
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);

    
    //execute
    $stmt->execute();

    $selectSql = 'SELECT * FROM users WHERE email = :email';

    $prepare = $pdo->prepare($selectSql);
    $prepare->bindParam(':email', $email, PDO::PARAM_STR);

    $prepare->execute();

    echo json_encode($prepare->fetch(PDO::FETCH_ASSOC));
    return;
}

//ログイン
if ($router[2] === 'sign_in') {
    $signupData = json_decode($json, true);

    $email = $signupData['sign_in_user_params']['email'];
    $password = $signupData['sign_in_user_params']['password'];

    
    $sql = 'SELECT * FROM users WHERE email = :email AND password = :password';

    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

    $stmt->execute();
    // sendResponse('タイムラインです');
    sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
}

//ユーザー一覧
if ($router[2] === 'users') {
    if ($method === 'GET') {
        // token check start ==========================================================
        $headers = getallheaders();
        $authorization = $headers['Authorization'];
        $tokenFromRequest = explode(' ', $authorization)[1]; // d0183...

        $sql = 'SELECT * FROM users WHERE token = :token';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $tokenFromRequest, PDO::PARAM_STR);
        $stmt->execute();

        $resultFetchAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $isValidToken = (1 === count($resultFetchAll)); // tokenが使われていればtrue

        if (false === $isValidToken) {
            /* リクエストされたトークンが使われていない場合 */
            // TODO:tokenが存在しないときにエラーメッセージを返す
            sendResponse('トークンが存在しないよ');
            exit();
        }
        /* リクエストされたトークンが有効な場合 */
        // token check end ============================================================

        if (!isset($query)) {
            /* query off */
            $sql = 'SELECT * FROM users';
            $stmt = $pdo->prepare($sql);
        } else {
            /* query on */
            $sql = 'SELECT * FROM users WHERE name LIKE :name';
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
        $a = [];
        $start = 1 + $limit * ($page -1) -1;
        $end =  $limit * ($page -1) + $limit -1;
        for ($i = $start; $i <= $end && $i < count($result); $i++) {
            $a[] = $result[$i];
        }
        sendResponse($a);


        // TODO: limit page の計算をPHPでがんばる
    }
}
    if //ユーザー削除
    ($method === 'DELETE') {
        $id = $router[3];
        $sql = 'DELETE FROM users WHERE id = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);

        $stmt->execute();
        sendResponse('ID:'. $id . 'を削除しました');
    }

    //タイムライン
    if ($router[3] === 'timeline') {
        $sql = 'SELECT * FROM users';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        // sendResponse('タイムラインです');
        sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }


//新規投稿
if ($router[2] === 'posts') {
    if ($method === 'POST') {
        // token check start ==========================================================
        $headers = getallheaders();
        $authorization = $headers['Authorization'];
        $tokenFromRequest = explode(' ', $authorization)[1]; // d0183...

        $sql = 'SELECT * FROM users WHERE token = :token';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $tokenFromRequest, PDO::PARAM_STR);
        $stmt->execute();

        $resultFetchAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $isValidToken = (1 === count($resultFetchAll)); // tokenが使われていればtrue

        if (false === $isValidToken) {
            /* リクエストされたトークンが使われていない場合 */
            // TODO:tokenが存在しないときにエラーメッセージを返す
            sendResponse('トークンが存在しないよ');
            exit();
        }
        /* リクエストされたトークンが有効な場合 */
        // token check end ============================================================

        $user = $resultFetchAll[0];

        // sendResponse([
        //     '$user' => $user,
        //     '$user[\'id\']' => $user['id'],
        // ]);

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

        sendResponse([
            'msg' => 'トークンが有効だった',
            'json' => $json,
            'router' => $router,
            'headers' => $headers,
            'message' => $message,
            'ret' => $ret,
        ]);

        // 投稿後の処理
        $selectSql = 'SELECT * FROM posts ORDER BY date DESC LIMIT 1';
        $prepare = $pdo->prepare($selectSql);
        $prepare->execute();
        echo json_encode($prepare->fetch(PDO::FETCH_ASSOC));
    } //投稿一覧
    if ($method === 'GET') {
        $sql = 'SELECT * FROM posts';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
    } //投稿削除
    if ($method === 'DELETE') {
        $headers = getallheaders();
        $authorization = $headers['Authorization'];
        $tokenFromRequest = explode(' ', $authorization)[1]; // d0183...

        $sql = 'SELECT * FROM users WHERE token = :token';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $tokenFromRequest, PDO::PARAM_STR);
        $stmt->execute();

        $resultFetchAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $isValidToken = (1 === count($resultFetchAll)); // tokenが使われていればtrue

        if (false === $isValidToken) {
            /* リクエストされたトークンが使われていない場合 */
            // TODO:tokenが存在しないときにエラーメッセージを返す
            sendResponse('トークンが存在しないよ');
            exit();
        }

        $id = $router[3];
        $sql = 'DELETE FROM posts WHERE id = :id';
        

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);

        $stmt->execute();
        sendResponse('ID:'. $id . 'の投稿を削除しました');
    } //投稿編集
    if ($method === 'PUT') {
        $user = $resultFetchAll[0];
        $user_id = $user['id'];

        $postData = json_decode($json, true);

        $message = $postData['post_params']['text'];
            
        // SQL操作
        $sql = 'UPDATE SET message = :message WHERE ';
        
        //準備
        $stmt = $pdo->prepare($sql);
        
        //bindpram
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
            
        //execute
        $stmt->execute();
        
        //未定義
        $selectSql = 'SELECT FROM posts ORDER BY posts.date DESC LIMIT 1';
        $prepare = $pdo->prepare($selectSql);
        $prepare->execute();
        echo json_encode($prepare->fetch(PDO::FETCH_ASSOC));
    }
}
