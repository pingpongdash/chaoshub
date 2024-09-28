<?php
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
declare(strict_types=1) ;
require_once 'ModuleBase.php' ;
require_once 'HTTPProcessor.php' ;


class GreCAPTCHA extends ModuleBase {
    public function __construct(?array $permissions=null) {
        parent::__construct($permissions) ;
    }

    protected function respond() {}

    public function verifyToken($secret ,$token ,$remote) {
        $htproc = new HTTPProcessor() ;
        $response = $htproc->post(reCAPTCHA_VERIFY_URL,[
            'secret'   => $secret ,
            'response' => $token ,
            'remoteip' => $remote ,
        ]) ;
        $response = json_decode($response,true) ;
        if($response['success']) {
            $response['status'] = 'OK' ;
        }
        else {
            $response['status'] = 'ERROR' ;
        }
        return $response ;
    }

    public function getSuite(?string $namespace=''): ?array {
        $this->namespace = $namespace ;
        $templatedir  = __DIR__.'/templates/' ;
        $templatebase = __CLASS__ ;
        $display  = [
            'objectname' => __CLASS__  ,
            'sitekey_v2' => reCAPTCHA_SITE_KEY_v2 ,
            'sitekey_v3' => reCAPTCHA_SITE_KEY_v3 ,
        ] ;
        $suite = $this->getBundle(__CLASS__,$templatedir,$display) ;
        return $suite ;
    }
}


