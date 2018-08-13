<?php
    require_once "renderCharts.php";

    function renderCryptocurrencies(&$conn) {
        $convert = "conversionRates";
        $lastUpdate = date(timeFormat, time());
        
        function conversionRates($values) {
            $returnString = "";
            while($value = mysqli_fetch_assoc($values)) {
                $returnString .= "<li>" . $value["ValueSymbol"] . ": " . rtrim(rtrim($value["CoinValue"], "0"), ".") . "</li>";
            }
            return $returnString;
        }

        print <<< TopDiv
    <div id="values">
TopDiv;

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
            <div class="valueData">
                <span class="header">1 $coinName = </span>
                <ul class="valueDataList">
                    {$convert($valueResults)}
                </ul>
            </div>
            <div class="valueChart" id="{$coinName}ChartContainer"></div>
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

        print <<< BottomDiv
    </div>

BottomDiv;
    }
?>