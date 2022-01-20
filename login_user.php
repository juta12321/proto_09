<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="login_user_act.php" method="POST">
        <fieldset>
            <legend>ユーザーログイン</legend>
            <div>
                メールアドレス　: <input type="text" name="username">
            </div>
            <div>
                パスワード: <input type="text" name="password">
            </div>
            <div>
                <button>Login</button>
            </div>
            <a href="https://localhost/G's/20220120proto/create_user.php">新規会員登録はこちら</a>
        </fieldset>
    </form>
</body>

</html>