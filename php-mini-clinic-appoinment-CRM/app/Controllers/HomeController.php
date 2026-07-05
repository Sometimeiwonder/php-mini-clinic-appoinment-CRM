<?php

class HomeController
{
    public function index(): void
    {
        if (!empty($_SESSION['user_id'])) {
            redirect('/dashboard');
        }
        redirect('/login');
    }
}
