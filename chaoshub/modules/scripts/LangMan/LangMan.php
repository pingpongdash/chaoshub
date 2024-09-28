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
require_once 'XMLProcessor.php'       ;

class LangMan extends ModuleBase {

    public function __construct(array $permissions=null) {
        parent::__construct($permissions) ;
    }

    public function respond() {
        $language = $this->getLanguage() ;
// D($language) ;
        $this->getLangPack($language) ;
        D($language) ;
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

    private function getLangPack(string $language) {
        $uuidgen  = new UUIDGenerater()  ;
        $uuid     = $uuidgen->generate() ;
        // $lang     = $this->getLanguage() ;
        // $page     = $this->post['page']  ;
        $langPath = LANGUAGE_DIR         ;
        if(!file_exists($langPath.$language)) {
            $langPath .= DEFAULT_LANGUAGE ;
        }
        else {
            $langPath .= $language ;
        }
        $xmlproc  = new XMLProcessor() ;
        $langPack = $xmlproc->load($langPath,LANGUAGE_PACK_FILE) ;
// D($langPath) ;
// D($langPack) ;
        $langPack = json_encode($langPack) ;
        $response = [
            'status' => 'OK'      ,
            'id'     => $uuid     ,
            'body'   => $langPack ,
            'lang'   => $language ,
        ] ;
        $this->sendObject($response) ;
        return $response ;
    }

    public function getLanguage(): string {
        $lang = DEFAULT_LANGUAGE ;
        $lang = (isset($_COOKIE['lang']))?
            $_COOKIE['lang']:$this->getPrimaryLanguage() ;
        if($lang === 'undefined' ) $lang = DEFAULT_LANGUAGE ;
        return $lang ;
    }

    private function getPrimaryLanguage(): string {
        $lang = DEFAULT_LANGUAGE ;
        $accept_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ;
// D($accept_lang) ;
        $langs = explode(',',$accept_lang) ;
        $lang  = array_shift($langs) ;
        if($lang === 'undefined' ) $lang = DEFAULT_LANGUAGE ;
        return $lang ;
    }
}


