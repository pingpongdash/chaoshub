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
//GreCAPTCHA
var {{objectname}} = {
    rendered   : false            ,
    sitekey_v2 : '{{sitekey_v2}}' ,
    sitekey_v3 : '{{sitekey_v3}}' ,
    execute: (sitekey,action,callback)=> {
        grecaptcha.execute(sitekey, {action:action}).then((token)=> {
             callback(token) ;
        }) ;
    } ,
    render: (id,params)=> {
        try {
            if({{objectname}}.rendered) return ;
            grecaptcha.render(id,params) ;
            {{objectname}}.rendered = true ;
        }
        catch(e) {
        }
    } ,
}

// grecaptcha.ready(function(){
// console.log('grecaptcha has ready') ;
// });

if('{{namespace}}' !== '' && '{{objectname}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;
document.getElementById('{{uuid}}').remove() ;
