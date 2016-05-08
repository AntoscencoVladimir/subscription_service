<?php

class EmailsAccessedEntity extends Entity
{
    public static $table = 'emails_accessed';

    public function __construct($tableName = 'emails_accessed')
    {
        parent::__construct('emails_accessed');
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