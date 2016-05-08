<?php

class EmailsUnsubscribedEntity extends Entity
{
    public static $table = 'emails_unsubscribed';

    public function __construct($tableName = 'emails_unsubscribed')
    {
        parent::__construct('emails_unsubscribed');
        $this->add('id_email');
        $this->add('ip');
        $this->add('country');
        $this->add('user_agent');
        $this->add('created');
    }

    public function getEmail()
    {
        return array_shift(EmailsEntity::findByIds([$this->id_email]));
    }
}