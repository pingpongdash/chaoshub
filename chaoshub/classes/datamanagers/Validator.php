<?php
declare(strict_types=1) ;
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
require_once 'ObjectBase.php' ;

class Validator extends ObjectBase {
    public function __construct() {
    }

    public function validate(string $domain,array $target): bool {
        $validity = false ;
        switch($domain) {
        case 'email' :
            $validity = $this->validateEmail($target) ;
            break ;
        case 'regex' :
            $validity = $this->validateRegex($target) ;
            break ;
        default :
            $validity = false ;
        }
        return $validity ;
    }

    private function validateEmail(array $target): bool {
        $valid   = false ;
        $email   = $target['email']   ;
        if(isset($target['confirm'])) {
            $confirm = $target['confirm'] ;
            $valid = (
                ( !empty($email) && !empty($confirm) ) &&
                ($email === $confirm)                  &&
                preg_match(EMAIL_REG, $email) ) ;
        }
        else {
            $valid = 
                (!empty($email) && preg_match(EMAIL_REG, $email)) ;
        }
        return $valid ; 
    }

    private function validateRegex(array $target): bool {
        $valid  = false ;
        $regex  = $target['regex']   ;
        $target = $target['target'] ;
        $valid  = preg_match($regex, $target) ;
        return $valid ; 
    }
}

