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
if(window.{{objectname}}) delete window.{{objectname}} ;
'use strict' ;

var {{objectname}} = {
    defaultURL          : location.href ,
    defaultResponseType : 'json'        ,
    query: function(params) {
        let url = (params.url)?params.url:{{objectname}}.defaultURL ;
        let post = {{objectname}}.array2Param(params.post) ;
        let responseType = (params.responseType)?params.responseType:Puller.defaultResponseType ;
        let request  = new XMLHttpRequest() ;
        let response = null ;
        request.onprogress = (params.onProgress)?params.onProgress:null;
        request.onload = function(event) {
            if(request.readyState == request.DONE) {
                if(!request.response) {
                    (params.onError)?params.onError(request.response):false ;
                }
                if(request.status == 200 ) {
                    (params.onResponse)?params.onResponse(request.response):false ;
                }
                else {
                    (params.onError)?params.onError(request.response):false ;
                }
            }
        }
        request.responseType = responseType ;
        request.open('POST',url,true) ;
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded') ;
        request.send(post) ;
    } ,
    array2Param: function(postarray) {
        let params = [] ;
        let post   = '' ;
        Object.keys(postarray).forEach(function(key){
            let param = encodeURIComponent(key)+'='+encodeURIComponent(postarray[key]) ;
            params.push(param) ;
        }) ;
        post = params.join('&').replace(/%20/g,'+');
        return post ;
    } ,
}
if('{{namespace}}' !== '' && '{{objectname}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;

document.getElementById('{{uuid}}').remove() ;

