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

require_once VENDOR_DIR.'autoload.php' ;

trait DocumentBase {
    protected $twig   ;
    protected $loader ;

    public function getContents(array|string $paths,string $template,array $displayData,):string {
        $contents = '' ;
        if(!is_array($paths)) {
            $paths = array($paths) ;
        }
        foreach($paths as &$path) {
            $path .= (substr($path, -1)===DIRECTORY_SEPARATOR)?'':DIRECTORY_SEPARATOR;
        }
        try {
            $this->loader = new \Twig\Loader\FilesystemLoader($paths) ;
            $this->twig   = new \Twig\Environment($this->loader,TWIG_SETTINGS) ;
            $contents = $this->twig->render($template,$displayData) ;
        }
        catch(Exception $e) {
            $contents = $e->getMessage() ;
        }
        finally {
            return $contents ;
        }
    }

    public function getPaths(string $root,array &$paths=[]): ?array {
        $root .= (substr($root,-1)===DIRECTORY_SEPARATOR)?'':DIRECTORY_SEPARATOR;
        array_push($paths,$root) ;
        if ($directoryHandler = opendir($root)) {
            while (($directory = readdir($directoryHandler)) !== false) {
                if($directory === '.' || $directory === '..') continue ;
                if (is_dir($root.$directory)) {
                    $this->getPaths($root.$directory,$paths) ;
                }
            }
        }
        closedir($directoryHandler) ;
        return $paths ;
    }
}
