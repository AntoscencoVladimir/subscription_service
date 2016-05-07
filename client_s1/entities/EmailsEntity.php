<?php

class EmailsEntity extends Entity
{
    public static $table = 'emails';

    public function __construct($tableName = 'emails')
    {
        parent::__construct('emails');
        $this->add('email');
        $this->add('ip');
        $this->add('country');
        $this->add('user_agent');
        $this->add('unsubscribed');
        $this->add('status');
        $this->add('accessed');
        $this->add('hash');
        $this->add('sended_at');
    }
}