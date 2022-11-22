<?php
// DB接続設定
$dsn = 'ホスト名';
$user = 'ユーザー名';
$password = 'パスワード';
try{
    $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
}catch(PDOException $e){
    exit("データベースへの接続に失敗しました:" .$e->getMessage());
}
?>