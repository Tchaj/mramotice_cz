<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    //web_info

    /**
     * @var WebService
     */
    private $service;

    public function get()
    {
        return $this->service->getShop();
    }

    public function languages()
    {
            return $this->handleSuccess(["id" => "cs", "name"=>"czech", "code" => "cs_CZ", "default" => true]);

    }

    public function adminDashboard()
    {
            return $this->service->adminGetDashboard();

    }
}
