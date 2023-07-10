<?php
session_start();

// データベース接続情報
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql;dbname=todo;charset=utf8',
    $dbUserName,
    $dbPassword
);

// リクエストパラメータからタスクIDと完了状態を取得
$taskId = $_GET['id'];
$status = $_GET['status'];

try {
    // タスクの完了状態を更新するクエリを準備
    $stmt = $pdo->prepare("UPDATE tasks SET status = :status WHERE id = :task_id");
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    $stmt->execute();

    // タスク一覧ページにリダイレクト
    header("Location: ../index.php");
    exit();
} catch (PDOException $e) {
    die("データベースの更新に失敗しました: " . $e->getMessage());
}
?>
