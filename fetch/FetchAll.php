<?php
    require_once dirname(__FILE__, 2) . "/config.php";
    require_once "FetchCoins.php";
    require_once "FetchWallets.php";
    require_once "FetchFooter.php";

    // Create connection
    $conn = mysqli_connect(DBServer, DBUser, DBPass, DBName);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Use DB connection to store fetched content
    FetchCoins($conn);
    FetchWallets($conn);
    FetchFooter($conn);

    // Close connection
    mysqli_close($conn);
?>