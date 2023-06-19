<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUser;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(CreateUser $request) 
    {
        $validatedData = $request->validated();
        $user = new User([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            // 加密bcrypt
            'password' =>bcrypt($validatedData['password']),
        ]);
        $user->save();
        return response('success',201);
    }
}
