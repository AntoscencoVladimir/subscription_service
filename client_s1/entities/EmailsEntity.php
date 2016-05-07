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


    public static function getSendedEmails()
    {
        $select = new Select(self::$db);
        $select->from([self::$table], [
            'id',
            'email',
            'ip',
            'country',
            'user_agent',
            'unsubscribed',
            'status',
            'accessed',
            'hash',
            'sended_at'
        ])->where('sended_at IS NOT NULL');
        $data = self::$db->select($select);

        if ($data) {
            return self::createCollection(get_called_class(), $data);
        }
        return [];
    }
}