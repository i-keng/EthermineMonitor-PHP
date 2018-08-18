<?php
    function renderFooter(&$conn) {
        $lastUpdate = date(timeFormat, time());

        $footer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Footer"));
        $dbupdate = date(timeFormat, $footer["LastUpdate"]);
        $difficulty = $footer["Difficulty"];
        $poolhashrate = $footer["PoolHashrate"];

        print <<< FOOTER
        
        <div id="footer">
            <div id="footerData">
                <div class="footer">Database Updated: $dbupdate</div>
                <div class="footer">Page Updated: $lastUpdate</div>
                <div class="footer">Current Difficulty: $difficulty Th</div>
                <div class="footer">Pool Hashrate: $poolhashrate Th/s</div>
            </div>
        </div>

FOOTER;
    }
?>