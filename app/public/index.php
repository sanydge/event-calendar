<?php
ob_start();
include_once 'db_connection.php';
//    /** @var PDO $pdo */
global $pdo;

if($_SERVER['REQUEST_METHOD'] ==='POST'){
    var_dump($_POST);


    return;
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

            <form class="email-form" method='POST'>
                <input type="text" id="bookdate" name="date"  value="">

                <div class="button" >
                    <button type="submit">Book this day</button>
                </div>
            </form>

        </div>
    </div>
</div>


</body>
</html>