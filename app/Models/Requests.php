<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;
    protected $table = "requests";

    protected $fillable = [
        'user_id',
        'kelompok',
        'nama',
        'stok',
        'deskripsi',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // atau Pegawai::class jika menggunakan pegawai
    }
}
