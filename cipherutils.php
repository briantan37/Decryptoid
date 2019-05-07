<?php
    define("RESET_ASCII_VALUE", 65);

//    interface Strategy {
//        public function encrypt($input, $key);
//        public function decrypt($input, $key);
//    }
//    class Cipher
//    {
//        function __construct($strategy, $key, $input) {
//            $this->key = $key;
//            $this->strategy = $strategy;
//            $this->input = $input;
//        }
//
//    }

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
            return $this->swap($key, $arr, count($arr), false);
        }

        public function decrypt($input, $key)
        {
            $arr = $this->parseTo2DArray($input);
            return $this->swap($key, $arr, count($arr), true);
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

        private function swap($key, $arr, $len, $reverse) {
            $key1 = explode(",", $key[0]);      //key to switch the rows
            $key2 = explode(",", $key[1]);      //key to switch the column;
            $output = array();

            //Start switching the rows
            if(count($key1) == $len && count($key2) == $len){
                for($i = 0; $i < $len; $i++) {
                    $leftVar = $reverse ? (int) $key1[$i] : $i;
                    $rightVar = $reverse ? $i : (int) $key1[$i];
                    $output[$leftVar] = $arr[$rightVar];
                }
                $arr = $output;
                for($i = 0; $i < $len; $i++) {
                    for($j = 0; $j < $len; $j++) {
                        $leftVar = $reverse ? (int) $key2[$i] : $i;
                        $rightVar = $reverse ? $i : (int) $key2[$i];
                        $output[$j][$leftVar] = $arr[$j][($rightVar)];
                    }
                }
            }
            $tmpArr = array();
            for($i = 0; $i< $len; $i++) {
                $tmpArr[] = implode($output[$i]);
            }

            return implode($tmpArr);
        }
    }

    class RC4 {
        public function encrypt($input, $key)
        {
            /**Initialization of RC4 **/
            $keyByteArr = $this->changeToBytes($key);
            $S = array();       //Array that holds the state of RC4; the permutation of 0-255
            $K = array();       //Array that holds the key
            for($i = 0; $i <= 255; $i++) {
                $S[$i] = $i;
                $K[$i] = $keyByteArr[$i % count($keyByteArr)];
            }
            $j = 0;
            for($i = 0; $i <= 255; $i++) {
                $j = ($j + $S[$i] + $K[$i]) % 256;

                $tmp = $S[$i];
                $S[$i] = $S[$j];
                $S[$j] = $tmp;
            }
            $i = 0;
            $j = 0;
            $output = "";
            $inputByteArr = $this->changeToBytes($input);

            /**Keystream**/
            for($x = 0; $x < count($inputByteArr); $x++) {
                $i = ($i + 1) % 256;
                $j = ($j + $S[$i]) % 256;

                $tmp = $S[$i];
                $S[$i] = $S[$j];
                $S[$j] = $tmp;

                $t = ($S[$i] + $S[$j]) % 256;
                $keyStreamByte = $S[$t];
                $output .= chr($keyStreamByte ^ $inputByteArr[$x]);
            }
            return $output;
        }

        public function decrypt($input, $key)
        {
            return $this->encrypt($input, $key);
        }

        private function changeToBytes($input)
        {
            $arr = array();
            $byte = unpack('C*', $input, 0);
            foreach($byte as $element) {
                $arr[] = $element;
            }
            return $arr;
        }
    }

//    $rc4 = new RC4;
//    $tmp = $rc4->encrypt("Hi my name is Brian Tan!", "d2x040Ws3K");
//    echo $tmp;
//    echo $rc4->decrypt($tmp, "d2x040Ws3K");
//    $dtrans = new DTransposition;
//    //            c,e,f,a,b,d
//    $key = array("2,4,5,0,1,3","2,4,5,3,0,1");
//    //$input = "aaaaaabbbbbbccccccddddddeeeeeeffffff";
//    $input = "briantan37@gmail.com brian tan 12345";
//    $var = $dtrans->encrypt($input, $key);
//    echo $var;
//    echo "<br>";
//    echo $dtrans->decrypt($var, $key);
    // define("RESET_ASCII_VALUE", 65);
    // $sub = new Substitution;
    // echo $sub->decrypt("USXHKWUHDXNETXUW", "UEOQDGYSXCFPHWVJRTNIAMBZKL");
?>