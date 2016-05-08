<?php

class DataBase
{

    /** @var SQLite3 */
    private $sqlite;

    /** @var  string */
    private $sq;

    public function __construct($dbPath, $dbName, $sq)
    {
        try {
            $this->sqlite = new SQLite3($dbPath . '/' . $dbName);
        } catch (Exception $exception) {
            exit('Database sqlite3 connection error: ' . $exception->getMessage());
        }

        $this->sq = $sq;
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
                    $arg = "'" . $this->sqlite->escapeString($params[$i]) . "'";
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
        $array = [];
        while (($row = $resultSet->fetchArray(SQLITE3_ASSOC)) != false) {
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

        return $resultSet->fetchArray(SQLITE3_ASSOC);
    }

    private function getResultSet(Select $select, $zero, $one)
    {
        $resultSet = $this->sqlite->query($select);

        if ($resultSet === false) {
            return false;
        }

        $num_rows = 0;
        while ($resultSet->fetchArray(SQLITE3_ASSOC)) {
            $num_rows++;
        }

        if ((!$zero) && ($num_rows == 0)) {
            return false;
        }
        if ((!$one) && ($num_rows == 1)) {
            return false;
        }

        return $resultSet;
    }


    public function __destruct()
    {
        if ($this->sqlite) {
            $this->sqlite->close();
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
        $params = [];
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

        return $this->query($query, $params, 'insert');
    }

    public function update($tableName, $row, $where = false, $params = array())
    {
        if (count($row) == 0) {
            return false;
        }
        $tableName = $this->getTableName($tableName);
        $query = "UPDATE `$tableName` SET ";
        $paramsAdd = [];
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

    private function query($query, $params = false, $method = false)
    {

        try {
            $success = $this->sqlite->query($this->getQuery($query, $params));
        } catch (Exception $e) {
            return false;
        }

        if (!$success) {
            return false;
        }

        if ($method === 'insert') {
            return $this->sqlite->lastInsertRowID();
        }

        return $this->sqlite->changes();
    }

}