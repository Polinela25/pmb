<?php

namespace App\Controllers;


class AdminDashboard extends BaseController
{
    public function index()
    {
        // Inisialisasi model
         return view('konten/admin/dashboard/index.php');
    }
}
