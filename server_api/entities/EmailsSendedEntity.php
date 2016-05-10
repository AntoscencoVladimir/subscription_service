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
        $entity = new EmailsEntity();
        $entity->findOneById($this->id_email);

        if ($entity->getId()) {
            return $entity;
        }
        return false;
    }

    public static function findAllByRange($from, $to)
    {
        $select = new Select(self::$db);
        $select->from(self::$table, ['id', 'id_email', 'sender_server', 'status', 'created'])
            ->where(' `created` BETWEEN ? AND ? ', [$from, $to]);
        $data = self::$db->select($select);

        if ($data) {

            return self::createCollection(get_called_class(), $data);
        }

        return [];

    }
}