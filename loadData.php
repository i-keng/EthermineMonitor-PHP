<?php
    require_once "config.php";
    require_once "classes/Wallet.php";
    require_once "classes/Value.php";
    require_once "renderCryptocurrencies.php";
    require_once "renderAddresses.php";

    class Data {
        private $wallets;
        private $values;

        function loadData() {
            $this->wallets = array();
            $this->values = array();

            
            foreach(ADDRESSES as $name => $address) {
                $wallet = new Wallet($name, $address);
                $wallet->setCurrentStats(json_decode(file_get_contents(ethermineAPI . $address . "/currentStats"), true));
                $wallet->setHistory(json_decode(file_get_contents(ethermineAPI . $address . "/history"), true));
                array_push($this->wallets, $wallet);
            }

            foreach(CRYPTOCURRENCIES as $currency) {
                $value = new Value($currency);
                $valueURL = cryptocompareAPI . "price?" . "fsym=" . $currency . "&tsyms=";
                $historyURL = cryptocompareAPI . "histoday?" . "fsym=" . $currency . "&tsym=" . FIAT[0] . "&limit=" . (valueHistoryLimit - 1);
                foreach(FIAT as $fiat) {
                    $valueURL .= "$fiat,";
                }
                foreach(CRYPTOCURRENCIES as $crypto) {
                    if($crypto != $currency)
                        $valueURL .= "$crypto,";
                }
                $value->setValue(json_decode(file_get_contents($valueURL), true));
                $value->setHistory(json_decode(file_get_contents($historyURL), true));
                array_push($this->values, $value);
            }
        }

        function getWallets() {
            return $this->wallets;
        }
        function getValues() {
            return $this->values;
        }
    }

    // Create connection
    $conn = mysqli_connect(DBServer, DBUser, DBPass, DBName);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    //$data = new Data;
    //$data->loadData();
    //$wallets = $data->getWallets();
    //$values = $data->getValues();
    //renderCryptocurrencies($values);
    renderAddresses($conn);
    mysqli_close($conn);
?>