<?php

class EmailsSendedEntity extends Entity
{
    public static $table = 'emails_sended';

    public function __construct($tableName = 'emails_sended')
    {
        parent::__construct('emails_sended');
        $this->add('id_email');
        $this->add('sender_server');
        $this->add('status');
        $this->add('created');
    }
    
    public function getEmail()
    {
        return array_shift(EmailsEntity::findByIds([$this->id_email]));
    }
}