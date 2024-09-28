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

// SocketMonster
var {{objectname}} = (()=> {
    let _server    = '' ;
    let _listeners = {} ;
    let _webSocket = null ;
    let _queue     = [] ;

    const _protocol = [] ;
    const _origin   = window.location.origin ;

    const _connect = ()=> {
        if(_server === ''){
            _onError('server not set.') ;
            return ;
        }
        try{
            _webSocket = new WebSocket(_server,_protocol,_origin) ;
            _webSocket.onopen    = _onOpen    ;
            _webSocket.onclose   = _onClose   ;
            _webSocket.onmessage = _onMessage ;
            _webSocket.onerror   = _onError   ;
        }catch(e){
            if(_webSocket.onerror)_webSocket.onerror(e) ;
        }
    } ;
    const _close = ()=> {
        _webSocket.close() ;
    } ;
    const _send = (message)=> {
        try{
            if(_webSocket.readyState === _webSocket.OPEN) {
                _webSocket.send(message) ;
            }
            else {
                _queue.push(message) ;
            }
        }catch(e){
            _onError(e)
        }
    } ;

    const _flush = ()=> {
        if(_webSocket.readyState === _webSocket.OPEN) {
            while (typeof (message = _queue.shift()) !== 'undefined') {
                _webSocket.send(message) ;
            }
        }
    }

    // 0   CONNECTING
    // 1   OPEN
    // 2   CLOSING
    // 3   CLOSED
    const _status = ()=> {
        if(!_webSocket){
            return undefined ;
        }
        else {
            return _webSocket.readyState ;
        }
    } ;
    const _onOpen = ()=> {
        if(_listeners.onOpen) _listeners.onOpen() ;
    } ;
    const _onClose = ()=> {
        if(_listeners.onClose) _listeners.onClose() ;
    } ;

    const _onMessage = (message)=> {
        if(_listeners.onMessage){
            _listeners.onMessage(message) ;
        }
    } ;
    const _onError = (error)=> {
        if(_listeners.onError) _listeners.onError(error) ;
    } ;

    return {
        setServer: (server)=> {
            _server = server ;
        } ,
        connect: ()=> {
            _connect() ;
        } ,
        close: ()=> {
            _close() ;
        } ,
        send: (message)=> {
            _send(message) ;
        } ,
        status: ()=> {
            return _status() ;
        } ,
        // onOpen
        // onClose
        // onMessage
        // onError
        setListeners: (listeners)=> {
            _listeners = listeners ;
        } ,
    } ;
})() ;

if('{{namespace}}' !== '' && '{{namespace}}' in window) {{namespace}}{{objectname}} = {{objectname}} ;
document.getElementById('{{uuid}}').remove() ;



