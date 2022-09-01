<?php

ob_start();

include_once 'db_connection.php';

if (isset($_SESSION['auth'])) {
    header('Location: /index.php');
    exit;
}

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($emailB, FILTER_VALIDATE_EMAIL) === false ||
        $emailB != $email
    ) {
        $_SESSION['error'] = 'Invalid email';
        header("Location: /register.php");
        exit;
    }

    if (strlen($_POST["password"]) < 6) {
        $_SESSION['error'] = 'Password must be at least 6 characters long.';
        header("Location: /register.php");
        exit;
    }

    if (!preg_match("/[a-z]/i", $_POST["password"])) {
        $_SESSION['error'] = 'Password must contain at least one letter';
        header("Location: /register.php");
        exit;
    }

    if (!preg_match("/[0-9]/", $_POST["password"])) {
        $_SESSION['error'] = 'Password must contain at least one letter';
        header("Location: /register.php");
        exit;
    }


    $sql = $pdo->prepare("SELECT * FROM users WHERE  email = :email ");
    $sql->bindParam(":email", $email);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);

    if($sql->fetch()){
        $_SESSION['error'] = 'Email already exists';
        header("Location: /register.php");
        exit;
    }

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    if ($pdo->prepare($sql)->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT, ['cost'=>10])])) {
        header("Location: /login.php");
        exit;
    }

}

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>Register</title>
</head>
<body>
<form action="register.php" method="post" novalidate>
    <div class="">
        <h1>Sign Up</h1>
        <p>Please fill in this form to create an account.</p>
        <?php

        if (isset($_SESSION['error'])) {
            print ($_SESSION['error']);
            unset($_SESSION['error']);
        }

        ?>
        <hr>

        <label for="username"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" required>

        <label for="email"><b>email</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>

        <!--        <label for="psw-repeat"><b>Repeat Password</b></label>-->
        <!--        <input type="password" placeholder="Repeat Password" name="psw-repeat" required>-->
        <!---->
        <!--        <label>-->
        <!--            <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me-->
        <!--        </label>-->


        <div class="">
            <button type="button">Cancel</button>
            <button type="submit" name="submit">Sign Up</button>
        </div>
        <div class="text-center">Already have an account? <a href="login.php">Sign in</a></div>
    </div>


</form>
</body>
</html>



