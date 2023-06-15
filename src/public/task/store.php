<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category = $_POST["category_id"] ?? '';
    $taskName = $_POST["status"] ?? '';
    $date = $_POST["deadline"] ?? '';

    if (empty($category)) {
        $_SESSION["error_message"] = "カテゴリが選択されていません";
        header("Location: ../task/create.php");
        exit;
    }
    if (empty($taskName)) {
        $_SESSION["error_message"] = "タスク名が入力されていません";
        header("Location: ../task/create.php");
        exit;
    }
    if (empty($date)) {
        $_SESSION["error_message"] = "締切日が入力されていません";
        header("Location: ../task/create.php");
        exit;
    }

    // DB接続部分
    $dbUserName = 'root';
    $dbPassword = 'password';

    try {
        $pdo = new PDO("mysql:host=mysql;dbname=todo;charset=utf8", $dbUserName, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // tasksテーブルにタスクを追加
        $stmt_tasks = $pdo->prepare("INSERT INTO tasks (category_id, status, deadline) VALUES (:category_id, :status, :deadline)");
        $stmt_tasks->bindParam(':category_id', $category);
        $stmt_tasks->bindParam(':status', $taskName);
        $stmt_tasks->bindParam(':deadline', $date);
        $stmt_tasks->execute();

        // 登録成功後はindex.phpに遷移
        header("Location: ../index.php");
        exit;

    } catch (PDOException $e) {
        die("データベースへの接続に失敗しました: " . $e->getMessage());
    }
}
?>
