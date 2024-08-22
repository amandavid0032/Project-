<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class userModel extends Model
{
    use HasFactory;
    public static function getUsers()
    {
        $user = DB::table('user')->orderBy('uid')->Paginate(4);
        return $user;
    }

    public static function addUser(array $data)
    {
        return DB::table('user')->insert($data); 
    }


    public static function singleUserData($id){
        $user = DB::table('user')->where('uid',$id)->get();
     return $user;
    }       


    protected function casts():array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
    
}
