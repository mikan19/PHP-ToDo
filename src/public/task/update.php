<?php
session_start();

// エラーメッセージを格納する配列
$errors = [];

// カテゴリが選択されているかチェック
if (empty($_POST['categoryId'])) {
    $errors[] = 'カテゴリが選択されていません';
}

// タスク名が入力されているかチェック
if (empty($_POST['task'])) {
    $errors[] = 'タスク名が入力されていません';
}

// 締切日が入力されているかチェック
if (empty($_POST['deadline'])) {
    $errors[] = '締切日が入力されていません';
}

// エラーメッセージがある場合は表示して終了
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p class='text-red-600 mb-5 text-center'>$error</p>";
    }
    exit;
}

// タスク更新処理を実行
// 以下は実際の更新処理のコード
$categoryId = $_POST['categoryId'];
$task = $_POST['task'];
$deadline = $_POST['deadline'];

// 更新処理が完了したらindex.phpにリダイレクトする
header('Location: index.php');
exit;
?>