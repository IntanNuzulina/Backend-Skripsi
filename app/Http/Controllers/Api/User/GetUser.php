<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GetUser extends Controller
{
    public function __invoke()
    {
        return User::all();
    }
}
