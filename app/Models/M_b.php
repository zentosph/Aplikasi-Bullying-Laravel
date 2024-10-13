<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class M_b extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function tampil($s) {
        return DB::table($s)    // Select table dynamically
                 ->get();       // Fetch all records
    }



    // Fetch user by conditions
    public static function getWhere($table,$where) {
        return self::from($table)->where($where)->first();
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

public function statusChange($table, $column, $noTrans, $where)
{
    // Update the specified column with the transaction number based on the where condition
    DB::table($table)->where($where)->update([$column => $noTrans]);
}

public function joinRow($pil, $tabel1, $on, $where)
{
    return DB::table($pil)
        ->join($tabel1, function($join) use ($on) {
            $join->on($on[0], '=', $on[1]);
        }, 'right') // Right join
        ->where($where)
        ->first(); // Mengambil satu baris data
}


public function joinRow2($tabel, $tabel1, $on, $tabel2, $on2, $where, $where1)
{
    return DB::table($tabel)
        ->join($tabel1, function($join) use ($on) {
            $join->on($on[0], '=', $on[1]); // Menggunakan array untuk join pertama
        })
        ->join($tabel2, function($join) use ($on2) {
            $join->on($on2[0], '=', $on2[1]); // Menggunakan array untuk join kedua
        })
        ->where($where)
        ->where($where1)
        ->first(); // Mengambil satu baris data
}



public function joinRows($table1, $table2, $on, $where)
{
    return DB::table($table1)
        ->join($table2, function($join) use ($on) {
            $join->on($on[0], '=', $on[1]);
        }, 'right') // Right join
        ->where($where)
        ->first(); // Get results
}

public function edit($table, $data, $where) {
    return DB::table($table)->where($where)->update($data);
}

public function hapus($table, $where) {
    return DB::table($table)->where($where)->delete();
}

public function updateMenuVisibility(array $data, $id_menu)
{
    // Prepare the data for update
    $updateData = [];
    foreach ($this->allowedFields as $field) {
        $updateData[$field] = isset($data[$field]) ? 1 : 0;
    }

    // Perform the update using Laravel's Query Builder
    return DB::table($this->table)
             ->where($this->primaryKey, $id_menu)
             ->update($updateData);
}





public function joinRowss($table1, $table2, $onColumn1, $onColumn2, $where)
    {
        return DB::table($table1)
            ->join($table2, function($join) use ($onColumn1, $onColumn2) {
                $join->on($onColumn1, '=', $onColumn2);
            })
            ->where($where)
            ->get(); // Use first() if you expect only one result
    }


    public function joinMultiple($tables, $joinConditions)
    {
        $query = DB::table($tables[0]);

        // Loop through each table and add joins
        for ($i = 1; $i < count($tables); $i++) {
            $query->join($tables[$i], function($join) use ($joinConditions, $i) {
                $join->on($joinConditions[$i-1][0], '=', $joinConditions[$i-1][1]);
            });
        }


        return $query->get(); // Use first() if you expect only one result
    }
    
    public function joinResultByDate($table, $table1, $on, $table2, $on2, $startDate = null, $endDate = null)
{
    // Use Laravel's Query Builder to build the query
    $query = DB::table($table)
        ->join($table1, function ($join) use ($on) {
            // Split the string into two parts for the join condition
            [$left, $right] = explode(' = ', $on);
            $join->on($left, '=', $right);
        })
        ->join($table2, function ($join) use ($on2) {
            // Split the string into two parts for the join condition
            [$left, $right] = explode(' = ', $on2);
            $join->on($left, '=', $right);
        });
    
    // Apply date filters if provided
    if ($startDate) {
        $query->where('create_at', '>=', $startDate);
    }
    if ($endDate) {
        $query->where('create_at', '<=', $endDate);
    }
    
    return $query->get();
}

    
}
