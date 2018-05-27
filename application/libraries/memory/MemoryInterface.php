<?php
namespace kit\memory;

interface MemoryInterface
{
	public function load();
	public function save($items);
}

