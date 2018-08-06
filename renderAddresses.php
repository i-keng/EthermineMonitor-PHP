<?php
    require_once "config.php";
    require_once "loadData.php";
    require_once "renderCharts.php";

    function renderAddresses(&$wallets, &$values) {
        print("<div id=\"current\">\n");

        foreach($wallets as $wallet)
        {
            $name = $wallet->getName();
            $address = $wallet->getAddress();
            $currentStats = $wallet->getCurrentStats();
            $ethermineURL = ethermineURL;
            $etherchainURL = etherchainURL;
            $style = "width: " . ((99 / count($wallets)) - 0.5) . "%;";

            if(count(FIAT) > 0) {
                $primaryFiat = FIAT[0];
            }
            else {
                $primaryFiat = "USD";
            }

            if(is_array($currentStats) && isset($currentStats["activeWorkers"])) {
                $activeWorkers = $currentStats["activeWorkers"];
            }
            else {
                $activeWorkers = 0;
            }

            print <<< HEADER
            <div id="{$name}Section" class="Section" style="$style">
                <ul class="current $name">

                    <h3 class="$name">$name's Miner</h3>
                    <span class="subHeaders">Active Workers: $activeWorkers</span>
                    <span class="subHeaders">Wallet:</span>
                    <a class="link" target="_blank" href="$ethermineURL$address">$address</a>
                    <a class="link" target="_blank" href="$etherchainURL$address">(etherchain.org)</a>
HEADER;

            if($currentStats != "NO DATA") {
                $unpaidETH = number_format(($currentStats["unpaid"] / ethOffset), 6);
                $unpaidFiat = number_format(($unpaidETH * $values[0]->getValue()[$primaryFiat]), 2);

                $hashrateCurrent = number_format(($currentStats["currentHashrate"] / mHashOffset), 2);
                $hashrateReported = number_format(($currentStats["reportedHashrate"] / mHashOffset), 2);
                $hashrateAverage = number_format(($currentStats["averageHashrate"] / mHashOffset), 2);

                $payrateETHWeek = number_format(($currentStats["coinsPerMin"] * perWeekOffset), 6);
                $payrateUSDWeek = number_format(($currentStats["usdPerMin"] * perWeekOffset), 2);

                $payrateETHMonth = number_format(($currentStats["coinsPerMin"] * perMonthOffset), 6);
                $payrateUSDMonth = number_format(($currentStats["usdPerMin"] * perMonthOffset), 2);

                $payrateETHYear = number_format(($currentStats["coinsPerMin"] * perYearOffset), 6);
                $payrateUSDYear = number_format(($currentStats["usdPerMin"] * perYearOffset), 2);

                $lastSeen = date(timeFormat, $currentStats["lastSeen"]);
                $lastRefresh = date(timeFormat, $currentStats["time"]);

                print <<< DATA

                <li>Unpaid ETH: $unpaidETH (\${$unpaidFiat} $primaryFiat)</li>
                <li><ul>Hashrate:
                    <li>Current: $hashrateCurrent Mh/s ($currentStats[validShares] shares)</li>
                    <li>Repoted: $hashrateReported Mh/s</li>
                    <li>Average: $hashrateAverage Mh/s</li>
                </ul></li>

                <li><ul>ETH (USD) per
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
                renderAddressChart($wallet);
            }
            else {
                print <<< NODATA

                <li class="nodata">No Data</li>
                </ul>
                </div>

NODATA;
            }
        }

        print("</div>\n");
    }
?>