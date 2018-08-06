<?php
    require_once "config.php";
    require_once "loadData.php";
    require_once "renderCharts.php";

    function renderCryptocurrencies(&$values) {
        $draw = "drawChart";
        $convert = "conversionRates";
        $lastUpdate = date(timeFormat, time());
        
        function conversionRates($values, $index) {
            $returnString = "";
            foreach($values[$index]->getValue() as $key => $conversion) {
                $returnString .= "<li>" . $key . ": " . $conversion . "</li>";   
            }
            return $returnString;
        }

        print("<div id=\"values\">\n");

        foreach($values as $index => $coinValue) {
            $coin = $coinValue->getCoin();
            $value = $coinValue->getValue();

            print <<< VALUES

            <div class="valueSection" id="{$coin}Section">
                <ul class="valueData">
                    <h3 class="valueHeader">1 $coin = </h3>
                    {$convert($values, $index)}
                </ul>
                <div class="valueChart" id="$coin"></div>
            </div>
VALUES;
            renderCryptoChart($coinValue);
        }

        print <<< TIME
        
            <div id="updateTime">
                <h4>Page Last Updated: $lastUpdate</h4>
            </div>
TIME;

        print("</div>\n");
    }
?>