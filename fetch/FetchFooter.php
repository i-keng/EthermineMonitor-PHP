<?php
    function FetchFooter(&$conn) {
        // Ensure tables exist
        mysqli_query($conn, 
            "CREATE TABLE IF NOT EXISTS Footer(
                LastUpdate int,
                Difficulty decimal(10,2),
                PoolHashrate decimal(10,2)
            );"
        );
        
        // Attempt to GET, decode, and format footer data
        $context = stream_context_create([
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
            ]
        ]);
        $difficulty = json_decode(file_get_contents(difficultyURL, false, $context), true);
        if($difficulty) {
            $difficulty = number_format(
                ($difficulty[0]["difficulty"] / tHashOffset), 2, '.', ''
            );
        }

        $poolhashrate = json_decode(file_get_contents(poolstatsURL), true);
        if($poolhashrate) {
            $poolhashrate = number_format(
                ($poolhashrate["data"]["poolStats"]["hashRate"] / tHashOffset), 2, '.', ''
            );
        }

        // Grab the current time for DB updated time
        $lastUpdate = time();

        // Ensure the footer data exists in the DB
        $result = mysqli_query($conn, "SELECT * FROM Footer");
        
        // Update the records if they exist
        if(mysqli_num_rows($result) > 0) {
            $sql = 
            "UPDATE Footer
            SET
                LastUpdate = '$lastUpdate',
                Difficulty = '$difficulty',
                PoolHashrate = '$poolhashrate'
            ";
        }
        else { // Create new records if they don't exist
            $sql = 
            "INSERT INTO Footer (
                LastUpdate,
                Difficulty,
                PoolHashrate
            ) 
            VALUES (
                '$lastUpdate',
                '$difficulty',
                '$poolhashrate'
            )";
        }
        mysqli_free_result($result);
        mysqli_query($conn, $sql);
    }
?>