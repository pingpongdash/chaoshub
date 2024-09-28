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

const EMAIL_REG = 
 "/^[A-Za-z0-9._@+!#%&'?^`{}~|$*<>\-\=]{1}[A-Za-z0-9._@+!#%&'?^`{}~|$*<>\-\=]*@{1}[A-Za-z0-9_.-]{1,}.[A-Za-z0-9]{1,}$/" ;

const SMTP_AUTH      = true       ;
const SMTP_AUTH_TYPE = 'LOGIN'    ;
const SMTP_SECURE    = 'CRAM-MD5' ;
const SMTP_PORT      = 25         ;
const MAIL_WORDWRAP  = 72         ;
const SMTP_CHARSET   = 'UTF-8'    ;
const XMAILER        = 'swordfish mailer' ;
const SMTP_OPTIONS = [
    'ssl' => [
        'verify_peer'       => false ,
        'verify_peer_name'  => false ,
        'allow_self_signed' => true  ,
    ]
] ;
