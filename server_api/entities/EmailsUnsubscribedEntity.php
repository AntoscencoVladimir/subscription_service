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
        $select->from(self::$table, ['id', 'id_email', 'ip', 'country','user_agent', 'created'])
            ->where(' `created` BETWEEN ? AND ? ',[$from,$to]);
        $data = self::$db->select($select);

        if ($data) {

            return self::createCollection(get_called_class(), $data);
        }

        return [];

    }
}