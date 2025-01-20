<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthServices;
use App\DTO\Auth\CreateTokenAuthDTO;
use Exception;

class AuthController extends Controller
{
    private $authServices;

    public function __construct()
    {
        $this->authServices = new AuthServices();
    }

    public function getUser()
    {
        $data = $this->authServices->getUser();
        return $this->response($data, 'User data', 200);
    }

    public function login(Request $request)
    {
        try {
            $dtoCreateToken = new CreateTokenAuthDTO($request->all());

            $token = $this->authServices->createToken($dtoCreateToken);

            if ($token == null) {
                return $this->response([], 'Unauthorized', 401);
            }

            return $this->response(['token' => $token], 'Token created', 201);
        } catch (Exception $e) {
            return $this->response(['error' => $e->getMessage()], 'Error interno del servidor', 500);
        }
    }


    public function logout(Request $request)
    {
        $this->authServices->logout();
        return $this->response([], 'Successfully logged out', 200);
    }
}
