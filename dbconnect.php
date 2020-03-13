<?php
    //データベース接続と例外処理
    try {
        $pdo = new PDO('mysql:dbname=php_sns;host=localhost;
        charset=utf8', 'root', 'root');
    } catch (PDOException $e) {
        print('DB接続エラー:' . $e->getMessage());
    }


    function tokenCheck()
    {
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
    }
