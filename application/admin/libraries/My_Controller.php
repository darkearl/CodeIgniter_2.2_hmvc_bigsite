<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class My_Controller extends CI_Controller
{
    public $data = array();
    function __construct ()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE); //debug 
    }
}