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

const APP_ROOT      = __DIR__            ;
const STORE_PATH    = APP_ROOT.'/store/' ;
const TEMPLATE_PATH = APP_ROOT.'/templates/' ;

const LOGGING  = true ;
const LOG_PATH = '/proc/self/fd/' ;
const DEBUG_LOG_FILE = '1' ;
const INFO_LOG_FILE  = '1' ;
const TRACE_LOG_FILE = '1' ;
const ERROR_LOG_FILE = '1' ;
