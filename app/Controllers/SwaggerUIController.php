<?php

namespace App\Controllers;

class SwaggerUIController extends BaseController
{
    public function index()
    {
        return view('swagger_ui');
    }
}