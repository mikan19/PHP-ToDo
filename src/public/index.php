<?php
session_start();

// データベース接続情報
$dbUserName = 'root';
$dbPassword = 'password';


  // データベースへの接続を確立
  $pdo = new PDO("mysql:host=mysql;dbname=todo;charset=utf8", $dbUserName, $dbPassword);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // ログインしているユーザーのIDを取得
  $userId = $_SESSION["user_id"];

  // ユーザー名を取得するクエリを準備
  $stmt = $pdo->prepare("SELECT name FROM users WHERE id = :user_id");
  $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
  $stmt->execute();

  // ユーザー名を取得
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $name = $row['name'];

  // 初期値として新しい順に並べる
  $orderBy = "deadline DESC";
  $keyword = "";
  $status = "";

  // 並び順が指定されている場合、それを適用
  if (isset($_POST['order']) && $_POST['order'] === 'oldest') {
    $orderBy = "deadline ASC";
}
  // 検索ワードが指定されている場合、それを適用
  if (isset($_POST['keyword'])) {
      $keyword = $_POST['keyword'];
      $keyword = "%$keyword%"; // 部分一致検索のためキーワードの前後にワイルドカードを追加
  }

  // ステータスの絞り込み条件を追加
  $statusCondition = '';

  if(isset($_POST['status'])){
    $status = $_POST['status'];
  }
  if ($status === '1') {
      $statusCondition = "AND tasks.status = 1";
  } elseif ($status === '0') {
      $statusCondition = "AND tasks.status = 0";
  }
  

  


   // クエリの準備とパラメータのバインド
   if (!empty($keyword)) {
    $stmt = $pdo->prepare("SELECT tasks.id, tasks.contents, tasks.deadline, categories.name, CASE WHEN tasks.status = 1 THEN '完了' ELSE '未完了' END AS status_text FROM tasks INNER JOIN categories ON tasks.category_id = categories.id WHERE tasks.user_id = :user_id AND (tasks.contents LIKE :keyword OR categories.name LIKE :keyword) $statusCondition ORDER BY $orderBy");
    $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare("SELECT tasks.id, tasks.contents, tasks.deadline, categories.name, CASE WHEN tasks.status = 1 THEN '完了' ELSE '未完了' END AS status_text FROM tasks INNER JOIN categories ON tasks.category_id = categories.id WHERE tasks.user_id = :user_id $statusCondition ORDER BY $orderBy");
}

// ステータスのパラメータをバインド
if ($statusCondition !== '') {
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
}

$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();

// 記事のデータを取得
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="style.css" rel="stylesheet" type="text/css">
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

  <main>
    <a href="./task/create.php">タスクを追加</a>

    <!-- 検索機能と絞り込み選択ボタン -->
    <div class="sarchform">
      <form method="POST" action="">
        <p>絞り込み検索</p>
        <input type="text" name="keyword" placeholder="キーワードを入力">
        <select name="status">
          <option value="">全てのステータス</option>
          <option value="1">完了</option>
          <option value="0">未完了</option>
        </select>
        <button type="submit">検索</button>
      </form>
      <form method="POST" action="">
        <p>並び替え</p>
        <button type="submit" name="order" value="newest">新着順</button>
        <button type="submit" name="order" value="oldest">古い順</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>タスク名</th>
          <th>締切</th>
          <th>カテゴリー名</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($todos as $todo): ?>
        <tr>
          <td><?php echo $todo['contents']; ?></td>
          <td><?php echo $todo['deadline']; ?></td>
          <td><?php echo $todo['name']; ?></td>
          <td>
            <a href="task/updateStatus.php?id=<?php echo $todo['id']; ?>&status=<?php echo ($todo['status_text'] == '完了') ? 0 : 1; ?>"><button type="button"><?php echo $todo['status_text']; ?></button></a>


            <a href="task/edit.php?id=<?php echo $todo['id']; ?>"><button type="button">編集</button></a>
            <a href="task/delete.php?id=<?php echo $todo['id']; ?>"><button type="button">削除</button></a>
          </td>

        </tr>
      <?php endforeach; ?>
      </tbody>

    </table>
  </main>
</body>
</html>
