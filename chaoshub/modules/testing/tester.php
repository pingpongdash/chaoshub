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
declare(strict_types=1);

require_once APP_DIR.'cominclude.php' ;
require_once 'Document.php' ;
require_once 'ClientChecker.php' ;
require_once 'StyleRoomList.php' ;
require_once 'ListManager.php' ;

class tester extends Document {
    public function __construct(?array $post) {
        parent::__construct($post) ;
        $this->htdb = new HTTP_DB_Client() ;
    }

    // public function setPlatforms(array $platforms) {
    //     $this->platforms = $platforms ;
    // }

    public function respond() {
        // $this->getModuleContent() ;
        // $this->getModuleContent() ;
        $this->getDock() ;
    }
//     public function getScript(): string {
//         $contents = '' ;
//         $htdb = new HTTP_DB_Client() ;
//         $params = [
//             'request'   => 'script'          ,
//             'component' => 'Dock' ,
//             'items'     => DOCK_ITEMS       ,] ;
//         $response = json_decode($htdb->query($params),true) ;
// var_dump($response) ;
// exit ;
//         $contents = $response['contents'] ;
//         return $contents ;
//     }    

    public function getDock() {
        $contents = '' ;
        $htdb = new HTTP_DB_Client() ;
        $params = [
            'request'   =>'contents'        ,
            'component' => 'Dock' ,
            'items'     => DOCK_ITEMS       ,] ;
        $response = json_decode($htdb->query($params),true) ;
        $contents = $response['contents'] ;
var_dump($contents) ;
        return $contents ;
    }

//     private function  getModuleScripts(): string {
//         $component  = 'ComCat' ;
//             $params = [
//                 'request'   => 'script' ,
//                 'component' => $component , 
//             ] ;
//             $response = $this->query($params) ;
//             $response = json_decode($response,true) ;
// print_r($response) ;
// exit ;
//             // if($response['status'] == 'OK') {
//             //     $components .= $response['contents'] ;
//             // }
//         return $response ;
//     }

//     private function getModuleContent(): string {
//         $component  = 'CookieMonster' ;
//             $params = [
//                 'request'   => 'contents' ,
//                 'component' => $component , 
//             ] ;
//             $response = $this->query($params) ;
//             $response = json_decode($response,true) ;
// print_r($response) ;
// exit ;
//             // if($response['status'] == 'OK') {
//             //     $components .= $response['contents'] ;
//             // }
//         return $response ;
//     }



}

