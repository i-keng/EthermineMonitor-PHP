<?php
    function renderFooter(&$conn) {
        $lastUpdate = date(timeFormat, time());

        print <<< TIME
        
        <div id="footer">
            <div id="updatedPage">
                <h4>Page Last Updated: $lastUpdate</h4>
            </div>
        </div>

TIME;
    }
?>