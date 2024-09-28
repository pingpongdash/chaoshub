<?php
require_once 'GeneratorBase.php' ;
/*************************************************************************
    Copyright 2020  HALCYON CYBERNETICS

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
**************************************************************************/
require_once 'GeneratorBase.php' ;

class RandomStringGeneratrer extends GeneratorBase {
    
 	public function __construct() {
	}

    public function generate(?string $seed=RANDOMSEED,?int $length=DEFAULT_RANDOM_LENGTH): string {
        $strarray = preg_split("//", $seed, 0, PREG_SPLIT_NO_EMPTY) ;
        $randomstr = "" ;
        for ($index = 0 ; $index < $length ; $index++) {
            $randomstr .= $strarray[array_rand($strarray ,1)] ;
        }
        return $randomstr ;
    }
}
