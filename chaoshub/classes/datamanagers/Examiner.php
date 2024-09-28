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

class Examiner extends ObjectBase {

    public function __construct() {
        $this->userAgent = get_browser(null, true) ;
    }

    public function examine(array $inspectionItems) {
        $result['status'] = 'allowed' ;
        if(!empty($inspectionItems['useragent'])) {
            $result = $this->examineUserAgent($inspectionItems['useragent']) ;
        }
        return $result ;
    }

    private function examineUserAgent(&$inspectionItems): array {
        $result['status'] = 'allowed' ;
        if($this->userAgent === false) {
            $result['arise']  = 'get_browser()' ;
            $result['cause']  = 'get_browser failed.' ;
            $result['status'] = 'denied' ;
            return $result ;
        }
        if(empty($inspectionItems)) return $result ;
        foreach($inspectionItems as $key => $inspectionItem) {
            if(isset($inspectionItem['deny'])) {
                $denied = $this->checkDenied($key ,$inspectionItem['deny']) ;
                if($denied) {
                    $result['arise']  = $key ;
                    $result['cause']  = $this->userAgent[$key] ;
                    $result['status'] = 'denied' ;
                    break ;
                }
            }
            if(isset($inspectionItem['allow'])) {
                $denied = $result['status'] = $this->checkAllowed($key ,$inspectionItem['allow']) ;
                if($denied) {
                    $result['arise']  = $key ;
                    $result['cause']  = $this->userAgent[$key] ;
                    $result['status'] = 'denied' ;
                    break ;
                }
            }
        }
        return $result ;
    }

    private function checkAllowed(string $key ,array|string $allowed): bool {
        $result = true ;
        if(is_array($allowed)) {
            if(in_array('any',$allowed,true) ||
               in_array($this->userAgent[$key],$allowed,true) ) {
                $result = false ;
            }
        }
        else {
            if($allowed === 'any' ||
               $this->userAgent[$key] === $allowed ) {
                $result = false ;
            }
        }
        return $result ;
    }

    private function checkDenied(string $key, array|string $reject): bool {
        $result = false ;
        if(is_array($reject)) {
            if(in_array('any',$reject,true) ||
               in_array($this->userAgent[$key],$reject,true) ) {
                $result = true ;
            }
        }
        else {
            if( $reject === 'any' ||
                $this->userAgent[$key] === $reject ) {
                $result = true ;
            }
        }
        return $result ;
    }
}


