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
if (typeof {{objectname}} !== 'undefined') {
    delete window.{{objectname}} ;
}
'use strict' ;

// Suppresser
var {{objectname}} = (function(){
    const _suppress = function() {
        _override() ;
        _suppresWindow() ;
        {{debug}} _suppressConsole() ;
    } ;
    const _override =  function(...log) {
        window.D = function(...log){
            console.log(...log) ;
        } ;
        window.T = function(){
            console.trace() ;
        }
    } ;
    const _suppressConsole = function() {
        Object.keys(console).forEach(function(key){
            if(typeof console[key] === 'function') {
                console[key] = function() {
                    return false ;
                }
            }
        }) ;
        Object.freeze(console) ;
    } ;
    const _suppresWindow = function() {
        window.history.pushState(null, null, null) ;
        window.addEventListener("popstate", function(event){
            history.pushState(null, null, null) ;
        }) ;

        window.onbeforeunload = function(event) {
            event.preventDefault() ;
            event.returnValue = '' ;
        }
    } ;

    return {
        suppress: function(){
            _suppress() ;
        },
    } ;
})();

Suppresser.suppress() ;

if('{{namespace}}' !== '' && '{{objectname}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;
document.getElementById('{{uuid}}').remove() ;
