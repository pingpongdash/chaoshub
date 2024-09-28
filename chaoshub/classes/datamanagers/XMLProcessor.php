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

class XMLProcessor extends ObjectBase {
    public function __construct() {
    }

    public function save(string $path, string $name, array $data ,
                string $rootNode='root', bool $format=true) {
        $path = $this->getPath($path) ;
        $name = $this->getFileName($name) ;
        $xml = new SimpleXMLElement('<?xml version="1.0"?><'.$rootNode.'/>') ;
        $this->array2XML($data,$xml,$rootNode) ;
        if(!file_exists($path)) {
            mkdir($path, 0766, true) ;
        }
        $dom = dom_import_simplexml($xml)->ownerDocument ;
        $dom->formatOutput = $format ;
        return $dom->save($path.$name) ;
    }

    public function load(string $path, string $name): array|bool {
        $path = $this->getPath($path) ;
        $name = $this->getFileName($name) ;
        $xml = simplexml_load_file($path.$name) ;
        if($xml === false) {
            return $xml ;
        }
        $haystack = array() ;
        try{
            $haystack = json_decode(json_encode($xml),TRUE) ;
            $haystack = $this->drilling($path,$haystack) ;
        }
        catch(Exception $e) {
            $haystack = [
                'status' => 'ERROR' ,
                'path'   => $path ,
                'file'   => $name ,
            ] ;
        }
        return $haystack ;
    }

    private function drilling(string $storepath,array &$haystack): array {
        foreach($haystack as $key => &$value) {
            if(is_array($value)) {
                if(isset($value['external']) &&
                   strtolower($value['external']) === 'yes' ) {
                    $haystack[$key] = $this->readExternal($storepath,$value) ; 
                }
                else {
                    $value = $this->drilling($storepath,$value) ;
                }
            }
            else {
                $haystack[$key] = $value ;
            }
        }
        return $haystack ;
    } 

    private function readExternal(string $storepath ,array $value){
        if(strtolower($value['drilling']) === 'no') {
            $value['body'] = file_get_contents(
                $storepath.$value['path'].$value['file']) ;
            unset($value['external']) ;
            unset($value['drilling']) ;
            unset($value['path']) ;
            unset($value['file']) ;
            return $value ;
        }
        else {
            $value = $this->load($storepath.$value['path'],$value['file']) ;
        }
        return $value ;
    }

    private function array2XML(array $data ,?SimpleXMLElement &$xml ,
        ?string $nodeName='',?array $keyarray=null) {
        $xml = (is_null($xml))?
            new SimpleXMLElement('<?xml version="1.0"?><'.$nodeName.'/>'):$xml ;
        foreach ($data as $key => $value) {
            if(is_numeric($key)) {
                $key = (isset($keyarray[$nodeName]))?
                            $keyarray[$nodeName]:'element' ;
            }
            if(is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->array2XML($value, $subnode,$key,$keyarray);
            }
            else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }

    private function checkExsitance(string $path, string $filename): bool {
        $path = $this->getPath($path) ;
        $exists = false ;
        $exists = file_exists($path.$filename) ;
        return $exists ;
    }

    public function remove(string $path, string $filename): bool {
        $path = $this->getPath($path) ;
        return unlink($path.$filename) ;
    }

    public function moveto(string $path, string $filename,string $moveto): bool {
        $path   = $this->getPath($path) ;
        $moveto = $this->getPath($moveto) ;
        $result = false ;
        if(!file_exists($path.$moveto)) {
            mkdir($path.$moveto, 0766, true) ;
        }
        if(!file_exists($path.$filename)){
            return $result ;
        }
        $oldName = $path.$filename ;
        $newName = $path.$moveto.$filename.'_'.strtotime('now') ;
        try{
            $result = rename($oldName ,$newName) ;
            if(!$result) {
                $result = copy($oldName ,$newName) ;
                $result = unlink($oldName) ;
            }
        }
        catch(Exception $e) {
            exec('mv '.escapeshellarg($oldName).' '.escapeshellarg($newName));
        }
        return $result ;
    }

    public function trash(string $path, string $filename): bool {
        $result = $this->moveto($path,$filename,'trash') ;
        return $result ;
    }

    private function getPath(string $path) {
        $path .= (substr($path, -1) === DIRECTORY_SEPARATOR)?'':DIRECTORY_SEPARATOR ;
        return $path ;
    }
    private function getFileName(string $name) {
        $name .= ( substr($name, -4) === '.xml')?'':'.xml' ;
        return $name ;
    }
}

