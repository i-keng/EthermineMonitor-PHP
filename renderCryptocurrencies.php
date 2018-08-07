<?php
    require_once "config.php";
    require_once "renderCharts.php";

    function renderCryptocurrencies(&$conn) {
        //$draw = "drawChart";
        $convert = "conversionRates";
        $lastUpdate = date(timeFormat, time());
        
        function conversionRates($values) {
            $returnString = "";
            while($value = mysqli_fetch_assoc($values)) {
                $returnString .= "<li>" . $value["ValueSymbol"] . ": " . rtrim($value["CoinValue"], "0") . "</li>";
            }
            return $returnString;
        }

        print("<div id=\"values\">\n");

        if($coinResult = mysqli_query($conn, "SELECT * FROM Coins")) {
            while($coin = mysqli_fetch_assoc($coinResult)) {
                if($valueResults = mysqli_query($conn,
                        "SELECT * 
                        FROM CoinValues 
                        WHERE Coin_id = '$coin[Coin_id]'"
                    )) {
                    $coinName = $coin["Coin"];
                    
                    print <<< VALUES

                    <div class="valueSection" id="{$coinName}Section">
                        <ul class="valueData">
                            <h3 class="valueHeader">1 $coinName = </h3>
                            {$convert($valueResults)}
                        </ul>
                        <div class="valueChart" id="$coinName"></div>
                    </div>
VALUES;
                    renderCryptoChart($conn, $coin);

                }
            }
        }

        print <<< TIME
        
            <div id="updateTime">
                <h4>Page Last Updated: $lastUpdate</h4>
            </div>
TIME;

        print("</div>\n");
    }
?>