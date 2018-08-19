<?php
    function renderFooter(&$conn) {
        // Prepare and render container divs and current time
        $lastUpdate = date(timeFormat, time());
        print <<< TopDiv
    <div id="footer">
        <div id="footerData">
            <div class="footer">Page Updated: $lastUpdate</div>

TopDiv;

        // Attempt to retrieve and render remaining footer data
        if($footer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Footer"))) {
            $dbupdate = date(timeFormat, $footer["LastUpdate"]);
            $difficulty = $footer["Difficulty"];
            $poolhashrate = $footer["PoolHashrate"];

            print <<< MiddleDivs
            <div class="footer">Database Updated: $dbupdate</div>
            <div class="footer">Current Difficulty: $difficulty Th</div>
            <div class="footer">Pool Hashrate: $poolhashrate Th/s</div>

MiddleDivs;
        }

        // Close container divs
        print <<< BottomDiv
        </div>
    </div>

BottomDiv;
    }
?>