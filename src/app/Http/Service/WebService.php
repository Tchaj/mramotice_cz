<?php
/***
 * File: WebService.php
 * Product: mramotice_cz
 * HeroldCMS | heroldcms.eu
 * Author: Martin Horáček | martinhoracek.cz
 * Date: 03.02.2023
 * Time: 16:37
 * Codes may not be redistributed the owner's consent.
 * Copyrights (c) 2023. All right Reserved
 ***/


namespace App\Http\Service;

use App\Models\WebInfo;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Cache;

class WebService
{
    use ApiResponse;

    public static function getShop(): ?WebInfo
    {
            $web = WebInfo::first();
            if (empty($web) || !$web->id) return null;
            return $web;

    }

    public function adminGetDashboard()
    {

            $dashboard = Cache::get("admin_dashboard", function () {
                return $this->setDashboard();
            });

            return $this->handleSuccess($this->toArray($dashboard), "dashboard_data");

    }

    public function setDashboard(string $locale = "cs")
    {

            Cache::forget("admin_dashboard");
            $dashboard = [];
            $dashboard["total_orders"] =  null;
            $dashboard["orders_today"] = null;
            $dashboard["registered_users"] = null;
            $dashboard["registered_users_today"] = null;
            $dashboard["recent_orders"] = [];
            $dashboard["bestsellers"] = [];
            $dashboard["bestsellers_today"] = [];
            return $dashboard;
    }



}
