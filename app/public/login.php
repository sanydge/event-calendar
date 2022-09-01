<?php

ob_start();

include_once 'db_connection.php';

if (isset($_SESSION['auth'])) {
    header('Location: /index.php');
    exit;
}

global $pdo;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = $pdo->prepare("SELECT * FROM users WHERE  email = :email");
    $sql->bindParam(":email", $email);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);

    $user_data = $sql->fetch();

    if ($user_data && password_verify($password, $user_data['password'])) {
        $_SESSION['auth'] = $user_data["id"];
    }
}

?>

<html lang="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>login</title>
</head>
<body>
<form action="login.php" method="post">

    <h1>Login</h1>
    <p>Please fill in this form login.</p>
    <hr>

    <div>
        <!--        <label for="username"><b>Username</b></label>-->
        <!--        <input type="text" placeholder="Enter Username" name="username" required>-->

        <label for="email"><b>Email</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" id="password" required>

        <div class="">
            <button type="submit" name="login">Login</button>
        </div>
    </div>

</form>
</body>
</html>

