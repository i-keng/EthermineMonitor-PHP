<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Gohan Ethermine Monitor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <link rel="icon" type="image/ico" href="favicon.ico" />
    <meta http-equiv="refresh" content="60" />

    <script type="text/javascript" src="fusioncharts/fusioncharts-dist/fusioncharts.js"></script>
</head>
<body>
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
</body>
</html>