<?php
    require_once "config.php";
    require_once "renderCharts.php";

    function renderAddresses(&$conn) {
        print("<div id=\"current\">\n");

        if($result = mysqli_query($conn, "SELECT * FROM Wallets")) {
            $value = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT Coins.Coin, CoinValues.ValueSymbol, CoinValues.CoinValue
                FROM Coins, CoinValues
                WHERE Coins.Coin = 'ETH'
                AND Coins.Coin_id = CoinValues.Coin_id
                LIMIT 1"
            ));
            while($record = mysqli_fetch_assoc($result)) {
                $name = $record["Name"];
                $address = $record["Address"];
                $ethermineURL = ethermineURL;
                $etherchainURL = etherchainURL;
                $style = "width: " . ((99 / count(ADDRESSES)) - 0.5) . "%;";
            
                $primaryCurrency = $value["ValueSymbol"];
            
                $activeWorkers = $record["ActiveWorkers"];
            
                print <<< HEADER
                <div id="{$name}Section" class="Section" style="$style">
                    <ul class="current $name">
            
                        <h3 class="$name">$name's Miner</h3>
                        <span class="subHeaders">Active Workers: $activeWorkers</span>
                        <span class="subHeaders">Wallet:</span>
                        <a class="link" target="_blank" href="$ethermineURL$address">$address</a>
                        <a class="link" target="_blank" href="$etherchainURL$address">(etherchain.org)</a>
HEADER;
            
                if($record["LastSeen"] != 0) {
                    $unpaidETH = $record["Unpaid"];
                    $unpaidFiat = isset($value) ?
                        number_format(($unpaidETH * $value["CoinValue"]), 2) :
                        0;
                        
                    $hashrateCurrent = $record["Current"];
                    $hashrateReported = $record["Reported"];
                    $hashrateAverage = $record["Average"];
                    $valid = $record["Valid"];
                        
                    $payrateETHWeek = number_format(($record["PerMinute"] * perWeekOffset), 6);
                    $payrateUSDWeek = number_format(($record["PerMinute"] * $value["CoinValue"] * perWeekOffset), 2);
                
                    $payrateETHMonth = number_format(($record["PerMinute"] * perMonthOffset), 6);
                    $payrateUSDMonth = number_format(($record["PerMinute"] * $value["CoinValue"] * perMonthOffset), 2);
                
                    $payrateETHYear = number_format(($record["PerMinute"] * perYearOffset), 6);
                    $payrateUSDYear = number_format(($record["PerMinute"] * $value["CoinValue"] * perYearOffset), 2);
                
                    $lastSeen = date(timeFormat, $record["LastSeen"]);
                    $lastRefresh = date(timeFormat, $record["LastRefresh"]);
                
                    print <<< DATA
                
                    <li>Unpaid ETH: $unpaidETH (\${$unpaidFiat} $primaryCurrency)</li>
                    <li><ul>Hashrate:
                        <li>Current: $hashrateCurrent Mh/s ($valid shares)</li>
                        <li>Repoted: $hashrateReported Mh/s</li>
                        <li>Average: $hashrateAverage Mh/s</li>
                    </ul></li>
                
                    <li><ul>ETH ($primaryCurrency) per
                        <li>Week: $payrateETHWeek (\${$payrateUSDWeek})</li>
                        <li>Month: $payrateETHMonth (\${$payrateUSDMonth})</li>
                        <li>Year: $payrateETHYear (\${$payrateUSDYear})</li>
                    </ul></li>
                
                    <li>Miner last seen: $lastSeen</li>
                    <li>Last pool refresh: $lastRefresh</li>
                    </ul>
                
                    <div class="chart" id="$name"></div>
                    </div>
                
DATA;
                    //renderAddressChart($wallet);
                }
                else {
                    print <<< NODATA
                
                    <li class="nodata">No Data</li>
                    </ul>
                    </div>
                
NODATA;
                }
            }
        }

        print("</div>\n");
    }
?>