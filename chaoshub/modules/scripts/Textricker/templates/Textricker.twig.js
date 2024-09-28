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
if (typeof Textricker !== 'undefined') {
    delete window.Textricker ;
}
'use strict' ;

var Textricker = (function(){

    const _hasText = function(element) {
        if( !(element instanceof HTMLElement)) {
            return false ;
        }
        let placeholder = element.getAttribute('placeholder') ;
        if(!placeholder) return ;
        if(element.innerText.trim().length > 0) {
            DomCon.hide(placeholder) ;
        }
        else {
            DomCon.show(placeholder) ;
        }
        if(_onChange){_onChange(element);}
    } ;
    const _removeHtmlTags = function(text) {
        text = text.replace(Constants.tagIreg,'') ;
        text = text.replace(Constants.linefeedIreg,'').trim() ;
        return text ;
    } ;
    const _removeMailIreg = function(text) {
        text = text.replace(Constants.mailIreg,'').trim() ;
        return text ;
    } ;

    let _onChange = null ;
    return {
        hasText        : _hasText        ,
        removeHtmlTags : _removeHtmlTags ,
        removeMailIreg : _removeMailIreg ,
        setCallBack: function(onCange) {
            _onChange = onCange ;
        } ,
        checkId: function(id) {
            const idreg = /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/;
            return idreg.test(id);
        } ,
        purifyText: function(event) {
    // left  37
    // up    38
    // right 39
    // down  40
    // BS     8
    // Shift 16
            let cursors = [37,38,39,40,8,16] ;
            if(cursors.includes(event.keyCode)) {
                _hasText(this) ;
                return false ;
            }
            let text    = this.innerText ;
            let initLen = this.innerText.length ;
            text = _removeHtmlTags(text) ;
            text = _removeMailIreg(text) ;
            let selection  = window.getSelection()  ;
            let range      = document.createRange() ;
            let offset     = text.length  ;
            this.innerText = text ;
            if(this.firstChild) {
                range.setStart(this.firstChild, offset) ;
                range.setEnd(this.firstChild, offset)   ;
                selection.removeAllRanges() ;
                selection.addRange(range)   ;
            }
            _hasText(this) ;
        } ,
        purifyPaste: function(event) {
            let dataTransfer = (event.clipboardData || window.clipboardData) ;
            let paste = dataTransfer.getData('text')
            paste = _removeHtmlTags(paste) ;
            let selection = window.getSelection();
            if (!selection.rangeCount) return false;
            selection.deleteFromDocument();
            selection.getRangeAt(0).insertNode(document.createTextNode(paste));
            event.preventDefault();
            _hasText(this) ;
        } ,
        ignoreEnter: function(event) {
            if(event.keyCode === 13) {
                event.preventDefault();
                return false ;
            }
        },
        ignoreNumIreg: function(event) {
    // left  37
    // up    38
    // right 39
    // down  40
    // BS     8
    // Shift 16
    // MetaLeft 91
    // MetaRight 93
            let cursors = [37,38,39,40,8,16,91,93] ;
            if(cursors.includes(event.keyCode)) {
                _hasText(this) ;
                return false ;
            }

            let nums = ['1','2','3','4','5','6','7','8','9','0',
                        'Backspace','Delete',] ;
            if( nums.includes(event.key) ||
                (event.key === 'v' && event.ctrlKey) ) {
                _hasText(this) ;
            }
            else {
                event.preventDefault();
                return false ;
            }
        } ,
        purifyNumeric: function(event) {
    // left  37
    // up    38
    // right 39
    // down  40
    // BS     8
    // Shift 16
    // MetaLeft 91
    // MetaRight 93
            let cursors = [37,38,39,40,8,16,91,93] ;
            if(cursors.includes(event.keyCode)) {
                _hasText(this) ;
                return false ;
            }
            let dataTransfer = (event.clipboardData || window.clipboardData) ;
            let paste = dataTransfer.getData('text') ;
            paste = paste.replace(/[^0-9]/g,'') ;
            event.preventDefault() ;
            this.innerText = paste ;
            _hasText(this) ;
        } ,
        onTextKeyup: function(event) {
            _hasText(this) ;
        } ,
        getRandomString: function(len) {
            const seed   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' ;
            let random = '' ;
            for(var index=0 ; index<len ;index++) {
                random += seed.charAt(Math.floor(Math.random()*seed.length));
            }
            return random ;
        } ,
        getDateTime: function(dateseed,locale=undefined,options={}) {
            let date = new Date(dateseed) ;
            let intl = new Intl.DateTimeFormat(locale,options) ;
            return intl.format(date) ;
        } ,
    } ;

})() ;

if('{{namespace}}' !== '' && '{{objectname}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;
document.getElementById('{{uuid}}').remove() ;
