<?php

class ApiController
{

    public function __construct()
    {
    }

    /**
     * @param $limit int
     * @param $clientId int
     *
     * @return array|bool
     */
    public function getEmails($limit, $clientId)
    {
        if (preg_match('/^\d+$/', $limit) && preg_match('/^\d+$/', $clientId)) {
            return EmailsEntity::reserveEmailsForClient($limit, $clientId);
        }

        return false;
    }

    /**
     * @param $emailsData array example:
     * [
     *      [
     *          "country" => "MDA",
     *          "created" => "2016-05-06 12:12:12",
     *          "email" => "email1@spam4.me",
     *          "ip" => "127.0.0.1",
     *          "user_agent" => "chromium"
     *      ],
     * ]
     *
     * @return int|bool update counter
     */
    public function setEmailsAccessed($emailsData)
    {
        if (!is_array($emailsData) || empty($emailsData)) {

            return false;
        }
        $count = 0;
        foreach ($emailsData as $emailData) {
            $entity = new EmailsEntity();
            if ($entity->findOneByField('email', $emailData['email'])) {
                $emailAccessed = new EmailsAccessedEntity();
                $emailAccessed->findOneByField('id_email', $entity->getId());

                $emailAccessed->id_email = $entity->getId();
                $emailAccessed->ip = $emailData['ip'];
                $emailAccessed->country = $emailData['country'];
                $emailAccessed->user_agent = $emailData['user_agent'];
                $emailAccessed->created = date('Y-m-d H:i:s');
                $emailAccessed->save();
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param $emailsData array example:
     * [
     *      [
     *          "email" => "email1@spam4.me",
     *          "status" => true
     *      ],
     * ]
     * @param $clientId  int sender client id
     *
     * @return int update counter
     */
    public function setEmailsSended($emailsData, $clientId)
    {
        if (!is_array($emailsData) || empty($emailsData) || preg_match('/^\d+$/', $clientId) === false) {

            return false;
        }
        $count = 0;
        foreach ($emailsData as $emailData) {
            $entity = new EmailsEntity();
            if ($entity->findOneByField('email', $emailData['email'])) {
                $emailSended = new EmailsSendedEntity();
                $emailSended->findOneByField('id_email', $entity->getId());

                $emailSended->id_email = $entity->getId();
                $emailSended->sender_server = $clientId;
                $emailSended->status = $emailData['status'];
                $emailSended->created = date('Y-m-d H:i:s');
                $emailSended->save();
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param $emailsData array example:
     * [
     *      [
     *          "email" => "email1@spam4.me",
     *          "ip" => "127.0.0.1",
     *          "country" => "MDA",
     *          "user_agent" => "Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko"
     *      ],
     * ]
     *
     * @return bool|int
     */
    public function setEmailsUnsubsribed($emailsData)
    {
        $count = 0;
        if (!is_array($emailsData) || empty($emailsData)) {

            return false;
        }

        foreach ($emailsData as $emailData) {
            $entity = new EmailsEntity();
            if ($entity->findOneByField('email', $emailData['email'])) {
                $emailUnsubscribed = new EmailsUnsubscribedEntity();
                $emailUnsubscribed->findOneByField('id_email', $entity->getId());

                $emailUnsubscribed->id_email = $entity->getId();
                $emailUnsubscribed->ip = $emailData['ip'];
                $emailUnsubscribed->country = $emailData['country'];
                $emailUnsubscribed->user_agent = $emailData['user_agent'];
                $emailUnsubscribed->created = date('Y-m-d H:i:s');
                $emailUnsubscribed->save();
                $count++;
            }
        }

        return $count;
    }

}
