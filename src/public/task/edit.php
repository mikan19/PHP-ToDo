<?php
session_start();
// エラーメッセージがセッションに保存されている場合は取得する
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

// セッションからエラーメッセージを削除する
unset($_SESSION['errors']);

// データベース接続情報
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=todo; charset=utf8',
    $dbUserName,
    $dbPassword
);

try {
    $pdo = new PDO("mysql:host=mysql;dbname=todo;charset=utf8", $dbUserName, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ログインしているユーザーのカテゴリーデータを取得
    $userId = $_SESSION["user_id"];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("データベースへの接続に失敗しました: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200 w-full h-screen flex justify-center items-center">
    <div class="bg-white pt-10 pb-10 px-10 rounded-xl">
        <div>
            <?php foreach ($errors as $error): ?>
            <p class="text-red-600 mb-5 text-center"><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>

        <form action="./update.php" method="POST">
            <select name="categoryId">
                <option value="">カテゴリを選んでください</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input class='border-2 border-gray-300' type="text" name="task" placeholder="タスクを追加">
            <input class='border-2 border-gray-300' type="date" name="deadline">
            <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mb-5'
                type="submit">更新</button>
        </form>
        <a class="text-blue-600" href="../index.php">戻る</a>
    </div>
</body>

</html>