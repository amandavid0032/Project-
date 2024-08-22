<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class loginModel extends Model
{
    use HasFactory;

    public static function loginUser($email, $password)
    {
        $result = DB::table('user')
            ->where('email', $email)
            ->where('password', $password)
            ->first();

        return $result;
    }


}
