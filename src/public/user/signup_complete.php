<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passcheck = $_POST["passcheck"];

    if (empty($email) || empty($password)) {
        $_SESSION["error_message"] = "EmailかPasswordの入力がありません";
        header("Location: signup.php");
        exit;
    } elseif ($password !== $passcheck) {
        $_SESSION["error_message"] = "パスワードが一致しません";
        header("Location: signup.php");
        exit;
    } else {
        $dbUserName = 'root';
        $dbPassword = 'password';
        $pdo = new PDO(
            'mysql:host=mysql; dbname=todo; charset=utf8',
            $dbUserName,
            $dbPassword
        );

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // パスワードをハッシュ化

            $stmt = $pdo->prepare("INSERT INTO users (
                name, email, password
            ) VALUES (
                :name, :email, :password
            )");
        
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR); // ハッシュ化されたパスワードをバインド
            $res = $stmt->execute();

            // 登録成功時の処理
            if ($res) {
                // 例として、登録完了後にユーザーをログインページにリダイレクトする
                header("Location: signin.php");
                exit;
            } else {
                $_SESSION["error_message"] = "エラーが発生しました。もう一度お試しください。";
                header("Location: signup.php");
                exit;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION["error_message"] = "すでに保存されているメールアドレスです";
            } else {
                $_SESSION["error_message"] = "エラーが発生しました。もう一度お試しください。";
            }
            header("Location: signup.php");
            exit;
        }
    }
} else {
    header("Location: signup.php");
    exit;
}
?>