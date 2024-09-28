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
if(window.CookieMonster) delete window.CookieMonster ;
'use strict';

 CookieMonster = {
    // Note: This code assumes that HTTPS is being used for communication.
    // You should choose either Lax or Strict for SameSite,
    // and avoid setting 'SameSite=none; Secure'.
    options: {
        secure   : true ,
        SameSite : 'Lax' ,
    } ,
    getCookie: (name)=> {
      let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
      ));
      return matches ? decodeURIComponent(matches[1]) : undefined;
    } ,
    setCookie: (name, value, options={})=> {
        options = options || CookieMonster.options ;
        let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
        for (let optionKey in options) {
            updatedCookie += "; " + optionKey+'='+options[optionKey]+';' ;
        }
        document.cookie = updatedCookie;
    } ,
    deleteCookie: (name)=> {
        let removedCookie = encodeURIComponent(name) + '=; max-age=-1;' ;
        document.cookie = removedCookie ;
    } ,
}

if('{{namespace}}' !== '' && '{{objectname}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;
document.getElementById('{{uuid}}').remove() ;
