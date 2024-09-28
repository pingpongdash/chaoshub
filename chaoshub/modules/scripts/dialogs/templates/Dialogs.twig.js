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

// Dialogs
var {{objectname}} = (function(){
    let _buttons = [] ;
    let _modules = {} ;
    return {
        show: function(title,message,buttons = _buttons) {
            _buttons = buttons ;
            DomCon.get('button-area-dialog').innerText = '' ;
            DomCon.set('dialog-title',{text:title}) ;
            DomCon.set('dialog-message',{html:message}) ;
            _buttons.forEach(button => {
                let id = Textricker.getRandomString(16) ;
                DomCon.get('button-area-dialog').appendChild(
                    DomCon.set(id,{
                        element : 'div'    ,
                        text : button.text ,
                        listeners  : [{
                            type     : 'click' ,
                            listener : button.callback ,},],
                        classList : ['button-common'] ,
                        attributes : button.attributes ,}) ) ;
            }) ;
            _modules.modalStack.push('dialog-box') ;
        } ,
        close: function() {
            _modules.modalStack.pop() ;
        } ,
        setDefaults: function(defaults) {
            defaults.forEach(def => {
                def = defaults ;
            }) ;
        } ,
        setModules: function(modules) {
            _modules = modules ;
        } ,
    } ;
})();

if('{{namespace}}' !== '' && '{{objectname}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;
document.getElementById('{{uuid}}').remove() ;

