<?php

class ApiController {

    public function __construct() {
    }

    public function getEmails($limit, $clientId){
        if (preg_match('/^\d+$/',$limit) && preg_match('/^\d+$/',$clientId) ){
            return EmailsEntity::reserveEmailsForClient($limit,$clientId);
        }

        return false;
    }

}
