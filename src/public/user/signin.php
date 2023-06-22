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
        <form action="signin_complete.php" method="post">
            <h3>ログイン</h3>
            <table>
                <tr>
                    <td><input name="email" placeholder="Email"></td>
                </tr>
                <tr>
                    <td><input name="password" type="password" placeholder="Password"></td>
                </tr>
            </table>    
            <input type="submit" value="ログイン" />
            <p><a href="signup.php">アカウントを作る</a></p>
        </form>
    </body>
</html>