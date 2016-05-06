<?php

class DataBase
{

    /** @var mysqli */
    private $mysqli;

    /** @var  string */
    private $sq;

    public function __construct($dbHost, $dbUser, $dbPassword, $dbName, $sq)
    {
        $this->mysqli = @new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        if ($this->mysqli->connect_errno) {
            exit('Database mysqli connection error');
        }
        $this->sq = $sq;
        $this->mysqli->query("SET lc_time_names='ru_RU'");
        $this->mysqli->set_charset('utf8');
    }

    public function getSq()
    {

        return $this->sq;
    }

    public function getQuery($query, $params)
    {
        if (!empty($params)) {
            $offset = 0;
            $lenSq = strlen($this->sq);
            for ($i = 0; $i < count($params); $i++) {
                $pos = strpos($query, $this->sq, $offset);
                if (is_null($params[$i])) {
                    $arg = 'NULL';
                } else {
                    $arg = "'" . $this->mysqli->real_escape_string($params[$i]) . "'";
                }
                $query = substr_replace($query, $arg, $pos, $lenSq);
                $offset = $pos + strlen($arg);
            }
        }

        return $query;
    }

    public function getTableName($tableName)
    {
        return $tableName;
    }

    public function select(Select $select)
    {
        $resultSet = $this->getResultSet($select, true, true);
        if (!$resultSet) {
            return false;
        }
        $array = array();
        while (($row = $resultSet->fetch_assoc()) != false) {
            $array[] = $row;
        }

        return $array;
    }

    public function selectRow(Select $select)
    {
        $resultSet = $this->getResultSet($select, false, true);
        if (!$resultSet) {
            return false;
        }

        return $resultSet->fetch_assoc();
    }

    private function getResultSet(Select $select, $zero, $one)
    {
        $resultSet = $this->mysqli->query($select);
        if (!$resultSet) {
            return false;
        }
        if ((!$zero) && ($resultSet->num_rows == 0)) {
            return false;
        }
        if ((!$one) && ($resultSet->num_rows == 1)) {
            return false;
        }

        return $resultSet;
    }


    public function __destruct()
    {
        if (($this->mysqli) && (!$this->mysqli->connect_errno)) {
            $this->mysqli->close();
        }
    }

    public function insert($tableName, $row)
    {
        if (count($row) == 0) {

            return false;
        }
        $tableName = $this->getTableName($tableName);
        $fields = '(';
        $values = 'VALUES (';
        $params = array();
        foreach ($row as $key => $value) {
            $fields .= "`$key`,";
            $values .= $this->sq . ',';
            $params[] = $value;
        }
        $fields = substr($fields, 0, -1);
        $values = substr($values, 0, -1);
        $fields .= ')';
        $values .= ')';
        $query = "INSERT INTO `$tableName` $fields $values";

        return $this->query($query, $params);
    }

    public function update($tableName, $row, $where = false, $params = array())
    {
        if (count($row) == 0) {
            return false;
        }
        $tableName = $this->getTableName($tableName);
        $query = "UPDATE `$tableName` SET ";
        $paramsAdd = array();
        foreach ($row as $key => $value) {
            $query .= "`$key` = " . $this->sq . ",";
            $paramsAdd[] = $value;
        }
        $query = substr($query, 0, -1);
        if ($where) {
            $params = array_merge($paramsAdd, $params);
            $query .= " WHERE $where";
        }

        return $this->query($query, $params);
    }

    public function delete($tableName, $where = false, $params = array())
    {
        $tableName = $this->getTableName($tableName);
        $query = "DELETE FROM `$tableName`";
        if ($where) {
            $query .= " WHERE $where";
        }

        return $this->query($query, $params);
    }

    private function query($query, $params = false)
    {
        $success = $this->mysqli->query($this->getQuery($query, $params));

        if (!$success) {
            return false;
        }

        if ($this->mysqli->insert_id === 0) {
            return true;
        }

        return $this->mysqli->insert_id;
    }

    public function selectCell(Select $select)
    {
        $result_set = $this->getResultSet($select, false, true);
        if (!$result_set) {
            return false;
        }
        $arr = array_values($result_set->fetch_assoc());
        return $arr[0];
    }
}