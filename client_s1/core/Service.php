<?php

class Service
{

    private $timer;
    private $fp;
    private $lockFile = 'service.lock';

    public function __construct($lockFile)
    {
        $this->timer = new Timer();

        $this->lockFile = sys_get_temp_dir() . '/' . $lockFile;

        if (!is_file($this->lockFile)) {
            touch($this->lockFile);
        }

        $this->fp = fopen($this->lockFile, 'r+');
        if (!flock($this->fp, LOCK_EX | LOCK_NB)) {
            throw new Exception('Service is already running...');
        }

    }

    public function getRuntime($format = 's')
    {
        return $this->timer->getRuntime($format);
    }

    public function resetTimer()
    {
        $this->timer->reset();
        return true;
    }

    public function __destruct()
    {
        fclose($this->fp);
        unlink($this->lockFile);
    }
}