<?php
    require_once "../config.php";
    require_once "FetchCoins.php";
    require_once "FetchWallets.php";

    // Create connection
    $conn = mysqli_connect(DBServer, DBUser, DBPass, DBName);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    FetchCoins($conn);
    FetchWallets($conn);

    mysqli_close($conn);
?>