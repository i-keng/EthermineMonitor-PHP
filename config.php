<?php
    // Requires PHP 7
    define("ADDRESSES",
    [
        "Michael" => "49B9Da60c5256A8428BcF331c92aeBe80C9E04b4",
        "Kevin" => "00693Ed1A9541d84849Ccf2D01a2637a42757e3D",
        "Josh" => "3e5aaae2f27233ec7af634b14b6ef324e1fa0f60"
    ]);
    define("FIAT",
    [
        "USD"
    ]);
    define("CRYPTOCURRENCIES",
    [
        "ETH",
        "BTC"
    ]);

    // Use below if on PHP 5.6
    //const ADDRESSES = [];
    //const FIAT = [];
    //const CRYPTOCURRENCIES = [];

    const DBServer = "localhost";
    const DBUser = "mining";
    const DBPass = "password";
    const DBName = "mining";

    const timeFormat = "h:ia T";

    const valueHistoryLimit = 30;
    const mHashOffset = 1000000;
    const ethOffset = 1000000000000000000;
    const timeOffset = 1000;
    const perHourOffset = 60;
    const perDayOffset = perHourOffset * 24;
    const perWeekOffset = perDayOffset * 7;
    const perYearOffset = perWeekOffset * 52;
    const perMonthOffset = perYearOffset / 12;

    const ethermineURL = "https://www.ethermine.org/miners/";
    const etherchainURL = "https://www.etherchain.org/account/";

    const ethermineAPI = "https://api.ethermine.org/miner/";
    const cryptocompareAPI = "https://min-api.cryptocompare.com/data/";
?>