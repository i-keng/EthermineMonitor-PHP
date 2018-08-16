<?php
    require_once dirname(__FILE__) . "/../config.php";
    require_once "FetchCoins.php";
    require_once "FetchWallets.php";
    require_once "FetchFooter.php";

    // Create connection
    $conn = mysqli_connect(DBServer, DBUser, DBPass, DBName);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    FetchCoins($conn);
    FetchWallets($conn);
    FetchFooter($conn);

    mysqli_close($conn);
?>