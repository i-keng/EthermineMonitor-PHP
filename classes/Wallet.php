<?php
    class Wallet {
        private $name;
        private $address;
        private $currentStats = array();
        private $history = array();

        // Constructor
        function __construct($name, $address) {
            $this->name = $name;
            $this->address = $address;
        }

        // Get name and address
        function getName() {
            return $this->name;
        }
        function getAddress() {
            return $this->address;
        }

        // Set and Get current stats
        function setCurrentStats($stats) {
            $this->currentStats = $stats;
        }
        function getCurrentStats() {
            if($this->currentStats) {
                return $this->currentStats["data"];
            }
        }

        // Set and Get history
        function setHistory ($history) {
            $this->history = $history;
        }
        function getHistory() {
            return $this->history;
        }
    }
?>