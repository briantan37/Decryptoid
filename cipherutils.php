<?php
    // $tmp = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    // echo str_shuffle($tmp)."<br>";
    interface Strategy {
        public function encrypt($input, $key);
        public function decrypt($input, $key);
    }
    class Cipher
    {
        function __construct($strategy, $key, $input) {
            $this->key = $key;
            $this->strategy = $strategy;
            $this->input = $input;
        }

    }

    class Substitution
    {
        function encrypt($input, $key) {
            $output = "";
            $keyArr = str_split($key);
            $inArr = str_split(strtoupper($input));
            foreach ($inArr as $element) {
                if(ctype_alpha($element)) {
                    $asciiValue = ord($element);
                    $output .= $keyArr[$asciiValue - RESET_ASCII_VALUE];
                }
                else {
                    $output .= $element;
                }
            }
            return $output;
        }

        public function decrypt($input, $key) {
            $output = "";
            $keyArr = str_split($key);
            $inArr = str_split(strtoupper($input));
            foreach ($inArr as $element) {
                if(ctype_alpha($element)) {
                    $index = array_search($element, $keyArr);
                    $output .= chr($index + RESET_ASCII_VALUE);
                }
                else {
                    $output .= $element;
                }
            }
            return $output;
        }
    }
    class DTransposition
    {
        
        public function encrypt($input, $key) 
        {

        }

        public function decrypt($input, $key)
        {

        }

        private function create2DArray($key) {
            $key1 = explode(",", $key[0]);
            $key2 = explode(",", $key[1]);
            $arr = array_fill(0, count($key1),"1");
            for($i = 0; $i < count($arr); $i++) {
                $tmpArr = array_fill(0, count($key2), "2");
                $arr[$i] = $tmpArr;
            }
        }
    }

    $dtrans = new DTransposition;
    $key = array("1,2,3,4","2,4,5,3");
    $dtrans->encrypt("BJKSA", $key);
    // define("RESET_ASCII_VALUE", 65);
    // $sub = new Substitution;
    // echo $sub->decrypt("USXHKWUHDXNETXUW", "UEOQDGYSXCFPHWVJRTNIAMBZKL");
?>