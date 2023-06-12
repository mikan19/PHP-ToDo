<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $_SESSION["error_message"] = "パスワードとメールアドレスを入力してください";
        header("Location: signin.php");
        exit;
    } else {
        $dbUserName = 'root';
        $dbPassword = 'password';

        try {
            $pdo = new PDO("mysql:host=mysql;dbname=todo;charset=utf8", $dbUserName, $dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {
                // パスワードの検証が成功した場合
                $_SESSION["user_id"] = $user["id"]; // ユーザーIDをセッションに保存
                header("Location:../index.php");
                exit;
            } else {
                $_SESSION["error_message"] = "メールアドレスまたはパスワードが正しくありません。";
                header("Location: signin.php");
                exit;
            }
        } catch (PDOException $e) {
            die("データベースへの接続に失敗しました: " . $e->getMessage());
        }
    }
}




