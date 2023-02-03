<?php
/***
 * File: ApiResponse.php
 * Product: aiorarpApi
 * HeroldCMS | heroldcms.eu
 * Author: Martin Horáček | martinhoracek.cz
 * Date: 17.11.2022
 * Time: 16:09
 * Codes may not be redistributed the owner's consent.
 * Copyrights (c) 2022. All right Reserved
 ***/


namespace App\Traits;

use Illuminate\Http\JsonResponse;

Trait ApiResponse
{


    protected function handleSuccess(string|array $getData, string $getMessage = null, int $getCode = 200): JsonResponse
    {

        return response()->json([
            'status' => 'success',
            'message' => $getMessage,
            'data' => $getData
        ], $getCode)->header('Content-Type', "application/json");
    }

    protected function handleFailed(string $getMessage, int  $getCode = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $getMessage,
            'data' => null
        ], $getCode)->header('Content-Type', "application/json");
    }

}
