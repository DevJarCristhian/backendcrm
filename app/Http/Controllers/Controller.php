<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function response($data, $message, $code)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $code);
    }
}
