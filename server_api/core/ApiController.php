<?php

class ApiController
{

    public function __construct()
    {
    }

    public function getEmails($limit, $clientId)
    {
        if (preg_match('/^\d+$/', $limit) && preg_match('/^\d+$/', $clientId)) {
            return EmailsEntity::reserveEmailsForClient($limit, $clientId);
        }

        return false;
    }

    public function setEmailsAccessed($emailsData)
    {

        //$data = json_decode(stripcslashes($emailsData),true);

       /* $array = [
            "statistic" => [
                [
                    "country" => "MDA",
                    "created" => "2016-05-06 12:12:12",
                    "email" => "email1@spam4.me",
                    "ip" => "127.0.0.1",
                    "user_agent" => "chromium"
                ],
                [
                    "country" => "USA",
                    "created" => "2016-05-06 13:13:13",
                    "email" => "email2@spam4.me",
                    "ip" => "127.0.0.1",
                    "user_agent" => "firefox"
                ],
                [
                    "country" => "RU",
                    "created" => "2016-05-06 11:11:11",
                    "email" => "email3@spam4.me",
                    "ip" => "127.0.0.1",
                    "user_agent" => "opera"
                ]
            ]
        ];*/

        return $emailsData;
    }

}
