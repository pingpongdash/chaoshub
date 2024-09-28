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
require_once VENDOR_DIR.'autoload.php' ;

class Postman extends ObjectBase {

    private $mailer ;

    public function __construct() {
        $this->mailer = new PHPMailer\PHPMailer\PHPMailer(true) ;
        $this->mailer->XMailer = XMAILER ;
    }

    public function setup(array $mailData): array  {
        $this->setDefaults($mailData) ;
        $result = $this->setServerData($mailData) ;
        if($result['status'] === 'ERROR') return $result ;
        $result = $this->setMailData($mailData) ;
        return $result ;
    }

    public function send(array $mailData): array  {
        $result = ['class' => 'postman'] ;
        try {
            $this->mailer->WordWrap = MAIL_WORDWRAP ;
            $this->mailer->isHTML(!empty($mailData['isHTML'])) ;
            $this->mailer->Subject = mb_encode_mimeheader($mailData['subject']) ; 
            $this->mailer->Body    = 
                mb_convert_encoding($mailData['body'],SMTP_CHARSET,'auto') ;
            if(isset($mailData['altbody'])) {
                $this->mailer->AltBody = 
                    mb_convert_encoding($mailData['altbody'],SMTP_CHARSET,'auto') ;
            }
            $resp = $this->mailer->send() ;
            if($resp) {
                $result = [
                    'status'      => 'OK'                     ,
                    'code'        => REQUESTED_MAIL_ACTION_OKAY            ,
                    'description' => RESPONSES[REQUESTED_MAIL_ACTION_OKAY] ,
                ] ;
                $this->mailer->SmtpClose();
            }
            else {
                throw new Exception('Email sending error.') ;
            }
        }
        catch(Exception $e) {
            $result = [
                'status'      => 'ERROR'                        ,
                'code'        => REQUESTED_ACTION_ABORTED            ,
                'description' => RESPONSES[REQUESTED_ACTION_ABORTED] ,
                'exception'   => $e->getMessage()               ,
            ] ;
        }
        finally {
            $this->mailer->SmtpClose();
        }
        return $result ;
    }

    private function setServerData(array $mailData): array {
        $result = [] ;
        try {
            // $this->mailer->SMTPDebug = 2 ; 
            $this->mailer->isSMTP();
            $this->SMTPKeepAlive       = true;
            $this->mailer->Host        = $mailData['smtpserver']      ;
            $this->mailer->SMTPAuth    = $mailData['smtp_auth']       ;
            $this->mailer->AuthType    = $mailData['smtp_auth_type']  ;
            $this->mailer->Username    = $mailData['from']['address'] ;
            $this->mailer->Password    = $mailData['password']        ;
            $this->mailer->SMTPSecure  = $mailData['smtpsecure']      ;
            $this->mailer->Port        = $mailData['port']            ;
            $this->mailer->SMTPOptions = $mailData['smtpoptions']     ;
            $result = [
                'status'      => 'OK'                     ,
                'code'        => RESP_CONTINUE            ,
                'description' => RESPONSES[RESP_CONTINUE] ,
            ] ;
        }
        catch(Exception $e) {
            $this->mailer->SmtpClose();
            $result = [
                'status'      => 'ERROR'                     ,
                'code'        => SERVICE_NOT_AVAILABLE            ,
                'description' => RESPONSES[SERVICE_NOT_AVAILABLE] ,
                'exception'   => $e->getMessage()            ,
            ] ;
        }
        return $result ;
    }

