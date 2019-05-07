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

            $arr = $this->parseTo2DArray($input);
            $this->swap($key, $arr, count($arr));
        }

        public function decrypt($input, $key)
        {

        }

        private function parseTo2DArray($input) {
            $len = strlen($input);
            //create two each factor for length and get the ceiling to not lose some values
            $factors = ceil(sqrt($len));
            //split the input to array of characters
            $inputArr = str_split($input);
            //create an index variable
            $index = 0;

            //the 2D array to output
            $arr = array();
            //Nested for loop that creates multiple arrays for the output array
            for($i = 0; $i < $factors; $i++) {
                $tmpArr = array();
                //This inner loop parses each character from the input array and stores them in temporary array
                for($j = 0; $j < $factors; $j++) {
                    //Check if the index is not greater than the len of the input to not get OutOfBoundsException
                    if($index < $len) {
                        $tmpArr[] = $inputArr[$index];
                        $index++;
                    }
                    //if it is, just insert space
                    else {
                        $tmpArr[] = " ";
                    }

                }
                //Store the temporary array in the output array
                $arr[] = $tmpArr;
            }
            return $arr;
        }

        private function swap($key, $arr, $len) {
            $key1 = explode(",", $key[0]);      //key to switch the rows
            $key2 = explode(",", $key[1]);      //key to switch the column;
            $output = array();
            $swapFlag = (count($key1) < $len) ? true : false;
            //Start switching the rows
            for($i = 0; $i < $len; $i++) {
                //make sure that the value of i does not exceed the length of key1
                if($i < count($key1)) {
                    $keyIndex = (int) $key1[$i];
                    if($swapFlag) {                       //if the key's index is less than the len of the array
                        $output[$keyIndex] = $arr[$i];       //Replace the key index to the array's index
                    }
                    else {
                        $output[$i] = $arr[$keyIndex];        //store the array of the key's index at the current index
                    }
                }

            }
            $swapFlag = (count($key2) < $len) ? true : false;
            for($j = 0; $j < $len; $j++) {
                if($j < count($key2)) {
                    $keyIndex = (int) $key2[$i];
                    if($swapFlag) {

                    }
                }
            }
        }
    }

    $dtrans = new DTransposition;
    //            c,e,f,a,b,
    $key = array("2,4,5,0","2,4,5,3");
    $input = "aaaaaabbbbbbccccccddddddeeeeeeffffff";
    $dtrans->encrypt($input, $key);
    // define("RESET_ASCII_VALUE", 65);
    // $sub = new Substitution;
    // echo $sub->decrypt("USXHKWUHDXNETXUW", "UEOQDGYSXCFPHWVJRTNIAMBZKL");
?>