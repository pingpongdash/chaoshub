// {{namespace}}
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

// SessionManager
var {{objectname}} = (function(){
    let _{{objectname}} = {} ;
    const _get = function(key=undefined){
        if(!key) return _{{objectname}} ;
        return _{{objectname}}[key] ;
    } ;
    return {
        set: function({{objectname}}){
            _{{objectname}} = {{objectname}} ;
        } ,
        get: function(key=undefined){
            return _get(key) ;
        } ,
    } ;
})() ;




document.getElementById('{{uuid}}').remove() ;


