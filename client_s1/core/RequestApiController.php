<?php

class RequestApiController
{

    public function getEmailsAction()
    {
        $qry_str = "?apikey=" . Config::API_SECRET_KEY . "&action=get_emails&limit=" . Config::GET_EMAILS_LIMIT . "&s=" . Config::CLIENT_ID;
        return $this->sendGetData($qry_str);
    }


    public function sendUnsubscribedStatisticAction()
    {
        $data = [
            "apikey" => Config::API_SECRET_KEY,
            "action" => "set_emails_unsubsribed",
        ];

        $emailsUnsubscribedData = [];
        $entities = EmailsEntity::findByField(EmailsEntity::$table, 'EmailsEntity', 'unsubscribed', 1);
        if (!empty($entities)) {
            $i = 0;
            foreach ($entities as $entity) {
                $emailsUnsubscribedData[$i]['country'] = $entity->country;
                $emailsUnsubscribedData[$i]['email'] = $entity->email;
                $emailsUnsubscribedData[$i]['ip'] = $entity->ip;
                $emailsUnsubscribedData[$i]['user_agent'] = $entity->user_agent;
                $emailsUnsubscribedData[$i]['created'] = $entity->unsubscribed_at;
                $i++;
            }
        }
        $data['emails_unsubscribed'] = $emailsUnsubscribedData;

        return $this->sendPostJsonData($data);
    }

    public function sendSendedEmailsStatisticAction()
    {
        $data = [
            'apikey' => Config::API_SECRET_KEY,
            'action' => 'set_emails_sended',
            's' => Config::CLIENT_ID
        ];

        $emailsSendedData = [];
        $entities = EmailsEntity::getSendedEmails();
        if (!empty($entities)) {
            $i = 0;
            foreach ($entities as $entity) {
                $emailsSendedData[$i]['email'] = $entity->email;
                $emailsSendedData[$i]['status'] = $entity->status;
                $emailsSendedData[$i]['created'] = $entity->sended_at;
                $i++;
            }
        }
        $data['emails_sended'] = $emailsSendedData;

        return $this->sendPostJsonData($data);
    }

    public function sendAccessedStatisticAction()
    {
        $data = [
            "apikey" => Config::API_SECRET_KEY,
            "action" => "set_emails_accessed",
        ];

        $emailsAccessedData = [];
        $entities = EmailsEntity::findByField(EmailsEntity::$table, 'EmailsEntity', 'accessed', 1);
        if (!empty($entities)) {
            $i = 0;
            foreach ($entities as $entity) {
                $emailsAccessedData[$i]['country'] = $entity->country;
                $emailsAccessedData[$i]['created'] = $entity->sended_at;
                $emailsAccessedData[$i]['email'] = $entity->email;
                $emailsAccessedData[$i]['ip'] = $entity->ip;
                $emailsAccessedData[$i]['user_agent'] = $entity->user_agent;
                $emailsAccessedData[$i]['created'] = $entity->accessed_at;

                $i++;
            }
        }
        $data['emails_accessed'] = $emailsAccessedData;

        return $this->sendPostJsonData($data);
    }

    private function sendGetData($dataString)
    {
        $ch = curl_init(Config::SERVER_API_URL . $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return $this->curlQuery($ch);
    }

    private function sendPostJsonData($data)
    {
        $data_string = json_encode($data);

        $ch = curl_init(Config::SERVER_API_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        return $this->curlQuery($ch);
    }

    private function curlQuery($ch)
    {
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}