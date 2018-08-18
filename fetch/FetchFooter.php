<?php
    function FetchFooter(&$conn) {
        $lastUpdate = time();
        $difficulty = json_decode(file_get_contents(difficultyURL), true)[0]["difficulty"];
        $poolhashrate = json_decode(file_get_contents(poolstatsURL), true)["data"]["poolStats"]["hashRate"];
        
        $difficulty /= tHashOffset;
        $difficulty = number_format($difficulty, 2, '.', '');
        
        $poolhashrate /= tHashOffset;
        $poolhashrate = number_format($poolhashrate, 2, '.', '');

        // Ensure tables exist
        mysqli_query($conn, 
            "CREATE TABLE IF NOT EXISTS Footer(
                LastUpdate int,
                Difficulty decimal(10,2),
                PoolHashrate decimal(10,2)
            );"
        );

        $result = mysqli_query($conn, "SELECT * FROM Footer");
        
        if(mysqli_num_rows($result) > 0) {
            $sql = 
            "UPDATE Footer
            SET
                LastUpdate = '$lastUpdate',
                Difficulty = '$difficulty',
                PoolHashrate = '$poolhashrate'
            ";
        }
        else {
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
        
        if(!mysqli_query($conn, $sql)) {
            echo "<p>" . mysqli_error($conn) . "</p>";
        }
    }
?>