<?php

class Timer {

    private $seconds;

    public function __construct()
    {
        $this->seconds = microtime(true);
    }

    public function reset()
    {
        $this->seconds = microtime(true);
    }

    /***
     * @param string $format (s/m/h)
     */
    public function getRuntime($format = 's')
    {
        switch ($format) {
            case 'm':
                $division = 60;
                break;
            case 'h':
                $division = 60*60;
                break;
            case 'd':
                $division = 60*60*24;
                break;
            default:
                $division = 1;
                break;
        }

        $time_end = microtime(true);

        return ($time_end - $this->seconds)/$division;

    }

}