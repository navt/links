<?php
namespace kit\memory;
use kit\memory\MemoryInterface;

class SessionMemory implements MemoryInterface
{

    private $key;

    public function __construct($key='user')
    {
        $this->key = $key;
    }

    public function load()
    {
        return isset($_SESSION[$this->key]) ? unserialize($_SESSION[$this->key]) : [];
    }

    public function save($items)
    {
        $_SESSION[$this->key] = serialize($items);
    }

}


