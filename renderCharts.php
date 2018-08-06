<?php
    require_once "fusioncharts/php-wrapper/fusioncharts-wrapper/fusioncharts.php";
    require_once "loadData.php";

    function renderAddressChart(&$wallet) {
        $name = $wallet->getName();
        $current = array();
        $reported = array();
        $average = array();
        $stale = array();

        $data = array(
            "chart" => array(
                "caption" => "$name",
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
                    "dataset" => array()
                ),
                array(
                    "title" => "Stale Shares",
                    "adjustDiv" => "1",
                    "axisonleft" => "0",
                    "maxValue" => "20",
                    "numDivLines" => "20",
                    "yAxisValueDecimals" => "0",
                    "dataset" => array()
                )
            )
        );

        foreach($wallet->getHistory()['data'] as $historyItem) {
            $label = date("h:i", $historyItem['time']);
            array_push($data['categories'][0]['category'], array(
                "label" => "$label"
            ));
            array_push($current, array("value" => number_format(($historyItem['currentHashrate'] / mHashOffset), 2)));
            array_push($reported, array("value" => number_format(($historyItem['reportedHashrate'] / mHashOffset), 2)));
            array_push($average, array("value" => number_format(($historyItem['averageHashrate'] / mHashOffset), 2)));
            array_push($stale, array("value" => $historyItem['staleShares']));
        }

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

        $jsonData = json_encode($data);
        $chart = new FusionCharts("MultiAxisLine", "{$name}Chart", "100%", "280", "$name", "json", $jsonData);
        $chart->render();
    }

    function renderCryptoChart(&$value) {
        $coin = $value->getCoin();
        $values = array();
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
        
        foreach($value->getHistory()['Data'] as $historyItem) {
            $label = date("m/d", $historyItem['time']);
            array_push($data['data'], array(
                "label" => "$label",
                "value" => $historyItem['close']
            ));

            //array_push($values, array("value" => $historyItem['close']));
        }

        //var_dump($data);
        $jsonData = json_encode($data);
        $chart = new FusionCharts("line", "{$coin}Chart", "100%", "100%", "$coin", "json", $jsonData);
        $chart->render();

        /* $data = array(
            "chart" => array(
                "showLegend" => "0",
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
                    "title" => FIAT[0],
                    "setAdaptiveYMin" => "1",
                    "dataset" => array()
                )
            )
        );

        foreach($value->getHistory()['Data'] as $historyItem) {
            $label = date("m/d", $historyItem['time']);
            array_push($data['categories'][0]['category'], array(
                "label" => "$label"
            ));

            array_push($values, array("value" => $historyItem['close']));
        }

        array_push($data['axis'][0]['dataset'], array(
            "seriesname" => FIAT[0],
            "color" => "3366CC",
            "data" => $values
        ));

        $jsonData = json_encode($data);
        $chart = new FusionCharts("multiaxisline", "{$coin}Chart", "100%", "100%", "$coin", "json", $jsonData);
        $chart->render(); */
    }
?>