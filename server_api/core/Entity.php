<?php

class Entity
{


    /** @var AbstractDataBase */
    protected static $db = null;
    private $id = null;
    private $properties = [];

    protected $tableName = '';

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public static function setDb(DataBase $db)
    {
        self::$db = $db;
    }

    public function findOneById($id)
    {
        $id = (int)$id;
        if ($id < 1) {
            return false;
        }
        $select = new Select(self::$db);
        $select = $select->from($this->tableName, $this->getSelectFields())
            ->where(' `id` = ' . self::$db->getSq(), [$id]);
        $row = self::$db->selectRow($select);
        if (!$row) {

            return false;
        }
        if ($this->init($row)) {

            return $this->postLoad();
        }

        return false;
    }

    public function init($row)
    {
        foreach ($this->properties as $key => $value) {
            $val = $row[$key];
            $this->properties[$key]['value'] = $val;
        }
        $this->id = $row['id'];
        return $this->postInit();
    }

    public function isSaved()
    {
        return $this->getId() > 0;
    }

    public function getId()
    {
        return (int)$this->id;
    }

    public function save()
    {
        $update = $this->isSaved();
        if ($update) {
            $commit = $this->preUpdate();
        } else {
            $commit = $this->preInsert();
        }
        if (!$commit) {

            return false;
        }
        $row = [];
        foreach ($this->properties as $key => $value) {
            $row[$key] = $value['value'];
        }
        if (count($row) > 0) {
            if ($update) {
                $success = self::$db->update($this->tableName, $row, "`id` = " . self::$db->getSq(),
                    array($this->getId()));
                if (!$success) {
                    throw new Exception();
                }
            } else {
                $this->id = self::$db->insert($this->tableName, $row);
                if (!$this->id) {
                    throw new Exception();
                }
            }
        }
        if ($update) {

            return $this->postUpdate();
        }
        return $this->postInsert();
    }

    public function delete()
    {
        if (!$this->isSaved()) {
            return false;
        }
        if (!$this->preDelete()) {
            return false;
        }
        $success = self::$db->delete($this->tableName, '`id` = ' . self::$db->getSq(), [$this->getId()]);
        if (!$success) {
            throw new Exception();
        }
        $this->id = null;

        return $this->postDelete();
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->properties)) {
            $this->properties[$name]['value'] = $value;

            return true;
        } else {
            $this->$name = $value;
        }

        return false;
    }

    public function __get($name)
    {
        if ($name == 'id') {
            return $this->getId();
        }

        return array_key_exists($name, $this->properties) ? $this->properties[$name]['value'] : null;
    }

    /**
     * @param $class
     * @param $data
     * @return array
     * @throws Exception
     */
    public static function createCollection($class, $data)
    {
        $ret = [];

        if (!class_exists($class)) {
            throw new Exception();
        }

        /** @var Entity */
        $testObj = new $class();
        if (!$testObj instanceof Entity) {
            throw new Exception();
        }
        foreach ($data as $row) {
            /** @var Entity */
            $obj = new $class();
            $obj->init($row);
            $ret[$obj->getId()] = $obj;
        }
        return $ret;
    }

    public static function findAll($count = false, $offset = false)
    {
        $class = get_called_class();
        return self::getAllWithOrder($class::$table, $class, 'id', true, $count, $offset);
    }

    public static function countAll()
    {
        $class = get_called_class();
        return self::getCountOnWhere($class::$table, false, false);
    }

    protected static function countAllByField($tableName, $field, $value)
    {
        return self::getCountOnWhere($tableName, "`$field` = " . self::$db->getSq(), [$value]);
    }

    protected static function findAllBy(
        $tableName,
        $class,
        $where = false,
        $values = false,
        $order = false,
        $ask = true,
        $count = false,
        $offset = false
    ) {
        $select = new Select(self::$db);
        $select->from($tableName, "*");
        if ($where) {
            $select->where($where, $values);
        }
        if ($order) {
            $select->order($order, $ask);
        } else {
            $select->order("id");
        }
        if ($count) {
            $select->limit($count, $offset);
        }
        $data = self::$db->select($select);

        return Entity::createCollection($class, $data);
    }

    protected static function getCountOnWhere($tableName, $where = false, $values = false)
    {
        $select = new Select(self::$db);
        $select->from($tableName, ["COUNT(id)"]);
        if ($where) {
            $select->where($where, $values);
        }

        return self::$db->selectCell($select);
    }

    public static function findByField(
        $tableName,
        $class,
        $field,
        $value,
        $order = false,
        $ask = true,
        $count = false,
        $offset = false
    ) {
        return self::getAllOnWhere($tableName, $class, "`$field` = " . self::$db->getSq(), [$value], $order, $ask,
            $count, $offset);
    }

    protected static function getAllOnWhere(
        $table_name,
        $class,
        $where = false,
        $values = false,
        $order = false,
        $ask = true,
        $count = false,
        $offset = false
    ) {
        $select = new Select(self::$db);
        $select->from($table_name, "*");
        if ($where) {
            $select->where($where, $values);
        }
        if ($order) {
            $select->order($order, $ask);
        } else {
            $select->order("id");
        }
        if ($count) {
            $select->limit($count, $offset);
        }
        $data = self::$db->select($select);
        if ($data) {
            return Entity::createCollection($class, $data);
        }
        return [];
    }

    public function findOneByField($field, $value)
    {
        $select = new Select(self::$db);
        $select->from($this->tableName, "*")
            ->where("`$field` = " . self::$db->getSq(), [$value]);
        $row = self::$db->selectRow($select);
        if ($row) {
            if ($this->init($row)) {
                return $this->postLoad();
            }
        }
        return false;
    }

    public static function findByIds($ids, $field)
    {
        $class = get_called_class();
        $select = new Select(self::$db);
        $select->from($class::$table, "*")
            ->whereIn($field, $ids);
        $data = self::$db->select($select);
        return Entity::createCollection($class, $data);
    }

    protected function preInsert()
    {
        return true;
    }

    protected function postInsert()
    {
        return true;
    }

    protected function preUpdate()
    {
        return true;
    }

    protected function postUpdate()
    {
        return true;
    }

    protected function preDelete()
    {
        return true;
    }

    protected function postDelete()
    {
        return true;
    }

    protected function postInit()
    {
        return true;
    }

    protected function postLoad()
    {
        return true;
    }

    protected function add($field, $default = null, $type = null)
    {
        $this->properties[$field] = array("value" => $default, "type" => $type);
    }

    private function getSelectFields()
    {
        $fields = array_keys($this->properties);
        array_push($fields, "id");
        return $fields;
    }
}