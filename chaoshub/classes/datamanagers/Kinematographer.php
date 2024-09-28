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

class Kinematographer extends ObjectBase {
    public function __construct() {
    }

    private function getDataAsb64(string $path, string $filename, string $cachePath): string {
        $b64 = '' ;
        if(file_exists($cachePath.$filename.'.b64')) {
            $b64  = file_get_contents($cachePath.$filename.'.b64') ;
        }
        else {
            $b64 = base64_encode(
                file_get_contents($path.$filename)) ;
            if(!file_exists($cachePath)) {
                mkdir($cachePath, 0766, true) ;
            }
            file_put_contents($cachePath.$filename.'.b64',$b64) ;
        }
        return $b64 ;
    }

    public function getAsArray(string $path, string $filename, string $cachePath):array {
        $path      = $this->getPath($path) ;
        $cachePath = $this->getPath($cachePath) ;
        $mime  = $this->getMimeType($path.$filename) ;
        $b64   = $this->getDataAsb64($path,$filename,$cachePath) ;
        $data = [
            'mime'  => $mime ,
            'image' => $b64  ,
        ] ;
        return $data ;
    }
    public function getAsString(string $path, string $filename,string $cachePath): string {
        $path      = $this->getPath($path) ;
        $cachePath = $this->getPath($cachePath) ;
        $mime  = $this->getMimeType($path.$filename) ;
        $b64   = $this->getDataAsb64($path,$filename,$cachePath) ;
        $data =  'data:'.$mime.';base64,'.$b64.'' ;
        return $data ;
    }

    private function getMimeType($filename): string|bool {
        $mime_type = '' ;
        try{
            if(function_exists('finfo_open') && function_exists('finfo_file')) {
                $finfo     = finfo_open(FILEINFO_MIME_TYPE) ;
                $mime_type = finfo_file($finfo, $filename)  ;
                finfo_close($finfo) ;
            }
            else {
                $mime_type = mime_content_type($filename) ;
            }
        }catch(Exception $e){
            // NOP
        }
        return $mime_type ;
    }

    private function getPath(string $path) {
        $path .= (substr($path, -1) === DIRECTORY_SEPARATOR)?'':DIRECTORY_SEPARATOR ;
        return $path ;
    }

}
