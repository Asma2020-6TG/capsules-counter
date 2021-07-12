<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CangePasswordController extends Controller
{
    public function changePassword()
    {

        return response()->json(['message' => 'Your password had changed successfully'],200);
    }
}
