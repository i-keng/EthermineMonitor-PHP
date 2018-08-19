<?php
    require_once "renderCharts.php";

    function renderCryptocurrencies(&$conn) {
        // Prepare conversionRates function for inclusion in VALUES heredoc
        $convert = "conversionRates";
        
        // Retrieve each stored value
        function conversionRates($values) {
            $returnString = "";
            while($value = mysqli_fetch_assoc($values)) {
                $returnString .= "<li>" . $value["ValueSymbol"] . ": " . rtrim(rtrim($value["CoinValue"], "0"), ".") . "</li>";
            }
            return $returnString;
        }

        // Render container divs
        print <<< TopDiv
    <div id="header">
        <div id="values">
TopDiv;

        // Attempt to retrieve and render each coin and values from the DB
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

        // Close container divs
        print <<< BottomDiv
        </div>
    </div>

BottomDiv;
    }
?>