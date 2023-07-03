<?php
session_start();

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    echo '<p>' . $error . '</p>';
    unset($_SESSION['error']);
}



// データベース接続情報
$dbUserName = 'root';
$dbPassword = 'password';

try {
    // データベースへの接続を確立
    $pdo = new PDO("mysql:host=mysql;dbname=todo;charset=utf8", $dbUserName, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ログインしているユーザーのIDを取得
    $userId = $_SESSION["user_id"];

    if (!empty($keyword)) {
        $stmt = $pdo->prepare("SELECT tasks.id, tasks.contents, tasks.deadline, tasks.category_id, categories.name FROM tasks INNER JOIN categories ON tasks.category_id = categories.id WHERE tasks.user_id = :user_id AND (tasks.contents LIKE :keyword OR categories.name LIKE :keyword)");
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
    } else {
        $stmt = $pdo->prepare("SELECT tasks.id, tasks.contents, tasks.deadline, categories.id AS category_id, categories.name FROM tasks INNER JOIN categories ON tasks.category_id = categories.id WHERE tasks.user_id = :user_id ");
    }

    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // カテゴリのデータを取得
    $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <link href="../style.css" rel="stylesheet" type="text/css">
  <title>Document</title>
  <base href="http://localhost:8080/">
</head>
<body>
<header>
      <h2>Todoアプリ</h2>
      <nav class="nav-top">
        <ul>
          <li><a class="nav-menu" href="index.php">ホーム</a></li>
          <li><a class="nav-menu" href="category/index.php">カテゴリ一覧</a></li>
          <li><a class="nav-menu" href="user/logout.php">ログアウト</a></li>
        </ul>
      </nav>
  </header>
  <h3>カテゴリ一覧</h3>
  <main>

   
    <div class="sarchform">
      <form method="GET" action="category/store.php">
        <input type="text" name="keyword" placeholder="カテゴリー追加">
        <button type="submit">追加</button>
      </form>
    </div>

   
      <tbody>
        <?php foreach ($todos as $todo): ?>
          <tr class="ctegory">
            
            <td><?php echo $todo['name']; ?></td>

            <td>
              <a href="category/edit.php?id=<?php echo $todo['category_id']; ?>"><button type="submit">編集</button></a>
              <a href="category/delete.php?id=<?php echo $todo['category_id']; ?>"><button type="submit">削除</button></a>
            </td>


          </tr>



        <?php endforeach; ?>
      </tbody>
    </table>
  </main>
</body>
</html>



  <main>
    <a href="./task/create.php">戻る</a>
  </main>
</body>