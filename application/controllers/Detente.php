<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use kit\Foo;

class Detente extends CI_Controller
{
	public $foo;

	public function index()
	{
            $this->foo = new Foo();
            $this->foo->one();
	}
}