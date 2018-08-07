<?php
    require_once "../config.php";

    function FetchCoins(&$conn) {
        // Ensure tables exist
        mysqli_query($conn, 
            "CREATE TABLE IF NOT EXISTS Coins(
                Coin_id int not null auto_increment primary key,
                Coin varchar(5)
            );"
        );
        mysqli_query($conn, 
            "CREATE TABLE IF NOT EXISTS CoinValues(
                Value_id int not null auto_increment primary key,
                ValueSymbol varchar(10),
                CoinValue decimal(12,6),
                Coin_id int not null,
                FOREIGN KEY fk_coin(Coin_id)
                REFERENCES Coins(Coin_id)
                ON UPDATE CASCADE
                ON DELETE CASCADE
            );"
        );
        mysqli_query($conn, 
            "CREATE TABLE IF NOT EXISTS CoinHistory(
                History_id int not null auto_increment primary key,
                CoinValue decimal(8,2),
                HistoryTime int,
                Coin_id int not null,
                FOREIGN KEY fk_coin(Coin_id)
                REFERENCES Coins(Coin_id)
                ON UPDATE CASCADE
                ON DELETE CASCADE
            );"
        );

        foreach(CRYPTOCURRENCIES as $currency) {
            // Ensure the Coin exists in the table
            $count = mysqli_query($conn,
                "SELECT COUNT(*)
                FROM Coins
                WHERE Coin = '$currency'"
            );
            // Add the Coin to the table if it doesn't exist
            if(mysqli_num_rows($count) > 0 && !mysqli_fetch_row($count)[0]) {
                mysqli_query($conn,
                    "INSERT INTO Coins (Coin)
                    VALUE ('$currency');"
                );
            }
            mysqli_free_result($count);

            $Coin_id = "SELECT Coin_id FROM Coins WHERE Coin = '$currency' LIMIT 1";
            $valueURL = cryptocompareAPI . "price?" . "fsym=" . $currency . "&tsyms=";
            
            foreach(FIAT as $fiat) {
                $valueURL .= "$fiat,";
            }
            foreach(CRYPTOCURRENCIES as $crypto) {
                if($crypto != $currency) {
                    $valueURL .= "$crypto,";
                }
            }
            
            $values = json_decode(file_get_contents($valueURL), true);

            foreach($values as $symbol => $value) {
                // Determine if this value exists in the DB
                $count = mysqli_query($conn,
                    "SELECT COUNT(*)
                    FROM CoinValues
                    WHERE ValueSymbol = '$symbol'
                    AND Coin_id = ($Coin_id)"
                );
                // Update the record if it exists
                if(mysqli_num_rows($count) > 0 && mysqli_fetch_row($count)[0]) {
                    $sql = 
                    "UPDATE CoinValues
                    SET CoinValue = '$value'
                    WHERE ValueSymbol = '$symbol'
                    AND Coin_id = ($Coin_id)";
                }
                else { // Create a new record if it doesn't exist
                    $sql = 
                    "INSERT INTO CoinValues (ValueSymbol, CoinValue, Coin_id)
                    VALUES ('$symbol', '$value', ($Coin_id))";
                }
                mysqli_free_result($count);

                mysqli_query($conn, $sql);
            }

            $historyURL = cryptocompareAPI . "histoday?" . "fsym=" . $currency
            . "&tsym=" . FIAT[0] . "&limit=" . (valueHistoryLimit - 1);
            $history = json_decode(file_get_contents($historyURL), true);

            $records = mysqli_query($conn,
                "SELECT History_id
                FROM CoinHistory
                WHERE Coin_id = ($Coin_id)"
            );
            $recordCount = mysqli_num_rows($records);

            for($i = 0; $i < count($history["Data"]); $i++) {
                $value = $history['Data'][$i]['close'];
                $time = $history['Data'][$i]['time'];
                if($i < $recordCount) {
                    mysqli_data_seek($records, $i);
                    $History_id = mysqli_fetch_row($records)[0];
                    $sql = 
                    "UPDATE CoinHistory
                    SET
                        CoinValue = '$value',
                        HistoryTime = '$time'
                    WHERE History_id = '$History_id'";
                }
                else {
                    $sql =
                    "INSERT INTO CoinHistory (CoinValue, HistoryTime, Coin_id)
                    VALUES ('$value', '$time', ($Coin_id))";
                }

                mysqli_query($conn, $sql);
            }
            mysqli_free_result($records);
        }
    }
?>