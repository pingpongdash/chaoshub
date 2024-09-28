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
require_once 'ResponderBase.php' ;
require_once 'DocumentBase.php' ;


class ApplicationBase extends ResponderBase {
    use DocumentBase ;
    protected $userAgent ;

    public function __construct(?array $permissions) {
        parent::__construct($permissions) ;
        $this->userAgent = get_browser(null,true) ;
    }
}
