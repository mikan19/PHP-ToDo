<?php
session_start();

// エラーメッセージがセッションに保存されている場合に表示
if (isset($_SESSION["error_message"])) {
    echo "<p class='error'>" . $_SESSION["error_message"] . "</p>";
    unset($_SESSION["error_message"]); // エラーメッセージを表示した後はセッションから削除
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>sample</title>
    </head>
    <body>
        <!-- 会員登録フォーム -->
        <form action="signup_complete.php" method="post">
            <h3>会員登録</h3>
            <table>
                <tr>
                    <td><input name="name" placeholder="User name"></td>
                </tr>
                <tr>
                    <td><input name="email" placeholder="Email"></td>
                </tr>
                <tr>
                    <td><input name="password" type="text" placeholder="Password"></td>
                </tr>
                <tr>
                    <td><input name="passcheck" type="text" placeholder="Password確認"></td>
                </tr>
            </table>    
            <input type="submit" value="アカウント作成" />
            <p><a href="signin.php">ログイン画面へ</a></p>
        </form>
        <form action="../task/store.php" method="post">
            <div>
                <label for="category">カテゴリを選んでください</label>
                <select name="category">
                <option value="">タスクを追加</option>
                <option value="task1">タスク１</option>
                <option value="task2">タスク２</option>
                <option value="task3">タスク３</option>
                <option value="task4">タスク４</option>
                </select>

            </div>
            <div>
                <label for="date">日付を選んでください</label>
                <input type="date" name="date" id="date" />
            </div>
            <input type="submit" value="アカウント作成" />
        </form>
    </body>
</html>
