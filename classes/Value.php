<?php
    class Value {
        private $coin;
        private $value = array();
        private $history = array();

        //Constructor
        function __construct($coin) {
            $this->coin = $coin;
        }

        // Get coin
        function getCoin() {
            return $this->coin;
        }

        // Get and Set value
        function getValue() {
            return $this->value;
        }
        function setValue($value) {
            $this->value = $value;
        }

        // Get and Set history
        function getHistory() {
            return $this->history;
        }
        function setHistory($history) {
            $this->history = $history;
        }
    }
?>