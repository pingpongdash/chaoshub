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

// BoxDragger
var {{objectname}} = (()=> {
    let _startX      = 0 ;
    let _startY      = 0 ;
    let _pX          = 0 ;
    let _pY          = 0 ;
    let _differenceX = 0 ;
    let _differenceY = 0 ;
    let _minheight   = 0 ;

    const _addListener = (id,type,listener,options={once:true})=> {
        let element = _get(id) ;
        element.addEventListener(type,listener,options) ;
    } ;
    const _removeListener = (id,type,listener,options={once:true})=> {
        let element = _get(id) ;
        element.removeEventListener(type,listener,options) ;
    } ;
    const _get = (id)=> {
        return document.getElementById(id) ;
    } ;
    const _setCurrentPosition = (reference)=> {
        let mom      = reference.parentElement ;
        let brothers = Array.from(mom.childNodes) ;
        let brother  = undefined ;
        while(brother = brothers.pop()) {
            if(!brother) break ;
            if(!brother.tagName) continue ;
            let styles = window.getComputedStyle(brother) ;
            let marginTop  = parseInt(styles['margin-top'],10)  ;
            let marginLeft = parseInt(styles['margin-left'],10) ;
            brother.style.left = (brother.offsetLeft - marginLeft) + 'px' ;
            brother.style.top  = (brother.offsetTop  - marginTop)  + 'px' ;
            brother.style.position = 'absolute' ;
        }
    } ;

    const _moveStart = function(event) {
        let id = this.getAttribute('id') ;
        _setCurrentPosition(this)        ;
        this.parentElement.appendChild(this) ;
        _startX = this.offsetLeft ;
        _startY = this.offsetTop  ;
        _pX = event.pageX ;
        _pY = event.pageY ;
        _addListener(id,'mousemove',_moving,{once:true}) ;
        _addListener(id,'mouseup',_moved,{once:true}) ;
    } ;
    const _moving = function(event) {
        let id = this.getAttribute('id') ;
        _differenceX = event.pageX - _pX ;
        _differenceY = event.pageY - _pY ;
        _get(id).style.position = 'absolute'  ;
        _get(id).style.left = _startX + _differenceX + 'px' ;
        _get(id).style.top  = _startY + _differenceY + 'px' ;
        _addListener(id,'mousemove',_moving,{once:true}) ;
    } ;
    const _moved = function() {
        let id       = this.getAttribute('id')    ;
        let mom      = _get(id).parentElement     ;
        let brothers = Array.from(mom.childNodes) ;
        let brother  = undefined                  ;
        let minTop   = _get(id).offsetTop         ;
        let maxBottm = 0                          ;
        _removeListener(id,'mousemove',_moving,{once:true}) ;
        while(brother = brothers.pop()) {
            if(!brother) break ;
            if(!brother.tagName) continue ;
            let bottom = brother.offsetTop + brother.offsetHeight ;
            if(maxBottm < bottom) maxBottm = bottom ;
        }
        if(_minheight < maxBottm) {
            mom.style.height = maxBottm + 'px' ;
        }
    } ;
    const _assign = (id)=> {
        _addListener(id,'mousedown',_moveStart,{once:false}) ;
        _addListener(id,'mouseleave',_moved,{once:false}) ;
        _minheight = _get(id).parentElement.offsetHeight
    } ;
    const _assignItems = (wrapper)=> {
        let nodes = Array.from(wrapper.childNodes) ;
        let node  = undefined ;
        while(node = nodes.pop()) {
            if(!node.tagName) continue ;
            _assign(node.id) ;
        }
    } ;

    return {
        assign: (item)=> {
            if (typeof item === "string") {
                _assign(item) ;
            } else if (item instanceof HTMLElement) {
                _assignItems(item) ;
            } else {
                return false ;
            }
        } ,
    } ;
})() ;

if('{{namespace}}' !== '' && '{{objectname}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;
document.getElementById('{{uuid}}').remove() ;
