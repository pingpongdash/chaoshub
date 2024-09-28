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

require_once 'ApplicationBase.php' ;
require_once 'UUIDGenerater.php' ;

class ModuleBase extends ApplicationBase {

    protected string $namespace = '' ;

    public function __construct(?array $permissions=null) {
        parent::__construct($permissions) ;
    }

    protected function respond() {}

    const ELEMENT = [
        'js' => [
            'element' => 'script'          ,
            'type'    => 'text/javascript' ,
        ] ,
        'css' => [
            'element' => 'style'          ,
            'type'    => 'text/css' ,
        ] ,
        'div' => [
            'element' => 'div'          ,
            'type'    => '' ,
        ] ,
        'html' => [
            'element' => 'div'          ,
            'type'    => '' ,
        ] ,
    ] ;

    private function parseTemplate(
        string $class,string $template,?array $display): array  {
        $filepath = pathinfo($template);
        $contents = [] ;
        $uuidgen = new UUIDGenerater() ;
        $uuid = $uuidgen->generate() ;
        $display['uuid'] = $uuid ;
        $display['namespace'] = (!empty($this->namespace))?$this->namespace.'.':'window.' ;
        $html = $this->getContents(
            $filepath['dirname'],$filepath['basename'],$display) ;
        $contents         = self::ELEMENT[$filepath['extension']] ;
        $contents['id']   = $uuid                                 ;
        $contents['html'] = $html                                 ;
        return $contents ;
    }

    protected function getBundle(
        string $class,string $path,?array $display): array {
        $suite = ['elements' => [],] ;
        $suite = [] ;
        foreach(['.twig.html','.twig.js','.twig.css'] as $extension) {
            if(file_exists($path.$class.$extension)) {
                $suite['elements'][] = $this->parseTemplate(
                    $class ,$path.$class.$extension , $display);
            }
        }
        return $suite ;
    }
}
