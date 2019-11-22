<?php // データベース接続を確立
function db_connect()
{
    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーモードの設定 レポートを表示
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // prepareのエミュレーションを停止
        return $pdo;
    } catch (PDOException $e) {
        // エラー発生時
        exit("データベースの接続に失敗しました");
    }
}
