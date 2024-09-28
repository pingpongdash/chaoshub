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

class HTTPProcessor extends ObjectBase {

    public function __construct() {
        parent::__construct() ;
    }

    public function post(string $url,?array $post) :string {
    	$response = '' ;
        $response = $this->query($url,$post) ;
        return $response ;
    }
    
    public function get(string $url) :string {
        $response = '' ;
        $response = $this->query($url,null) ;
        return $response ;
    }

    private function query(string $url,array $postParam,bool $post=true): string {
        $CURLERR = NULL ;
        $curl    = curl_init($url) ;
        if($post) {
            curl_setopt($curl, CURLOPT_POST, TRUE) ;
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postParam)) ;
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE) ;
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['user-agent: '.HTTP_USER_AGENT]) ;
        $response = curl_exec($curl) ;
        if(curl_errno($curl)){
            $error = array('curl_errno'=>curl_errno($curl)  ,
                           'curl_error'=>curl_error($curl)) ;
            return json_encode($error) ;
        }
        curl_close($curl) ;
        return $response  ;
    }
}
