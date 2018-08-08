<?php
    require_once "config.php";
    require_once "renderCryptocurrencies.php";
    require_once "renderAddresses.php";

    // Create connection
    $conn = mysqli_connect(DBServer, DBUser, DBPass, DBName);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    renderCryptocurrencies($conn);
    renderAddresses($conn);
    
    mysqli_close($conn);
?>