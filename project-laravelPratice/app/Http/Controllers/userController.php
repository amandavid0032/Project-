<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\userModel;
use App\Models\user;


class userController extends Controller
{
    public static function singleUser(String $id)
    {
        $user = userModel::singleUserData($id);
        return view('admin.singleUser', ['data' => $user]);
    }

    public function showUser()
    {
        $data = userModel::getUsers();
        return view('admin.ViewAdmin', ['data' => $data]);
    }

    public function user(Request $request)
    {
        $data = userModel::getUsers();
        return view('user.viewUser', ['data' => $data]);

    }

    public function addNewUser(Request $req)
    {
      $validatedData=$req->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'fathername' => 'required',
            'mothername' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'password' => 'required|alpha_num|min:8',
            'confirm_password' => 'required|same:password',
            'role' => 'required | numeric',
            'street' => 'required',
            'additional_info' => 'required',
            'zip_code' => 'required |numeric',
            'place' => 'required',
            'country' => 'required',
            'code' => 'required | numeric',
            'phone_number' => 'required |numeric',
        ]);

        $validatedData['password'] = md5($validatedData['password']);
        $userData = [
            'f_name' => $validatedData['firstname'],
            'l_name' => $validatedData['lastname'],
            'father_name' => $validatedData['fathername'],
            'mother_name' => $validatedData['mothername'],
            'gender' => $validatedData['gender'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'type' => $validatedData['role'], // Assuming 'role' corresponds to 'type'
            'street_no' => $validatedData['street'],
            'additional_info' => $validatedData['additional_info'],
            'zip_code' => $validatedData['zip_code'],
            'place' => $validatedData['place'],
            'country' => $validatedData['country'],
            'code' => $validatedData['code'],
            'phone' => $validatedData['phone_number'],
        ];
        $user = UserModel::addUser($userData);

        if ($user) {
            return redirect()->back()->with('success', 'User added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add user. Please try again.');
        }

       
    }

    public function index()
    {
        $user = user::all();
        return $user;
    }


}
