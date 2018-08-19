<!--
    Load and render ethereum mining data from ethermine.org as well as current values of 
    cryptocurrencies (e.g. ETH, BTC, etc.) provided in the config.php file.

    See the README for more information: https://github.com/xcalibur839/EthermineMonitor-PHP
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Gohan Ethermine Monitor</title>
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <link rel="icon" type="image/ico" href="favicon.ico" />
    <meta http-equiv="refresh" content="60" />

    <script type="text/javascript" src="fusioncharts/fusioncharts-dist/fusioncharts.js"></script>
</head>
<body>
<?php
    // Render process begins automatically by including/requiring render/renderAll.php
    require_once "render/renderAll.php";
?>
</body>
</html>