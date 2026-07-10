<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Validator;

trait Helper {

    use LogError;
    public static function validationId(string $pkName, string $table, mixed $value): bool
    {
        try{
            $validator = Validator::make([$pkName => $value], [
                $pkName => 'required|integer|exists:' . $table . ',' . $pkName,
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            };
            return true;
        }catch (Exception $error){
            self::theLog('validationId', 'Traits/Helper', $error);
            return false;
        }   
    }
}