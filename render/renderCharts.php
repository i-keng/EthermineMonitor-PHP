<?php
    // Depends on FusionCharts fusioncharts-dist and php-wrapper, included in .gitmodules
    require_once "fusioncharts/php-wrapper/fusioncharts-wrapper/fusioncharts.php";

    function renderAddressChart(&$conn, &$wallet) {
        $name = $wallet["Name"];
        $current = array();
        $reported = array();
        $average = array();
        $stale = array();

        // Attempt to retrieve WalletHistory from DB
        if($result = mysqli_query($conn,
            "SELECT *
            FROM WalletHistory
            WHERE Wallet_id = ($wallet[Wallet_id])
            ORDER BY HistoryTime"
        )) {
            // Prepare the data array with required chart settings
            $data = array(
                "chart" => array(
                    "showLegend" => "1",
                    "showvalues" => "0",
                    "drawAnchors" => "0",
                    "allowSelection" => "0"
                ),
                "categories" => array(
                    array(
                        "category" => array()
                    )
                ),
                "axis" => array(
                    array(
                        "title" => "Hashrate (Mh/s)",
                        "color" => "000000",
                        "dataset" => array()
                    ),
                    array(
                        "title" => "Stale Shares",
                        "adjustDiv" => "1",
                        "axisonleft" => "0",
                        "maxValue" => "20",
                        "numDivLines" => "20",
                        "yAxisValueDecimals" => "0",
                        "color" => "006400",
                        "dataset" => array()
                    )
                )
            );

            // Prepare history to be added to the chart
            while($record = mysqli_fetch_assoc($result)) {
                $label = date("h:i", $record['HistoryTime']);
                array_push($data['categories'][0]['category'], array(
                    "label" => "$label"
                ));
                array_push($current, array("value" => $record['Current']));
                array_push($reported, array("value" => $record['Reported']));
                array_push($average, array("value" => $record['Average']));
                array_push($stale, array("value" => $record['Stale']));
            }

            // Add the prepared history to the data array
            array_push($data['axis'][0]['dataset'], array(
                "seriesname" => "Current",
                "color" => "3366CC",
                "data" => $current
            ));

            array_push($data['axis'][0]['dataset'], array(
                "seriesname" => "Reported",
                "color" => "DC3912",
                "data" => $reported
            ));

            array_push($data['axis'][0]['dataset'], array(
                "seriesname" => "Average",
                "color" => "FF9900",
                "data" => $average
            ));

            array_push($data['axis'][1]['dataset'], array(
                "seriesname" => "Stale",
                "color" => "006400",
                "rederas" => "area",
                "data" => $stale
            ));

            // Encode the data array, add it to the chart, and render the chart
            $jsonData = json_encode($data);
            $chart = new FusionCharts("MultiAxisLine", "{$name}Chart", "100%", "100%", "{$name}ChartContainer", "json", $jsonData);
            $chart->render();
        }
    }

    function renderCryptoChart(&$conn, &$value) {
        $coin = $value["Coin"];
        $values = array();

        // Attempt to retrieve CoinHistory from DB
        if($result = mysqli_query($conn,
            "SELECT *
            FROM CoinHistory
            WHERE Coin_id = ($value[Coin_id])
            ORDER BY HistoryTime"
        )) {
            // Prepare the data array with required chart settings
            $data = array(
                "chart" => array(
                    "paletteColors" => "3366CC",
                    "showLegend" => "0",
                    "showvalues" => "0",
                    "drawAnchors" => "0",
                    "allowSelection" => "0",
                    "setAdaptiveYMin" => "1",
                    "yAxisName" => FIAT[0]
                ),
                "data" => array()
            );

            // Prepare history to be added to the chart and add it to the data array
            while($record = mysqli_fetch_assoc($result)) {
                $label = date("m/d", $record['HistoryTime']);
                array_push($data['data'], array(
                    "label" => "$label",
                    "value" => $record['CoinValue']
                ));
            }

            // Encode the data array, add it to the chart, and render the chart
            $jsonData = json_encode($data);
            $chart = new FusionCharts("line", "{$coin}Chart", "100%", "100%", "{$coin}ChartContainer", "json", $jsonData);
            $chart->render();
        }
    }
?>