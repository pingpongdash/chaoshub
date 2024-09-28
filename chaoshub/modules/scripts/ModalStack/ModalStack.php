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
require_once 'ModuleBase.php' ;
require_once 'UUIDGenerater.php' ;

class ModalStack extends ModuleBase {

    public function __construct(?array $permissions=null) {
        parent::__construct($permissions) ;
    }

    public function getSuite(?string $namespace=''): ?array {
        $this->namespace = $namespace ;
        $templatedir  = __DIR__.'/templates/' ;
        $templatebase = __CLASS__ ;
        $display  = [
            'objectname'     => __CLASS__        ,
        ] ;
        $suite = $this->getBundle(__CLASS__,$templatedir,$display) ;
        return $suite ;
    }
}


