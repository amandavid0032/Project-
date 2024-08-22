<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginModel;
use App\Models\userModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function loginUser(Request $request)
    {
        $email = $request->input('email');
        $password = md5($request->input('password'));
        $result = LoginModel::loginUser($email, $password);

        if (!empty($result)) {
            session([
                'email' => $result->email,
                'f_name' => $result->f_name,
                'type' => $result->type,
                'uid' => $result->uid,
                'success_message' => 'You Login Successfully'
            ]);

            if ($result->type == 1) {
                return redirect()->route('show');
            } elseif ($result->type == 0) {
                return redirect()->route('showUser');
            }
        } else {
            return view('index');
        }
    }


    public function showUser(Request $request)
    {
        $data = userModel::paginate(5);
        return view('admin.ViewAdmin', compact('data'));
    }

    public function user(Request $request)
    {
        $data = userModel::paginate(5);
        return view('user.viewUser', compact('data'));
    }
}
