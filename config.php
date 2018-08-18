<?php
    /* 
        ********** User Settings **********
        - Enter a name and address in the ADDRESSES constant array
        - Enter at least one fiat symbol (e.g. USD) in the FIAT const array
        - Enter at least one crypto symbol (e.g. ETH) in the CRYPTOCURRENCIES const array
    */
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

    /* 
        ********** MySQL Database Settings **********
    */
    const DBServer = "localhost";
    const DBUser = "mining";
    const DBPass = "password";
    const DBName = "mining";

    /* 
        ********** Formatting Settings **********
        Don't touch these unless you know what you're doing
    */
    const timeFormat = "h:ia T";

    const valueHistoryLimit = 30;
    const mHashOffset = 10 ** 6;
    const ethOffset = 10 ** 18;
    const timeOffset = 1000;
    const perHourOffset = 60;
    const perDayOffset = perHourOffset * 24;
    const perWeekOffset = perDayOffset * 7;
    const perYearOffset = perWeekOffset * 52;
    const perMonthOffset = perYearOffset / 12;
    const tHashOffset = 10 ** 12;

    /* 
        ********** API and hyperlink URLs **********
    */
    const ethermineURL = "https://www.ethermine.org/miners/";
    const etherchainURL = "https://www.etherchain.org/account/";

    const ethermineAPI = "https://api.ethermine.org";
    const cryptocompareAPI = "https://min-api.cryptocompare.com/data/";
    const etherchainAPI = "https://www.etherchain.org/api";
    
    const minerURL = (ethermineAPI . "/miner/");
    const poolstatsURL = (ethermineAPI . "/poolstats");
    const difficultyURL = (etherchainAPI . "/difficulty");
?>