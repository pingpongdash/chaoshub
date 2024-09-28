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
require_once 'Examiner.php' ;

class ResponderBase extends ObjectBase {

    protected $client    ;
    protected $post      ;

    protected function __construct(?array $permissions=null) {
        parent::__construct() ;
        $this->post        = isset($_POST)?$_POST:null;
        $this->permissions = $permissions ;
        if(!empty($this->permissions)) {
            $examiner = new Examiner() ;
            $result = $examiner->examine($this->permissions) ;
            if($result['status']==='denied') {
                $this->forward('AgentError') ;
            }
        }
    }

    protected function sendText(string $response) {
        $this->setAllowedOrigin() ;
        header("Cache-Control: no-cache, must-revalidate");
        header("Feature-Policy: autoplay 'self'; camera 'self'; microphone 'self'");
        echo $response ;
        exit ;
    }

    protected function sendObject(array $response) {
        $this->sendJson($response) ;
    }

    protected function sendJson(array $response) {
        $response = json_encode($response);
        $this->setAllowedOrigin();
        header("Content-Type: application/json; charset=UTF-8");
        header("Cache-Control: no-cache, must-revalidate");
        header("Feature-Policy: autoplay 'self'; camera 'self'; microphone 'self'");
        echo $response;
        exit;
    }

    protected function sendError(string $errorcode,?array $data=null) {
        $response = [
            'status'      => 'ERROR'    ,
            'code'        => $errorcode ,
            'description' => RESPONSES[$errorcode] ,
            'data'        => $data      ,
        ] ;
        $this->sendObject($response) ;
    }

    protected function sendArray(array $response){
        try{
            if(is_array($response)) {
                $this->sendObject($response) ;
            }
        }
        catch (Exception $e) {
            throw $e ;
        }
    }

    protected function forward(string $class, ?array $permissions=null) {
        require_once $class.'.php' ;
        $responder = new $class($permissions) ;
        $responder->respond() ;
        exit ;
    }

    private function setAllowedOrigin() {
        $origin = '' ;
        if(isset($_SERVER['HTTP_ORIGIN'])) {
            $origin = $_SERVER['HTTP_ORIGIN'] ;
        }
        if (defined('ALLOWED_ORIGINS') && in_array($origin, ALLOWED_ORIGINS)) {
            header("Access-Control-Allow-Origin: $origin");
        }
    }
}
