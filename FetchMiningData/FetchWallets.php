<?php
    require_once "../config.php";

    function FetchWallets(&$conn) {
        // Ensure tables exist
        mysqli_query($conn, 
            "CREATE TABLE IF NOT EXISTS Wallets(
                Wallet_id int not null auto_increment primary key,
                Name varchar(20),
                Address varchar(50),
                ActiveWorkers int,
                Unpaid decimal(10,6),
                Current decimal(10,2),
                Reported decimal(10,2),
                Average decimal(10,2),
                PerMinute decimal(25,20),
                LastSeen datetime,
                LastRefresh datetime
            );"
        );
        mysqli_query($conn, 
            "CREATE TABLE IF NOT EXISTS WalletHistory(
                History_id int not null auto_increment primary key,
                HistoryTime datetime,
                ActiveWorkers int,
                Current decimal(10,2),
                Reported decimal(10,2),
                Average decimal(10,2),
                Stale int,
                Wallet_id int not null,
                FOREIGN KEY fk_wallet(Wallet_id)
                REFERENCES Wallets(Wallet_id)
                ON UPDATE CASCADE
                ON DELETE CASCADE
            );"
        );

        // Load stats for each wallet address
        foreach(ADDRESSES as $name => $address) {
            $active = 0;
            $unpaid = 0;
            $hashrateCurrent = 0;
            $hashrateReported = 0;
            $hashrateAverage = 0;
            $perMinute = 0;
            $lastSeen = 0;
            $lastRefresh = 0;

            $stats = json_decode(file_get_contents(ethermineAPI . $address . "/currentStats"), true)["data"];

            if(is_array($stats) && isset($stats['activeWorkers'])) {
                $active = $stats['activeWorkers'];
                $unpaid = number_format(($stats["unpaid"] / ethOffset), 6);

                $hashrateCurrent = number_format(($stats["currentHashrate"] / mHashOffset), 2);
                $hashrateReported = number_format(($stats["reportedHashrate"] / mHashOffset), 2);
                $hashrateAverage = number_format(($stats["averageHashrate"] / mHashOffset), 2);

                $perMinute = $stats["coinsPerMin"];

                $lastSeen = $stats["lastSeen"];
                $lastRefresh = $stats["time"];
            }

            // Determine if this address exists in the DB
            $count = mysqli_query($conn,
                "SELECT COUNT(*)
                FROM Wallets
                WHERE Address = '$address'"
            );

            // Update the record if it exists
            if(mysqli_num_rows($count) > 0 && mysqli_fetch_row($count)[0]) {
                $sql = 
                "UPDATE Wallets
                SET 
                    ActiveWorkers = '$active',
                    Unpaid = '$unpaid',
                    Current = '$hashrateCurrent',
                    Reported = '$hashrateReported',
                    Average = '$hashrateAverage',
                    PerMinute = '$perMinute',
                    LastSeen = FROM_UNIXTIME($lastSeen),
                    LastRefresh = FROM_UNIXTIME($lastRefresh)
                WHERE Address = '$address'";
            }
            else { // Create a new record if it doesn't exist
                $sql = 
                "INSERT INTO Wallets (
                    Name,
                    Address,
                    ActiveWorkers,
                    Unpaid,
                    Current,
                    Reported,
                    Average,
                    PerMinute,
                    LastSeen,
                    LastRefresh
                )
                VALUES (
                    '$name',
                    '$address',
                    '$active',
                    '$unpaid',
                    '$hashrateCurrent',
                    '$hashrateReported',
                    '$hashrateAverage',
                    '$perMinute',
                    FROM_UNIXTIME($lastSeen),
                    FROM_UNIXTIME($lastRefresh)
                )";
            }
            mysqli_free_result($count);

            // Execute the query
            mysqli_query($conn, $sql);


            $Wallet_id = "SELECT Wallet_id FROM Wallets WHERE Address = '$address' LIMIT 1";
            $history = json_decode(file_get_contents(ethermineAPI . $address . "/history"), true);
            //var_dump($history);

            if(is_array($history)) {
                $records = mysqli_query($conn,
                    "SELECT History_id
                    FROM WalletHistory
                    WHERE Wallet_id = ($Wallet_id)"
                );
                $recordCount = mysqli_num_rows($records);

                for($i = 0; $i < count($history["data"]); $i++) {
                    $time = $history['data'][$i]['time'];
                    $active = $history['data'][$i]['activeWorkers'];
                    $current = number_format(($history['data'][$i]['currentHashrate'] / mHashOffset), 2);
                    $reported = number_format(($history['data'][$i]['reportedHashrate'] / mHashOffset), 2);
                    $average = number_format(($history['data'][$i]['averageHashrate'] / mHashOffset), 2);
                    $stale = $history['data'][$i]['staleShares'];

                    if($i < $recordCount) {
                        mysqli_data_seek($records, $i);
                        $History_id = mysqli_fetch_row($records)[0];
                        $sql = 
                        "UPDATE WalletHistory
                        SET
                            HistoryTime = FROM_UNIXTIME($time),
                            ActiveWorkers = '$active',
                            Current = '$current',
                            Reported = '$reported',
                            Average = '$average',
                            Stale = '$stale'
                        WHERE History_id = '$History_id'";
                    }
                    else {
                        $sql =
                        "INSERT INTO WalletHistory (
                            HistoryTime,
                            ActiveWorkers,
                            Current,
                            Reported,
                            Average,
                            Stale,
                            Wallet_id
                        )
                        VALUES (
                            FROM_UNIXTIME($time),
                            '$active',
                            '$current',
                            '$reported',
                            '$average',
                            '$stale',
                            ($Wallet_id)
                        )";
                    }

                    mysqli_query($conn, $sql);
                }
                mysqli_free_result($records);
            }
        }
    }
?>