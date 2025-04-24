<?php

namespace App\Controllers;

class Home extends BaseController
{ 
    protected $helpers = ['url', 'html'];
    
    public function index(): string
    {
        return view('welcome_message');
    }    
}
