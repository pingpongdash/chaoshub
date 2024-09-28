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

// DomCon
var {{objectname}} = (function(){
    return {
        cover : null ,
        getName : function() {
            return '{{objectname}}' ;
        } ,
        initialize: function() {
        } ,
        setElement: function(element=undefined,parentId='') {
            if(!element) return ;
            let parent = (parentId === '')?document.body:{{objectname}}.get(parentId) ;
            let child = {{objectname}}.set(element.id,{
                element : element.element ,
                type    : element.type    ,
                html    : element.html    ,}) ;
            parent.appendChild(child) ;
        } ,
        setElements: function(elements,parentId='') {
            if(!elements) return ;
            let parent = (parentId === '')?document.body:{{objectname}}.get(parentId) ;
            Object.keys(elements).forEach(key => {
                {{objectname}}.setElement(elements[key],parentId) ;
            }) ;
        } ,
        buildElement: function(id,params) {
            let element = {{objectname}}.get(id) ;
            if(!element) {
                element = document.createElement(params.element) ;
                if(!params.attributes) params.attributes = {} ;
                params.attributes.id = id ;
            }
            if(params.type) {
                element.setAttribute('type',params.type) ;
            }
            if(params.name) {
                element.setAttribute('name',params.name) ;
            }
            if(params.html){
                if (params.html instanceof HTMLElement) {
                    element.innerHTML = params.html.innerHTML ;
                    const observer = new MutationObserver(() => {
                        element.innerText = '' ;
                        element.innerHTML = params.html.innerHTML ;
                    });
                    observer.observe(params.html, { childList: true, subtree: true });
                } else {
                    element.innerText = '' ;
                    element.innerHTML = params.html;
                }
            }
            else if(params.text){
                if (params.text instanceof HTMLElement) {
                    element.innerHTML = '<p>' + params.text.innerText + '</p>' ;
                    const observer = new MutationObserver(() => {
                        element.innerText = '' ;
                        element.innerHTML = '<p>' + params.text.innerText + '</p>' ;
                    });
                    observer.observe(params.text, { childList: true, subtree: true }) ;
                } else {
                    element.innerText = '' ;
                    element.innerHTML = '<p>' + params.text + '</p>' ;
                }
            }
            if(params.classList) {
                params.classList.forEach(function(item){
                    element.classList.add(item) ;
                }) ;
            }
            if(params.attributes) {
                Object.entries(params.attributes).forEach(([key, value]) => {
                    element.setAttribute(key, value);
                });
            }
            if(params.listeners) {
                Object.entries(params.listeners).forEach(([key, listener]) => {
                    element.addEventListener(listener.type, listener.listener, listener.options);
                });
            }
            return element ;
        } ,
        get: function(id) {
            return document.getElementById(id) ;
        } ,
        set: function(id,params) {
            return {{objectname}}.buildElement(id,params) ;
        } ,
        remove: function(id) {
            let element = {{objectname}}.get(id) ;
            if(element) element.remove() ;
        } ,
        setPlaceHolder: function(id,status='none',blink=false) {
            let children = {{objectname}}.get(id).parentNode.children ;
            Object.keys(children).forEach(function(key){
                if(children[key].getAttribute('name') == 'placeholder') {
                    let id = children[key].getAttribute('id') ;
                    {{objectname}}.get(id).style.display = status ;
                    if(blink) {
                        {{objectname}}.addClass(id,'ts-blink') ;
                    }
                    else {
                        {{objectname}}.removeClass(id,'ts-blink') ;
                    }
                    return ;
                }
            }) ;
        } ,
        removeClass: function(id,className) {
            let element = {{objectname}}.get(id) ;
            if(element) {
                element.classList.remove(className) ;
            }
        } ,
        removeAllClasses: function(id,keeps=[]) {
            let element = {{objectname}}.get(id) ;
            if(element) {
                element.classList.classList = keeps ;
            }
        } ,
        addClass: function(id,className) {
            let element = {{objectname}}.get(id) ;
            if(element) {
                element.classList.add(className) ;
            }
        } ,
        hasClass : function(id,className) {
            return {{objectname}}.get(id).classList.contains(className) ;
        } ,
        addListener: function(id,type,listener,options={once:true}) {
            let element = {{objectname}}.get(id) ;
            if(element) {
                element.addEventListener(type,listener,options) ;
            }
        } ,
        removeListener: function(id,type,listener,options={once:true}) {
            let element = {{objectname}}.get(id) ;
            if(element) {
                element.removeEventListener(type,listener,options) ;
            }
        } ,
        show: function(id,param='inline-block') {
            let element = {{objectname}}.get(id) ;
            if(element) {
                element.style.display = param ;
            }
        } ,
        hide: function(id,effect) {
            let element = {{objectname}}.get(id) ;
            if(effect) {
                element.classList.add(effect) ;
            }
            else {
                element.style.display = 'none' ;
            }
        } ,
        insertNext: function(insertElementId,nextElementId) {
            let parent = {{objectname}}.get(nextElementId).parentNode ;
            parent.insertBefore({{objectname}}.get(insertElementId) ,
                {{objectname}}.get(nextElementId).nextSibling ) ;
        } ,
        insertBefore: function(insertElementId,beforeElementId) {
            let parent = {{objectname}}.get(beforeElementId).parentNode ;
            parent.insertBefore({{objectname}}.get(insertElementId) ,
                {{objectname}}.get(beforeElementId) );
        } ,
        insertChild: function(insertElementId,parentId) {
            let parent = {{objectname}}.get(parentId) ;
            if(parent.firstElementChild) {
                parent.insertBefore(
                    {{objectname}}.get(insertElementId) ,parent.firstElementChild) ;
            }
            else {
                parent.appendChild({{objectname}}.get(insertElementId)) ;
            }
        } ,
        modalCover: function(id,visible=true) {
            {{objectname}}.cover = {{objectname}}.get('ts-cover') ;
            if(!this.cover) {
                {{objectname}}.cover = {{objectname}}.set('ts-cover',{
                    type : 'div' ,
                    classList : ['ts-modal-cover'] ,}) ;
            }
            else {
                {{objectname}}.cover.remove()  ;
            }
            if(visible) {
                {{objectname}}.insertBefore({{objectname}}.cover,{{objectname}}.get(id)) ;
                {{objectname}}.cover.style.display = 'block' ;
            }
            else {
                {{objectname}}.cover.remove()  ;
            }
        } ,
        fadein: function(target,postPorcess=null,display='block',animationClass='ts-fadein') {
            {{objectname}}.addListener(target,'animationend',{
                handleEvent    : {{objectname}}.fadedIn ,
                target         : target              ,
                animationClass : animationClass      ,
                postPorcess    : postPorcess         ,
            }) ;
            {{objectname}}.addClass(target,animationClass) ;
            {{objectname}}.show(target,display) ;
        } ,
        fadedIn(event) {
            {{objectname}}.removeClass(this.target,this.animationClass) ;
            if(this.postPorcess) this.postPorcess(this) ;
        } ,
        fadeout: function(target,postPorcess=null,animationClass='ts-fadeout') {
            {{objectname}}.addListener(target,'animationend',{
                handleEvent    : {{objectname}}.fadedout ,
                target         : target               ,
                animationClass : animationClass       ,
                postPorcess    : postPorcess          ,
            }) ;
            {{objectname}}.addClass(target,animationClass) ;
        } ,
        fadedout: function(event) {
            if(!this.target) return ;
            {{objectname}}.removeClass(this.target,this.animationClass) ;
            {{objectname}}.hide(this.target) ;
            if(this.postPorcess) this.postPorcess(this) ;
        } ,

        transform: function(target,transformer,postPorcess=null) {
            {{objectname}}.addListener(target,'animationend',{
                handleEvent : {{objectname}}.halfwayTransform ,
                target      : target            ,
                transformer : transformer       ,
                postPorcess : postPorcess       ,
            }) ;
            {{objectname}}.addClass(target,transformer.early) ;
        } ,
        halfwayTransform: function(event) {
            {{objectname}}.removeClass(this.target,this.transformer.early) ;
            {{objectname}}.addListener(this.target,'animationend',{
                handleEvent : {{objectname}}.postTransform ,
                target      : this.target            ,
                transformer : this.transformer       ,
                postPorcess : this.postPorcess       ,
            }) ;
            {{objectname}}.show(this.target,'block') ;
            {{objectname}}.addClass(this.target,this.transformer.latter) ;
            this.transformer.halfway() ;
        } ,
        postTransform: function(event) {
            {{objectname}}.removeClass(this.target,this.transformer.latter) ;
            if(this.postPorcess) this.postPorcess(this) ;
        } ,
        requestFullscreen: function(id) {
            {{objectname}}.get(id).requestFullscreen() ;
        } ,
        exitFullscreen: function(id) {
           exitFullscreen() ;
        } ,
        copy: function(id) {
            let range = document.createRange();
            range.selectNode(document.getElementById(id));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            let result = document.execCommand("copy") ;
            return result ;
        } ,
    } ;
})();


if('{{namespace}}' !== '' && '{{objectname}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;
document.getElementById('{{uuid}}').remove() ;
