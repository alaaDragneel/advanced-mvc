<?php

namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view->render('blog.index', ['welcome' => 'welcome alaaDragneel']);
    }
}