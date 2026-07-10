<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait LogError
{

    public static function theLog(string $functionName, string $className, Exception $error)
    {
        Log::error('error in ' . $functionName . '@' . $className);
        if(!is_null($error)){
            Log::error('error is : ' . $error->getMessage());
        }
        return false;
    }
}