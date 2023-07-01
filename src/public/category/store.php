<?php
session_start();

// データベース接続情報
$dbUserName = 'root';
$dbPassword = 'password';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['keyword'])) {
        $categoryName = $_GET['keyword'];

        if (empty($categoryName)) {
            $_SESSION['error'] = 'カテゴリー名が入力されていません';
            header("Location:index.php");
            exit();
        }

        try {
            // データベースへの接続を確立
            $pdo = new PDO("mysql:host=mysql;dbname=todo;charset=utf8", $dbUserName, $dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // ログインしているユーザーのIDを取得
            $userId = $_SESSION["user_id"];

            // データベースに保存
            $stmt = $pdo->prepare("INSERT INTO categories (name, user_id) VALUES (:name, :user_id)");
            $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // カテゴリー追加成功のメッセージをセッションに保存
            $_SESSION['success'] = 'カテゴリーを追加しました';
            header("Location:index.php");
            exit();
        } catch (PDOException $e) {
            die("データベースへの接続に失敗しました: " . $e->getMessage());
        }
    }
}
