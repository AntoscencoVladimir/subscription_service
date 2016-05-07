<?php

class EmailsEntity extends Entity
{
    public static $table = 'emails';

    public function __construct($tableName = 'emails')
    {
        parent::__construct('emails');
        $this->add('email');
        $this->add('reserved_server');
        $this->add('reserved_date');
    }


    protected static function getEmailsForClient($limit, $clientId)
    {
        $select = new Select(self::$db);
        $select->from([self::$table], [
            self::$table . '.id',
            'email',
            'reserved_server',
            'reserved_date',
        ])
            ->leftJoin(EmailsUnsubscribedEntity::$table,[ self::$table . '.id' => EmailsUnsubscribedEntity::$table . '.id_email'])
            ->where(self::$table . '.reserved_server IS NULL ' .
                'AND ' . self::$table . '.reserved_date IS NULL ' .
                'AND ' . EmailsUnsubscribedEntity::$table . '.id_email IS NULL '
            )
            ->limit($limit);

        $data = self::$db->select($select);

        if ($data) {
            return self::createCollection(get_called_class(), $data);
        }
        return [];
    }

    public static function reserveEmailsForClient($limit, $clientId)
    {
        $collection = self::getEmailsForClient($limit, $clientId);
        $emails = [];
        if (!empty($collection)) {
            foreach ($collection as $item) {
                $item->reserved_server = $clientId;
                $item->reserved_date = date('Y-m-d H:i:s');
                $item->save();
                $emails[] = $item->email;
            }
        }

        return $emails;
    }
}