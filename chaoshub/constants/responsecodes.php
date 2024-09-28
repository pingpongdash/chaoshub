<?php
declare(strict_types=1) ;
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
/**
This module defines constants for HTTP-like status codes, but exact matching is not required.
These codes are designed to provide a flexible selection for developers.
Note that the codes are similar to HTTP status codes, but strict adherence is not necessary.
*/
// responses
const RESP_CONTINUE                = '100' ;
const RESP_OK                      = '200' ;
const RESP_CREATED                 = '201' ;
const RESP_ACCEPTED                = '202' ;
const RESP_NO_CONTENT              = '204' ;
const RESP_FOUND                   = '302' ;
const RESP_BAD_REQUEST             = '400' ;
const RESP_UNAUTHORIZED            = '401' ;
const RESP_NOT_FOUND               = '404' ;
const RESP_NOT_ACCEPTABLE          = '406' ;
const RESP_CONFLICT                = '409' ;
const RESP_GONE                    = '410' ;
const RESP_UNPROCESSABLE_ENTITY    = '422' ;
const RESP_INTERNAL_SERVER_ERROR   = '500' ;
const RESP_AUTHENTICATION_REQUIRED = '511' ;

// mail responses
const REQUESTED_MAIL_ACTION_OKAY = '250' ;
const SERVICE_NOT_AVAILABLE      = '421' ;
const REQUESTED_ACTION_ABORTED   = '451' ;

const RESPONSES = [
    '100' => 'Continue'                        ,
    '200' => 'OK'                              ,
    '201' => 'Created'                         ,
    '202' => 'Accepted'                        ,
    '204' => 'No Content'                      ,
    '302' => 'Found'                           ,
    '400' => 'Bad Request'                     ,
    '401' => 'Unauthorized'                    ,
    '402' => 'Payment Required'                ,
    '404' => 'Not Found'                       ,
    '406' => 'Not Acceptable'                  ,
    '409' => 'Conflict'                        ,
    '410' => 'Gone'                            ,
    '418' => 'I\'m a teapot'                   ,
    '422' => 'Unprocessable Entity'            ,
    '500' => 'Internal Server Error'           ,
    '502' => 'Bad Gateway'                     ,
    '503' => 'Service Unavailable'             ,
    '507' => 'Insufficient Storage'            ,
    '511' => 'Network Authentication Required' ,
    // mail responses
    '250' => 'Requested mail action okay, completed' ,
    '421' => 'Service not available,closing transmission channel' ,
    '451' => 'Requested action aborted: local error in processing' ,
 ] ;
