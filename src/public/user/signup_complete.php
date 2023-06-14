<?php
$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$passcheck = $_POST["passcheck"];

session_start();
if (empty($email) || empty($password)) {
    $_SESSION["error_message"] = "EmailかPasswordの入力がありません";
    header("Location: signup.php");
    exit;
} 

if ($password !== $passcheck) {
    $_SESSION["error_message"] = "パスワードが一致しません";
    header("Location: signup.php");
    exit;
}

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=todo; charset=utf8',
    $dbUserName,
    $dbPassword
);

$sqlSerectUsersByEmail = 'select * from users where email = :email';
$statement = $pdo->prepare($sqlSerectUsersByEmail);
$statement->bindValue(':email', $email, PDO::PARAM_STR);
$statement->execute();
$user = $statement->fetch();

if ($user) {
    $_SESSION['error_message'] = 'すでに登録済みのメールアドレスです';
    header('Location: ./signup.php');
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // パスワードをハッシュ化
$sqlInsertUsers = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
$statement = $pdo->prepare($sqlInsertUsers);
$statement->bindParam(':name', $name, PDO::PARAM_STR);
$statement->bindParam(':email', $email, PDO::PARAM_STR);
$statement->bindParam(':password', $hashedPassword, PDO::PARAM_STR); // ハッシュ化されたパスワードをバインド
$statement->execute();

header('Location: signin.php');
exit();