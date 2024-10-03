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
require_once 'XMLProcessor.php' ;
require_once 'YAMLProcessor.php' ;


class Responder extends ObjectBase {
    private static $instance = null ;
    private function __construct() {}
    private function __clone() {}

    final public static function getInstance(){
        if (self::$instance === null) {
            self::$instance = new self() ;
        }
        return self::$instance;
    }

    public static function getResponder(?string $request='default') {
        // $xmlProc    = new XMLProcessor() ;
        // $responders = $xmlProc->load(APP_SETTINGS_DIR,APP_SETTINGS) ;
        $confproc   = new YAMLProcessor() ;
        $appconfig  = $confproc->load(APP_SETTINGS_DIR,APP_SETTINGS) ;
        $responders = $appconfig['responders'] ;
        $requestKeys = array_column($responders, 'request');
        if (!in_array('default', $requestKeys)) {
            $request='internalerror' ;
            D('default responder not found') ;
            // throw new Exception("Error: 'default' responder not found in the configuration.");
        }
        foreach($responders as $key => $responder) {
            if(!empty($responder['request']) && $responder['request'] === $request) {
                $settings = self::getSettings($responder) ;
                if(!empty($settings) && is_array($settings)) {
                    $permissions =
                        array_key_exists('permissions', $settings)? $settings['permissions']:[] ;
                    require_once $settings['class'].'.php' ;
                    return new $settings['class']($permissions) ;
                }
            }
        }
        unset($_POST) ;
        return self::getResponder() ;
    }

    private static function getSettings($responder): ?array {
        $attribute = '' ;
        if(isset($_POST['attribute']) && !empty($_POST['attribute'])) {
            $attribute = $_POST['attribute'] ;
        }
        else {
            $attribute = 'default' ;
        }
        if(!isset($responder['switch'])) {
            return $responder ;
        }
        else {
            return $responder['switch'][$attribute] ;
        }
    }
}
