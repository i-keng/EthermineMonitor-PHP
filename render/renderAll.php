<?php
    require_once "config.php";
    require_once "renderCryptocurrencies.php";
    require_once "renderAddresses.php";
    require_once "renderFooter.php";

    // Create connection
    $conn = mysqli_connect(DBServer, DBUser, DBPass, DBName);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Use DB connection to retrieve content
    renderCryptocurrencies($conn);
    renderAddresses($conn);
    renderFooter($conn);
    
    // Close connection
    mysqli_close($conn);
?>