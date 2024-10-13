<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tampil($s) {
        return DB::table($s)    // Select table dynamically
                 ->get();       // Fetch all records
    }

    protected $table = 'user'; // Specify the table name if not the plural form of the model name

    // Fetch user by conditions
    public static function getWhere($where) {
        return self::where($where)->first();
    }


  
public function joinResult2($table, $table1, $on, $table2, $on2, $where)
{
    $query = DB::table($table)
        ->join($table1, function($join) use ($on) {
            $join->on($on[0], '=', $on[1]);
        })
        ->join($table2, function($join) use ($on2) {
            $join->on($on2[0], '=', $on2[1]);
        })
        ->where($where);

    return $query->get();
}


public function tampilwhere($table, $conditions) {
    return DB::table($table)
             ->where($conditions)
             ->get();
}

public function tambah($table, $data)
{
    return DB::table($table)->insert($data);
}

}
