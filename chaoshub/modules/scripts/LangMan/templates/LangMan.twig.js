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
if(window.{{objectname}})  delete window.{{objectname}} ;
'use strict';

var {{objectname}} = (function(){
    const _setLanguage = (language,onLanguage=null)=> {
        Object.entries(language).forEach(([id,value])=> {
            let element = DomCon.get(id) ;
            if(element) {
                if(typeof value !== 'string') {
                    element.innerHTML = value.body ;
                }
                else {
                    element.innerHTML = value ;
                }
            }
        }) ;
        if(onLanguage)onLanguage(language) ;
    } ;

    return {
        setup: (parentNode)=> {
            parentNode.appendChild(DomCon.get('lang-selector-wrap')) ;
        } ,
        getLanguagePack: (onLanguage=null)=> {
            let language = '' ;
            Puller.query({
                post : {
                    request  : 'language' ,
                    language : language   ,
                },
                onResponse : (response)=> {
                    let language = JSON.parse(response.body) ;
                    _setLanguage(language,onLanguage) ;
                } ,
                onError : (error)=> {
                    D(error) ;
                },

            }) ;
        } ,
        getLanguageList: ()=> {
        } ,
    } ;
})();

if('{{namespace}}' !== '' && '{{objectname}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;

document.getElementById('{{uuid}}').remove() ;
