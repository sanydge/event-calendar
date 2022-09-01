<?php
ob_start();
include_once 'db_connection.php';

if (!isset($_SESSION['auth'])) {
    header('Location: /login.php');
    exit;
}

global $pdo;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['date']>= date('Y-m-d')) {
    $date = $_POST['date'];

    $todayBookings = $pdo->prepare("SELECT * FROM bookings 
         WHERE bookings.user_id =:user 
           AND bookings.date = :date ");
    $todayBookings -> bindParam(":user", $_SESSION['auth']);
    $todayBookings -> bindParam(":date", $date);
    $todayBookings -> execute();
    $todayBookings -> setFetchMode(PDO::FETCH_ASSOC);
    $todayBookings = $todayBookings ->fetchAll();

    if(!empty($todayBookings)){
        $_SESSION['error'] = "Can't book again!";
        header("Location: /?date=$date");
        exit;
    }


//    var_dump($todayBookings);
//    die();

    $sql = "INSERT INTO bookings(date, user_id) VALUES (?, ?)";
    $pdo->prepare($sql)->execute([$date, $_SESSION['auth']]);
    header("Location: /?date=$date");
    exit;
}

$date = $_GET['date'] ?? date("Y-m-d");

$query = $pdo->prepare("SELECT users.username, users.email
                                    FROM users INNER JOIN bookings ON bookings.user_id = users.id
                                    where bookings.date=:date");
$query->bindParam(":date", $date);
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);

$bookings = $query->fetchAll();

?>

<!doctype html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendar</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>

</head>
<body>
<h1>


    <a href="register.php">
        <button style=" background-color: #0da344; border:none; width: 50px; height:35px; color: white; border-radius: 10%">
            Register
        </button>
    </a>
    <a href="login.php">
        <button style=" background-color: #0da344; border:none; width: 50px; height:35px; color: white; border-radius: 10%">
            Login
        </button>
    </a>
    <a href="logout.php">
        <button style=" background-color: #0da344; border:none; width: 50px; height:35px; color: white; border-radius: 10%">
            Logout
        </button>
    </a>


</h1>
<div class="container">

    <div class="calendar">
        <div class="month">
            <button class="prev"> ⬅</button>
            <div class="date">
                <h1></h1>
                <p></p>
            </div>
            <button class="next"> ➡</button>
        </div>

        <div class="weekdays">
            <div>M</div>
            <div>T</div>
            <div>W</div>
            <div>T</div>
            <div>F</div>
            <div>S</div>
            <div>S</div>
        </div>

        <div class="days"></div>
    </div>
    <div class="calendar-schedule">

        <div class="schedule">
            <div class="schedule-date">
                <h1>Schedule for</h1>
                <p></p>
            </div>
        </div>

        <div class="schedule-text">

            <?php
            if (!empty($bookings)) {
                foreach ($bookings as $booking) {
                    echo $booking['username'] . " - " . $booking['email'];
                }
            }
            else {
                print "There are no reservations for this date";
            }
            ?>

            <?php  if($date >= date('Y-m-d')):     ?>


            <form class="email-form" method='POST'>
                <input type="hidden" id="bookdate" name="date" value="">

                <div class="button">

                    <button type="submit">Book this day</button>

                </div>
            </form>

            <?php  endif;     ?>

            <?php

            if (isset($_SESSION['error'])) {
                print ($_SESSION['error']);
                unset($_SESSION['error']);
            }

            ?>

        </div>
    </div>
</div>


</body>
</html>