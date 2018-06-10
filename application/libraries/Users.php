<?php
namespace kit;
use kit\memory\MemoryInterface;

class Users 
{
    private $items = [];
    private $memory;
    
    public function __construct(MemoryInterface $memory)
    {
        $this->memory = $memory;
        $this->loadItems();
    }
    
    public function loginFact() 
    {
        $flag = false;
        if ($this->items !== []) {
            if (is_numeric($this->items['id']) && filter_var($this->items['email'], FILTER_VALIDATE_EMAIL)) {
                $flag = true;
            } 
        }
        return $flag;
    }
    public function isAdmin() {
        $flag = false;
        if ($this->loginFact() && $this->items['role'] === 'Administrator') {
            $flag = true;
        }
        return $flag;
    }
    public function saveItems($elements=[]) 
    {
        $this->memory->save($elements);
    }
    public function item($key='') 
    {
        if (key_exists($key, $this->items)) {
            $out = $this->items[$key];
        } else {
            $out = false;
        }
        return $out;
    }
    public function loadItems() 
    {
        $this->items = $this->memory->load();
    }
    
    public function clear()
    {
        $this->items = [];
        $this->saveItems($this->items);
    }
}
