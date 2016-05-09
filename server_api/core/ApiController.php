<?php

class ApiController
{

    public function __construct()
    {
    }

    /**
     * @return array|bool
     */
    public function getEmailsAction(Request $request)
    {
        if (preg_match('/^\d+$/', $request->limit) && preg_match('/^\d+$/', $request->s)) {
            return EmailsEntity::reserveEmailsForClient($request->limit, $request->s);
        }

        return false;
    }

    /**
     * @return int|bool update counter
     */
    public function setEmailsAccessedAction(Request $request)
    {
        if (!$request->hasProperty('emails_accessed')) {
            return false;
        }
        $emailsData = $request->emails_accessed;
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
                $emailAccessed->created = $emailData['created'];
                $emailAccessed->save();
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return int|bool update counter
     */
    public function setEmailsSendedAction(Request $request)
    {
        if (!$request->hasProperty('emails_sended') || !$request->hasProperty('s')) {

            return false;
        }
        $emailsData = $request->emails_sended;
        $clientId = $request->s;
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
                $emailSended->created = $emailData['created'];
                $emailSended->save();
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return bool|int
     */
    public function setEmailsUnsubsribedAction(Request $request)
    {
        if (!$request->hasProperty('emails_unsubscribed')) {

            return false;
        }
        $emailsData = $request->emails_unsubscribed;

        if (!is_array($emailsData) || empty($emailsData)) {

            return false;
        }

        $count = 0;
        foreach ($emailsData as $emailData) {
            $entity = new EmailsEntity();
            if ($entity->findOneByField('email', $emailData['email'])) {
                $emailUnsubscribed = new EmailsUnsubscribedEntity();
                $emailUnsubscribed->findOneByField('id_email', $entity->getId());

                $emailUnsubscribed->id_email = $entity->getId();
                $emailUnsubscribed->ip = $emailData['ip'];
                $emailUnsubscribed->country = $emailData['country'];
                $emailUnsubscribed->user_agent = $emailData['user_agent'];
                $emailUnsubscribed->created = $emailData['created'];
                $emailUnsubscribed->save();
                $count++;
            }
        }

        return $count;
    }

}
