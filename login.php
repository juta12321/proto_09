<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="login_act.php" method="POST">
        <fieldset>
            <legend>管理者ログイン</legend>
            <div>
                管理者ID　: <input type="text" name="username">　※「admin」です
            </div>
            <div>
                パスワード: <input type="text" name="password">　※「admin」です
            </div>
            <div>
                <button>Login</button>
            </div>
        </fieldset>
    </form>
</body>

</html>