    private function setMailData(array $mailData) {
        $result = [] ;
        try {
            $this->mailer->CharSet = $mailData['charset'] ;
            if(empty($mailData['from'])) {
                throw new Exception('sender not set.') ;
            }
            else {
               $this->mailer->setFrom(
                   $mailData['from']['address'],
                   mb_encode_mimeheader($mailData['from']['name']));  
            }
            $this->mailer->Sender = $mailData['return_path']['address'] ;
            foreach($mailData['replyto'] as $key => $replyto) {
                if(is_array($replyto)) {
                    $this->mailer->addReplyTo(
                        $replyto['address'],
                        mb_encode_mimeheader(
                            (isset($replyto['name']))?$replyto['name']:''));  
                }
                else {
                    $this->mailer->addReplyTo($replyto) ;
                }
            }
            foreach($mailData['addresses'] as $key => $address) {
                if(is_array($address)) {
                    $this->mailer->addAddress(
                        $address['address'],
                        mb_encode_mimeheader(
                            (isset($address['name']))?$address['name']:''));  
                }
                else {
                    $this->mailer->addAddress($address) ;
                }
            }
            foreach($mailData['carbon_copy'] as $key => $ccopy) {
                if(is_array($ccopy)) {
                    $this->mailer->addCC(
                        $ccopy['address'],
                        mb_encode_mimeheader(
                            (isset($ccopy['name']))?$ccopy['name']:''));  
                }
                else {
                    $this->mailer->addCC($replyto) ;
                }
            }
            foreach($mailData['customheaders'] as $key => $customheader) {
                if(is_array($customheader)) {
                    $this->mailer->addCustomHeader(
                        $customheader['name'],$customheader['value']) ;
                }
                else {
                    $this->mailer->addCustomHeader($customheader) ;
                }
            }
            foreach($mailData['embeddedimages'] as $key => $embeddedimage) {
                $this->mailer->AddEmbeddedImage(
                    $embeddedimage['image'],$embeddedimage['filename']) ;
            }
            $result = [
                'status'      => 'OK'                     ,
                'code'        => RESP_CONTINUE            ,
                'description' => RESPONSES[RESP_CONTINUE] ,
            ] ;
        }
        catch(Exception $e) {
            $this->mailer->SmtpClose();
            $result = [
                'status'      => 'ERROR'                     ,
                'code'        => REQUESTED_ACTION_ABORTED            ,
                'description' => RESPONSES[REQUESTED_ACTION_ABORTED] ,
                'exception'   => $e->getMessage()            ,
            ] ;
        }
        return $result ;
    }

    private function setDefaults(array &$maildata) {
        $maildata['smtpserver'] = 
            (empty($maildata['smtpserver']))?SMTP_HOST:$maildata['smtpserver'] ;
        $maildata['smtp_auth'] = 
            (empty($maildata['smtp_auth']))?SMTP_AUTH:$maildata['smtp_auth'] ;
        $maildata['smtpsecure'] = 
            (empty($maildata['smtpsecure']))?SMTP_SECURE:$maildata['smtpsecure'] ;
        $maildata['auth_type'] = 
            (empty($maildata['auth_type']))?SMTP_AUTH_TYPE:$maildata['auth_type'] ;
        $maildata['port'] = 
            (empty($maildata['port']))?SMTP_PORT:$maildata['port'] ;
        $maildata['charset'] = 
            (empty($maildata['charset']))?SMTP_CHARSET:$maildata['charset'] ;
    }
    public function getDefaults(): array {
        $defaults = [
            'smtpserver'     => ''                 ,
            'smtp_auth'      => SMTP_AUTH          ,
            'smtp_auth_type' => SMTP_AUTH_TYPE     ,
            'password'       => ''                 ,
            'smtpsecure'     => SMTP_SECURE        ,
            'port'           => SMTP_PORT          ,
            'smtpoptions'    => SMTP_OPTIONS       ,
            'charset'        => SMTP_CHARSET       ,
            'from'           => [['address'=>''],] ,
            'return_path'    => [['address'],]     ,
            'replyto'        => []                 ,
            'addresses'      => []                 ,
            'carbon_copy'    => []                 ,
            'customheaders'  => []                 ,
            'isHTML'         => false              ,
            'subject'        => ''                 ,
            'body'           => ''                 ,
            'altbody'        => null               ,
            'embeddedimages' => []                 ,
        ] ;
        return $defaults ;
    }

}
