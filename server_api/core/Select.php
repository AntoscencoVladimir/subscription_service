<?php

class Select
{

    /** @var DataBase */
    private $db;
    private $from = '';
    private $where = '';
    private $join = '';
    private $limit = '';
    private $order = '';

    public function __construct(DataBase $db)
    {
        $this->db = $db;
    }

    /**
     * @param $tableNames array|string
     * @param $fields array| string '*'
     * @return $this Select
     */
    public function from($tableNames, $fields)
    {
        $from = '';
        if ($fields == "*") {
            $from = "*";
        } else {
            for ($i = 0; $i < count($fields); $i++) {
                //@todo: separate `table.field`
                if (($pos_1 = strpos($fields[$i], '(')) !== false) {
                    $pos_2 = strpos($fields[$i], ')');
                    $from .= substr($fields[$i], 0, $pos_1) . '(`' . substr($fields[$i], $pos_1 + 1,
                            $pos_2 - $pos_1 - 1) . "`),";
                } else {
                    if (strpos($fields[$i], '.') !== false) {
                        $separatedFields = explode('.', $fields[$i]);
                        $wrappedField = '';
                        foreach ($separatedFields as $field) {
                            $wrappedField .= $field . '`.`';
                        }
                        $wrappedField = substr($wrappedField, 0, -3);
                        $from .= '`' . $wrappedField . '`,';
                    } else {
                        $from .= '`' . $fields[$i] . '`,';
                    }

                }
            }
            $from = substr($from, 0, -1);
        }
        $tables = '';
        if (is_array($tableNames)) {
            foreach ($tableNames as $tableName) {
                $tableName = $this->db->getTableName($tableName);
                $tables .= " `$tableName`,";
            }
            $tables = substr($tables, 0, -1);
        } else {
            $tableName = $this->db->getTableName($tableNames);
            $tables .= " `$tableName`";
        }

        $from .= ' FROM ' . $tables;
        $this->from = $from;

        return $this;
    }

    public function where($where, $values = array(), $and = true)
    {
        if ($where) {
            $where = $this->db->getQuery($where, $values);
            $this->addWhere($where, $and);
        }
        return $this;
    }

    private function addWhere($where, $and)
    {
        if ($this->where) {
            if ($and) {
                $this->where .= ' AND ';
            } else {
                $this->where .= ' OR ';
            }
            $this->where .= $where;
        } else {
            $this->where = "WHERE $where";
        }
    }

    public function leftJoin($table, $fieldPair)
    {
        reset($fieldPair);
        $this->join = "LEFT JOIN `$table` ON `" . preg_replace('/\./', '`.`', key($fieldPair)) . "`=`" . preg_replace('/\./', '`.`',array_values($fieldPair)[0]) .'`' ;

        return $this;
    }

    public function limit($count, $offset = 0)
    {
        $count = (int)$count;
        $offset = (int)$offset;
        if ($count < 0 || $offset < 0) {
            return false;
        }
        $this->limit = "LIMIT $offset, $count";

        return $this;
    }

    public function __toString()
    {
        if ($this->from) {
            $ret = 'SELECT ' . $this->from . ' ' . $this->join . ' ' . $this->where .' ' . $this->order . ' ' . $this->limit;
        } else {
            $ret = '';
        }

        return $ret;
    }

    public function order($field, $ask = true)
    {
        if (is_array($field)) {
            $this->order = "ORDER BY ";
            if (!is_array($ask)) {
                $temp = array();
                for ($i = 0; $i < count($field); $i++) {
                    $temp[] = $ask;
                }
                $ask = $temp;
            }
            for ($i = 0; $i < count($field); $i++) {
                $this->order .= "`" . $field[$i] . "`";
                if (!$ask[$i]) {
                    $this->order .= " DESC,";
                } else {
                    $this->order .= ",";
                }
            }
            $this->order = substr($this->order, 0, -1);
        } else {
            $this->order = "ORDER BY `$field`";
            if (!$ask) {
                $this->order .= " DESC";
            }
        }
        return $this;
    }

    public function whereIn($field, $values, $and = true)
    {
        $where = "`$field` IN (";
        $where .= str_repeat($this->db->getSq() . ",", count($values));
        $where = substr($where, 0, -1);
        $where .= ")";
        return $this->where($where, $values, $and);
    }

}