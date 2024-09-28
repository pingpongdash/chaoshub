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
if (typeof ModalStack !== 'undefined') {
    delete window.ModalStack ;
}
'use strict' ;

ModalStack = (()=> {
    let _bottom = '' ;
    let _stack  = [] ;
    let _position = 'center' ;
    const _cover = function() {
        let cover = document.getElementById('ms-cover') ;
        if(!cover) {
            cover = document.createElement('div') ;
            cover.setAttribute('id','ms-cover')   ;
            cover.classList.add("ms-modal-cover") ;
        }
        _bottom.appendChild(cover) ;
    } ;
    const _fadein = function(target,animationClass) {
        _setPosition(target) ;
        target.addEventListener('animationend', {
            handleEvent    : _fadedIn       ,
            target         : target         ,
            animationClass : animationClass ,
        },{once:true}) ;
        target.classList.add(animationClass)  ;
        target.style.display = 'inline-block' ;
    } ;
    const _fadedIn = function(event) {
        this.target.classList.remove(this.animationClass) ;
    } ;
    const _fadeout = function(target,animationClass) {
        target.classList.add(animationClass) ;
        target.addEventListener('animationend', {
            handleEvent    : _fadedOut      ,
            target         : target         ,
            animationClass : animationClass ,
        },{once:true}) ;
    } ;
    const _fadedOut = function() {
        this.target.classList.remove(this.animationClass) ;
        this.target.style.display = 'none'                ;
        let cover = document.getElementById('ms-cover')   ;
        if(_stack && _stack.length < 1) {
            if(cover)cover.remove() ;
        }
        else {
            if(_stack){
                let lastEmement =
                    document.getElementById(
                        _stack[_stack.length-1].id) ;
                _bottom.insertBefore(cover,lastEmement) ;
            }
        }
    } ;
    const _setPosition = function(target=undefined){
        if(target) {
            let width  = window.innerWidth  ;
            let height = window.innerHeight ;
            target.style.position = 'abusolute' ;
            target.style.display = 'block' ;
            let rect = target.getBoundingClientRect() ;
            let left = (width/2)  - (rect.width/2)    ;
            let top  = (height/2) - (rect.height/2)   ;
            target.style.top  = top+'px'  ;
            target.style.left = left+'px' ;
            target.style.display = 'none' ;
        }
    } ;
    const _setPositions = function(){
        let width = window.innerWidth ;
        let height = window.innerHeight ;
        _stack.forEach(id => {
            let target = document.getElementById(id.id) ;
            let rect = target.getBoundingClientRect() ;
            let left = (width/2)  - (rect.width/2)   ;
            let top  = (height/2) - (rect.height/2)  ;
            target.style.top  = top+'px' ;
            target.style.left = left+'px' ;
        }) ;
    } ;
    const _setResizeListener = function(){
        window.onresize = function(event){
            let width = window.innerWidth ;
            let height = window.innerHeight ;
            let cover = document.getElementById('ms-cover') ;
            if(cover) {
                cover.style.width  = width ;
                cover.style.height = height ;
            }
            if(_position === 'center'){
                _setPositions() ;
            }
        } ;
    }

    return {
        initialize: function(position='center') {
            _bottom              = document.body ;
            _stack               = []            ;
            _position            = position      ;
            _setResizeListener() ;
        } ,
        setBottom : function(id) {
            _bottom = document.getElementById(id) ;
        } ,
        push: function(id,effect='ms-fadein') {
            if(id instanceof HTMLElement) {
                id = id.getAttribute('id') ;
            }
            if( _stack.length > 0 &&
                _stack[_stack.length-1].id === id) return ;
            let element = document.getElementById(id) ;
            if(element) {
                _cover() ;
                _bottom.appendChild(element) ;
                _stack.push({id:id})         ;
                _fadein(element,effect)      ;
            }
        } ,
        pop: function(effect='ms-fadeout') {
            if (effect instanceof MouseEvent && effect.pointerType) {
              effect='ms-fadeout'
            }
            if(_stack.length === 0) return false ;
            let item = _stack.pop()              ;
            let element = document.getElementById(item.id) ;
            if(element) {
                _fadeout(element,effect) ;
            }
            return true ;
        } ,
        clear: function() {
            while(this.pop()) ;
        } ,
    } ;
})() ;

ModalStack.initialize() ;
if('{{namespace}}' !== '' && '{{namespace}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;
document.getElementById('{{uuid}}').remove() ;

