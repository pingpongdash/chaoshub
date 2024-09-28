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

require_once VENDOR_DIR.'autoload.php' ;
use Symfony\Component\Yaml\Exception\ParseException ;
use Symfony\Component\Yaml\Yaml ;

class YAMLProcessor extends ObjectBase {
    public function __construct() {
    }

    public function save(string $path, string $name, array $data , bool $format=true): bool {
        $path = $this->getPath($path) ;
        $name = $this->getFileName($name) ;
        $yaml = Yaml::dump($data) ;
        if(!file_exists($path)) {
            mkdir($path, 0766, true) ;
        }
        return file_put_contents($path.$name, $yaml) !== false;
    }

    public function load(string $path, string $name): array|bool {
        $path = $this->getPath($path) ;
        $name = $this->getFileName($name) ;
        $haystack = $this->loadYaml($path.$name) ;
        if($haystack === false) {
            return false ;
        }
        try {
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
                    if(!empty($value)) {
                        $value = $this->drilling($storepath,$value) ;
                    }

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

    private function checkExsitance(string $path, string $filename): bool {
        $path = $this->getPath($path) ;
        return file_exists($path.$filename) ;
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
        try {
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
        return $this->moveto($path,$filename,'trash') ;
    }

    private function getPath(string $path): string {
        return $path .= (substr($path, -1) === DIRECTORY_SEPARATOR)?'':DIRECTORY_SEPARATOR ;
    }

    private function getFileName(string $name): string {
        return $name .= (substr($name, -5) === '.yaml' || substr($name, -4) === '.yml') ? '' : '.yaml';
    }

    private function loadYaml($yaml) {
        try {
            $contents = Yaml::parseFile($yaml) ;
            return $contents ;
        } catch (ParseException $e) {
            echo "failed" ;
        }
    }

}
