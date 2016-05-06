<?php

class Request
{

    private static $sef_data = [];
    private $data;

    public function __construct()
    {
        if (empty($_REQUEST)){
            $input = json_decode(file_get_contents('php://input'), true);
            $this->data = $this->xss(array_merge($input, self::$sef_data));
        } else {
            $this->data = $this->xss(array_merge($_REQUEST, self::$sef_data));
        }

    }

    public static function addSEFData($sef_data)
    {
        self::$sef_data = $sef_data;
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    public function hasProperty($name)
    {
        return isset($this->data[$name]);
    }

    private function xss($data)
    {
        if (is_array($data)) {
            $escaped = array();
            foreach ($data as $key => $value) {
                $escaped[$key] = $this->xss($value);
            }
            return $escaped;
        }
        return trim(htmlspecialchars($data));
    }

}
