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
declare(strict_types=1) ;

define('SF_ROOT', __DIR__) ;

unset($_GET) ;
unset($_REQUEST) ;

require_once SF_ROOT.'/constants/constants.php' ;
require_once APP_DIR.'cominclude.php'  ;
require_once SF_ROOT.'/constants/maildefaults.php'  ;
require_once SF_ROOT.'/constants/responsecodes.php' ;
require_once SF_ROOT.'/constants/twigsettings.php'  ;
require_once SF_ROOT.'/PathFinder.php' ;
require_once SF_ROOT.'/classes/logging/Logger.php' ;

$pathFinder = new PathFinder() ;
$pathFinder->findPath(SF_ROOT.'/classes/',['store','templates']) ;
$pathFinder->findPath(SF_ROOT.'/modules/',['store','templates']) ;
$pathFinder->findPath(APP_DIR.'classes/',['store','templates'])  ;
$pathFinder->findPath(APP_DIR.'modules/',['store','templates'])  ;

require_once 'Responder.php' ;

$request = filter_input(INPUT_POST, 'request', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ;
if (empty($request)) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $parsedUri = parse_url($requestUri, PHP_URL_PATH);
    $parsedUri = trim($parsedUri, '/');
    $request = !empty($parsedUri) ? $parsedUri : 'default';
}
$request = filter_var($request, FILTER_VALIDATE_REGEXP,
    array('options' => array('regexp' => REQUEST_REGEXP))) ;
$request = ($request)? $request:'default' ;
$responder = Responder::getInstance()->getResponder($request);
if(empty($responder)) {
    echo 'Oops!' ;
    exit ;
}
$responder->respond() ;
