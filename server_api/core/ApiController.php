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

}
