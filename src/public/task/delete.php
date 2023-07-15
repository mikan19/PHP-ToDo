<?php
session_start();

// データベース接続情報
$dbUserName = 'root';
$dbPassword = 'password';

// データベースへの接続を確立
$pdo = new PDO("mysql:host=mysql;dbname=todo;charset=utf8", $dbUserName, $dbPassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$taskId = $_POST['id'] ?? null;

$stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
$stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
$stmt->execute();

// 削除が成功した場合の処理
if ($stmt->rowCount() > 0) {
  header("Location: ../index.php");
  exit;
}



